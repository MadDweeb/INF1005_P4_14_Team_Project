<?php
/**
 * src/models/CartItem.php
 *
 * CartItem model — a product line in a user's shopping cart.
 *
 * Cart data is stored in the database for logged-in users so it persists
 * across sessions and devices. Guest cart (session-based) is not implemented
 * here — see a TODO below if you want to add that.
 *
 * Attributes:
 *   cart_item_id — Auto-increment primary key
 *   user_id      — FK → users.user_id
 *   product_id   — FK → products.product_id
 *   quantity     — Number of units in the cart
 *   added_at     — When this item was first added
 *
 * UNIQUE KEY on (user_id, product_id) prevents duplicate rows.
 * Use INSERT ... ON DUPLICATE KEY UPDATE to increment quantity instead.
 *
 * TODO (optional): Add a session-based guest cart that merges into the
 *                  DB cart when the user logs in.
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
     * @param  int   $userId  The logged-in user's ID.
     * @return array          Cart items with product name, price, image, etc.
     */
    public function getByUser(int $userId): array
    {
        // TODO: JOIN cart_items with products to get name, price, product_image
        // Example columns to SELECT:
        //   ci.cart_item_id, ci.quantity, ci.added_at,
        //   p.product_id, p.name, p.price, p.product_image, p.stock_quantity
        return [];
    }

    /**
     * Add a product to the cart, or increase its quantity if already present.
     * Uses INSERT ... ON DUPLICATE KEY UPDATE for an atomic upsert.
     *
     * @param  int  $userId     The user's ID.
     * @param  int  $productId  The product to add.
     * @param  int  $quantity   Number of units to add (default 1).
     * @return bool             True on success.
     *
     * TODO: Check stock_quantity in the products table before adding —
     *       prevent adding more units than are in stock.
     */
    public function addOrUpdate(int $userId, int $productId, int $quantity = 1): bool
    {
        // TODO: INSERT ... ON DUPLICATE KEY UPDATE quantity = quantity + :qty
        return false;
    }

    /**
     * Update the quantity of a specific cart item.
     * If quantity becomes 0, the item should be removed.
     *
     * @param  int  $userId      The user's ID (for ownership verification).
     * @param  int  $cartItemId  The cart_item_id to update.
     * @param  int  $quantity    New quantity.
     * @return bool              True on success.
     */
    public function updateQuantity(int $userId, int $cartItemId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->remove($userId, $cartItemId);
        }
        // TODO: UPDATE cart_items SET quantity = :qty WHERE cart_item_id = :id AND user_id = :uid
        return false;
    }

    /**
     * Remove a single item from the cart.
     * Always include user_id in the WHERE clause to prevent one user
     * from deleting another user's cart items.
     *
     * @param  int  $userId      The user's ID.
     * @param  int  $cartItemId  The cart_item_id to delete.
     * @return bool              True on success.
     */
    public function remove(int $userId, int $cartItemId): bool
    {
        // TODO: DELETE WHERE cart_item_id = :id AND user_id = :uid
        return false;
    }

    /**
     * Clear the entire cart for a user.
     * Called after a successful order is placed.
     *
     * @param  int  $userId  The user's ID.
     * @return bool          True on success.
     */
    public function clearCart(int $userId): bool
    {
        // TODO: DELETE FROM cart_items WHERE user_id = :uid
        return false;
    }
}