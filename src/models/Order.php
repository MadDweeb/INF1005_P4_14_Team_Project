<?php
/**
 * src/models/Order.php
 *
 * Order model — represents a completed or pending customer order.
 *
 * Attributes:
 *   order_id     — Auto-increment primary key
 *   user_id      — FK → users.user_id (who placed the order)
 *   total_amount — Order total in SGD at time of checkout
 *   status       — 'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled'
 *   created_at   — When the order was placed
 *   updated_at   — Last status update
 *
 * Related table: order_items (order_id → order_items.order_id)
 *   Each order has one or more line items stored in order_items.
 *   order_items snapshots the product name and price at purchase time,
 *   so product edits/deletions don't corrupt historical orders.
 */

class Order
{
    private PDO    $pdo;
    private string $table = 'orders';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieve all orders for a specific user (order history page).
     *
     * @param  int   $userId  The user_id to filter by.
     * @return array          Array of order rows, newest first.
     *
     * TODO: JOIN with order_items to include item counts or product names.
     */
    public function getByUser(int $userId): array
    {
        // TODO: Implement SELECT with WHERE user_id = :userId ORDER BY created_at DESC
        return [];
    }

    /**
     * Retrieve a single order with all its line items.
     *
     * @param  int         $orderId  The order_id to look up.
     * @return array|false           Order row with nested items, or false.
     *
     * TODO: JOIN order_items and products in a single query,
     *       or run two queries and merge the results.
     */
    public function getById(int $orderId): array|false
    {
        // TODO: Implement query
        return false;
    }

    /**
     * Create a new order from the user's cart contents.
     * This MUST be wrapped in a database transaction.
     *
     * Steps:
     *   1. INSERT into orders → get order_id
     *   2. INSERT each cart item into order_items (snapshot name + price)
     *   3. UPDATE products.stock_quantity (deduct purchased quantities)
     *   4. DELETE from cart_items for this user
     *
     * @param  int   $userId       The user placing the order.
     * @param  array $cartItems    Cart item rows (with product details joined).
     * @param  float $totalAmount  Pre-calculated order total.
     * @return int                 The new order_id, or 0 on failure.
     */
    public function create(int $userId, array $cartItems, float $totalAmount): int
    {
        // TODO: Wrap all operations in $this->pdo->beginTransaction() / commit() / rollBack()
        return 0;
    }

    /**
     * Update the status of an order (admin action).
     *
     * @param  int    $orderId  The order to update.
     * @param  string $status   New status — must be one of the allowed ENUM values.
     * @return bool             True on success.
     *
     * TODO: Validate $status against the allowed ENUM values before updating.
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        // TODO: Execute UPDATE prepared statement
        return false;
    }
}