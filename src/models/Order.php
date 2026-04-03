<?php
/**
 * src/models/Order.php
 *
 * Order model - represents a completed or pending customer order.
 *
 * Attributes:
 *   order_id     - Auto-increment primary key
 *   user_id      - FK → users.user_id (who placed the order)
 *   total_amount - Order total in SGD at time of checkout
 *   status       - 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled'
 *   created_at   - When the order was placed
 *   updated_at   - Last status update
 *
 * Related table: order_items (order_id → order_items.order_id)
 *   Each order has one or more line items stored in order_items.
 *   order_items snapshots the product name and price at purchase time,
 *   so product edits/deletions never corrupt historical order records.
 */

class Order
{
    private PDO    $pdo;
    private string $table = 'orders';

    private const ALLOWED_STATUSES = [
        'pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed',
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieve all orders for a specific user, newest first.
     * Includes a count of line items per order so the history page can
     * show "3 items" without a second query per row.
     *
     * @param  int   $userId  The user_id to filter by.
     * @return array          Order rows with an extra `item_count` column.
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
                o.order_id,
                o.total_amount,
                o.status,
                o.created_at,
                o.shipping_name,
                o.shipping_address,
                o.shipping_city,
                o.shipping_postal,
                COUNT(oi.order_item_id) AS item_count
             FROM {$this->table} o
             LEFT JOIN order_items oi ON oi.order_id = o.order_id
             WHERE o.user_id = :user_id
             GROUP BY o.order_id
             ORDER BY o.created_at DESC"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Retrieve all orders (admin view), newest first.
     * Includes the customer's username and item count.
     *
     * @return array  All order rows with username and item_count.
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
                o.order_id,
                o.total_amount,
                o.status,
                o.created_at,
                u.username,
                COUNT(oi.order_item_id) AS item_count
             FROM {$this->table} o
             JOIN users u         ON u.user_id  = o.user_id
             LEFT JOIN order_items oi ON oi.order_id = o.order_id
             GROUP BY o.order_id
             ORDER BY o.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a single order with all its line items.
     *
     * We run two queries and merge them rather than a single JOIN that would
     * repeat the order-level columns for every line item - cleaner for the view.
     *
     * Returns an array shaped like:
     *   [
     *     'order_id'     => int,
     *     'user_id'      => int,
     *     'total_amount' => float,
     *     'status'       => string,
     *     'created_at'   => string,
     *     'items'        => [ [...], [...] ]   ← line items
     *   ]
     *
     * @param  int         $orderId  The order_id to look up.
     * @return array|false           Order with nested items, or false if not found.
     */
    public function getById(int $orderId): array|false
    {
        // 1. Fetch the order header row (joined with users for admin views).
        $orderStmt = $this->pdo->prepare(
            "SELECT o.order_id, o.user_id, o.total_amount, o.status, o.created_at,
                    o.shipping_name, o.shipping_address, o.shipping_city, o.shipping_postal,
                    u.username, u.email
             FROM {$this->table} o
             JOIN users u ON u.user_id = o.user_id
             WHERE o.order_id = :order_id
             LIMIT 1"
        );
        $orderStmt->execute([':order_id' => $orderId]);
        $order = $orderStmt->fetch();

        if (!$order) {
            return false;
        }

        // 2. Fetch all line items for this order.
        $itemsStmt = $this->pdo->prepare(
            "SELECT
                oi.order_item_id,
                oi.product_id,
                oi.product_name,
                oi.unit_price,
                oi.quantity,
                (oi.unit_price * oi.quantity) AS line_total
             FROM order_items oi
             WHERE oi.order_id = :order_id
             ORDER BY oi.order_item_id ASC"
        );
        $itemsStmt->execute([':order_id' => $orderId]);

        // Attach items to the order array for the view to iterate.
        $order['items'] = $itemsStmt->fetchAll();

        return $order;
    }

    /**
     * Create a new order from the user's cart contents inside a transaction.
     *
     * The four steps are atomic - if any one fails the entire operation is
     * rolled back, leaving the cart and stock unchanged.
     *
     * Steps:
     *   1. INSERT into orders                → captures total_amount
     *   2. INSERT each item into order_items → snapshots name + price at purchase time
     *   3. UPDATE products.stock_quantity    → deduct purchased quantities
     *   4. DELETE from cart_items            → clear the user's cart
     *
     * @param  int   $userId       The user placing the order.
     * @param  array $cartItems    Rows from CartItem::getByUser() (joined with products).
     * @param  float $totalAmount  Pre-calculated cart total.
     * @return int                 The new order_id, or 0 on failure.
     */
    public function create(
        int $userId,
        array $cartItems,
        float $totalAmount,
        string $name,
        string $address,
        string $city,
        string $postal
    ): int
    {
        try {
            $this->pdo->beginTransaction();

            // Step 1 - create the order header.
            $orderStmt = $this->pdo->prepare(
                "INSERT INTO {$this->table}
                    (user_id, total_amount, status, shipping_name, shipping_address, shipping_city, shipping_postal)
                VALUES
                    (:user_id, :total_amount, 'pending', :shipping_name, :shipping_address, :shipping_city, :shipping_postal)"
            );
            $orderStmt->execute([
                ':user_id'          => $userId,
                ':total_amount'     => $totalAmount,
                ':shipping_name'    => $name,
                ':shipping_address' => $address,
                ':shipping_city'    => $city,
                ':shipping_postal'  => $postal,
            ]);

            $orderId = (int) $this->pdo->lastInsertId();

            // Step 2 - insert a snapshot of each line item.
            // We snapshot name and unit_price here so that future product edits
            // or deletions never change what a customer sees in their order history.
            $itemStmt = $this->pdo->prepare(
                "INSERT INTO order_items
                    (order_id, product_id, product_name, unit_price, quantity)
                 VALUES
                    (:order_id, :product_id, :product_name, :unit_price, :quantity)"
            );

            // Step 3 - deduct stock for each item.
            $stockStmt = $this->pdo->prepare(
                            "UPDATE products
                            SET stock_quantity = stock_quantity - :qty_deduct
                            WHERE product_id = :product_id
                            AND stock_quantity >= :qty_check"
            );

            foreach ($cartItems as $item) {
                // Insert line item snapshot.
                $itemStmt->execute([
                    ':order_id'    => $orderId,
                    ':product_id'  => $item['product_id'],
                    ':product_name'=> $item['name'],
                    ':unit_price'  => $item['price'],
                    ':quantity'    => $item['quantity'],
                ]);

                // Deduct stock - roll back everything if a product ran out.
                $stockStmt->execute([
                    ':qty_deduct' => $item['quantity'],
                    ':product_id' => $item['product_id'],
                    ':qty_check'  => $item['quantity'],
                ]);

                if ($stockStmt->rowCount() === 0) {
                    // Stock was insufficient - abort the whole order.
                    $this->pdo->rollBack();
                    return 0;
                }
            }

            // Step 4 - clear the cart now that the order is confirmed.
            $clearStmt = $this->pdo->prepare(
                "DELETE FROM cart_items WHERE user_id = :user_id"
            );
            $clearStmt->execute([':user_id' => $userId]);

            $this->pdo->commit();
            return $orderId;

        } catch (\PDOException $e) {
            // Any DB error (constraint violation, connection loss, etc.)
            // rolls back the entire transaction cleanly.
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Order create failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Cancel an order and restore stock inside one transaction.
     *
     * @param  int        $orderId                The order to cancel.
     * @param  array|null $allowedCurrentStatuses Restrict allowed source statuses.
     *                                            Pass null to allow any non-cancelled status.
     * @return array                              Result payload with ok + reason keys.
     */
    public function cancelWithRestock(int $orderId, ?array $allowedCurrentStatuses = null): array
    {
        try {
            $this->pdo->beginTransaction();

            // Lock the order row so concurrent cancellation attempts stay consistent.
            $orderStmt = $this->pdo->prepare(
                "SELECT order_id, status
                 FROM {$this->table}
                 WHERE order_id = :order_id
                 LIMIT 1
                 FOR UPDATE"
            );
            $orderStmt->execute([':order_id' => $orderId]);
            $order = $orderStmt->fetch();

            if (!$order) {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'not_found'];
            }

            $currentStatus = (string) ($order['status'] ?? '');
            if ($currentStatus === 'cancelled') {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'already_cancelled'];
            }

            if (
                $allowedCurrentStatuses !== null
                && !in_array($currentStatus, $allowedCurrentStatuses, true)
            ) {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'invalid_status'];
            }

            $itemsStmt = $this->pdo->prepare(
                "SELECT product_id, quantity
                 FROM order_items
                 WHERE order_id = :order_id"
            );
            $itemsStmt->execute([':order_id' => $orderId]);
            $items = $itemsStmt->fetchAll();

            $restoreStmt = $this->pdo->prepare(
                "UPDATE products
                 SET stock_quantity = stock_quantity + :quantity
                 WHERE product_id = :product_id"
            );

            foreach ($items as $item) {
                $restoreStmt->execute([
                    ':quantity'   => (int) $item['quantity'],
                    ':product_id' => (int) $item['product_id'],
                ]);
            }

            if (!$this->updateStatus($orderId, 'cancelled')) {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'status_update_failed'];
            }

            $this->pdo->commit();
            return ['ok' => true, 'reason' => 'cancelled'];

        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['ok' => false, 'reason' => 'db_error'];
        }
    }

    /**
     * Mark a delivered order as received/completed by the customer.
     *
     * Only the order owner may call this, and only when status is 'delivered'.
     * Once marked completed, no further status changes are allowed.
     *
     * @param  int  $orderId  The order to complete.
     * @param  int  $userId   The customer's user_id (ownership check).
     * @return array          Result payload with ok + reason keys.
     */
    public function markAsReceived(int $orderId, int $userId): array
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare(
                "SELECT order_id, user_id, status
                 FROM {$this->table}
                 WHERE order_id = :order_id
                 LIMIT 1
                 FOR UPDATE"
            );
            $stmt->execute([':order_id' => $orderId]);
            $order = $stmt->fetch();

            if (!$order) {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'not_found'];
            }

            if ((int) $order['user_id'] !== $userId) {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'forbidden'];
            }

            if ($order['status'] !== 'delivered') {
                $this->pdo->rollBack();
                return ['ok' => false, 'reason' => 'invalid_status'];
            }

            $this->updateStatus($orderId, 'completed');

            $this->pdo->commit();
            return ['ok' => true, 'reason' => 'completed'];

        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Order markAsReceived failed: ' . $e->getMessage());
            return ['ok' => false, 'reason' => 'db_error'];
        }
    }

    /**
     * Permanently delete a cancelled or completed order (and its line items via CASCADE).
     *
     * @param  int      $orderId  The order to delete.
     * @param  int|null $userId   When supplied, also enforces that the order belongs to this user.
     * @return array              Result payload with ok + reason keys.
     */
    public function deleteFinished(int $orderId, ?int $userId = null): array
    {
        try {
            $conditions = "order_id = :order_id AND status = 'cancelled'";
            $params     = [':order_id' => $orderId];

            if ($userId !== null) {
                $conditions .= ' AND user_id = :user_id';
                $params[':user_id'] = $userId;
            }

            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$conditions}");
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                return ['ok' => false, 'reason' => 'not_eligible'];
            }

            return ['ok' => true];

        } catch (\PDOException $e) {
            error_log('Order deleteFinished failed: ' . $e->getMessage());
            return ['ok' => false, 'reason' => 'db_error'];
        }
    }

    /**
     * Update the status of an order (admin action).
     *
     * @param  int    $orderId  The order to update.
     * @param  string $status   New status - must match an ENUM value.
     * @return bool             True on success, false if status is invalid.
     */
public function updateStatus(int $orderId, string $status): bool
{
    if (!in_array($status, self::ALLOWED_STATUSES, true)) {
        return false;
    }

    $stmt = $this->pdo->prepare("
        UPDATE {$this->table}
        SET status = :status,
            updated_at = NOW()
        WHERE order_id = :order_id
    ");

    return $stmt->execute([
        ':status'   => $status,
        ':order_id' => $orderId,
    ]);
}
}