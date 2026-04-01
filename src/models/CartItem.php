<?php
/**
 * src/models/CartItem.php
 *
 * CartItem model - a product line in a user's shopping cart.
 *
 * Cart data is stored in the database for logged-in users so it persists
 * across sessions and devices.
 *
 * Attributes:
 *   cart_item_id - Auto-increment primary key
 *   user_id      - FK → users.user_id
 *   product_id   - FK → products.product_id
 *   quantity     - Number of units in the cart
 *   added_at     - When this item was first added
 *
 * UNIQUE KEY on (user_id, product_id) prevents duplicate rows.
 * addOrUpdate() uses INSERT ... ON DUPLICATE KEY UPDATE to increment
 * quantity atomically instead of doing a SELECT then INSERT.
 */

class CartItem
{
    private PDO    $pdo;
    private string $table = 'cart_items';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all cart items for a user, with product details joined.
     *
     * We JOIN with products here so the view gets everything it needs in
     * one query - no second lookup per item needed.
     *
     * @param  int   $userId  The logged-in user's ID.
     * @return array          Rows with cart + product columns merged.
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
                ci.cart_item_id,
                ci.quantity,
                ci.added_at,
                p.product_id,
                p.name,
                p.manufacturer,
                p.price,
                p.product_image,
                p.stock_quantity
             FROM {$this->table} ci
             JOIN products p ON p.product_id = ci.product_id
             WHERE ci.user_id = :user_id
             ORDER BY ci.added_at DESC"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Add a product to the cart, or increment its quantity if already present.
     *
     * INSERT ... ON DUPLICATE KEY UPDATE is a single atomic statement -
     * it avoids the race condition that would occur with a separate SELECT then INSERT.
     * The UNIQUE KEY on (user_id, product_id) in the schema makes this work.
     *
     * Stock validation happens in CartController::add() before this is called.
     *
     * @param  int  $userId     The user's ID.
     * @param  int  $productId  The product to add.
     * @param  int  $quantity   Units to add (default 1).
     * @return bool             True on success.
     */
    public function addOrUpdate(int $userId, int $productId, int $quantity = 1): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (user_id, product_id, quantity)
             VALUES (:user_id, :product_id, :qty_insert)
             ON DUPLICATE KEY UPDATE quantity = quantity + :qty_increment"
        );
        return $stmt->execute([
            ':user_id'    => $userId,
            ':product_id' => $productId,
            ':qty_insert' => $quantity,
            ':qty_increment' => $quantity,
        ]);
    }

    /**
     * Set the quantity of a specific cart item to a new absolute value.
     * If quantity is 0 or less, the item is removed entirely.
     *
     * user_id is always included in the WHERE clause so one user can never
     * modify another user's cart items - even if they guess a cart_item_id.
     *
     * @param  int  $userId      The user's ID (ownership check).
     * @param  int  $cartItemId  The cart_item_id to update.
     * @param  int  $quantity    New desired quantity.
     * @return bool              True on success.
     */
    public function updateQuantity(int $userId, int $cartItemId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->remove($userId, $cartItemId);
        }

        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table}
             SET quantity = :qty
             WHERE cart_item_id = :cart_item_id
               AND user_id = :user_id"
        );
        $ok = $stmt->execute([
            ':qty'          => $quantity,
            ':cart_item_id' => $cartItemId,
            ':user_id'      => $userId,
        ]);

        if (!$ok) {
            return false;
        }

        if ($stmt->rowCount() > 0) {
            return true;
        }

        // MySQL may report 0 affected rows when setting the same value.
        $check = $this->pdo->prepare(
            "SELECT quantity
             FROM {$this->table}
             WHERE cart_item_id = :cart_item_id
               AND user_id = :user_id
             LIMIT 1"
        );
        $check->execute([
            ':cart_item_id' => $cartItemId,
            ':user_id'      => $userId,
        ]);
        $current = $check->fetchColumn();

        if ($current === false) {
            return false;
        }

        return ((int) $current) === $quantity;
    }

    /**
     * Remove a single item from the cart.
     *
     * user_id is in the WHERE clause to prevent one user from deleting
     * another user's cart items.
     *
     * @param  int  $userId      The user's ID (ownership check).
     * @param  int  $cartItemId  The cart_item_id to delete.
     * @return bool              True on success.
     */
    public function remove(int $userId, int $cartItemId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM {$this->table}
             WHERE cart_item_id = :cart_item_id
               AND user_id = :user_id"
        );
        return $stmt->execute([
            ':cart_item_id' => $cartItemId,
            ':user_id'      => $userId,
        ]);
    }

    /**
     * Clear every item in a user's cart.
     * Called by Order::create() inside its transaction after a successful checkout.
     *
     * @param  int  $userId  The user's ID.
     * @return bool          True on success.
     */
    public function clearCart(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM {$this->table} WHERE user_id = :user_id"
        );
        return $stmt->execute([':user_id' => $userId]);
    }
}