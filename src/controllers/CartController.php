<?php
/**
 * src/controllers/CartController.php
 *
 * Handles shopping cart operations.
 *
 * Routed from public/index.php:
 *   GET  /cart         → index()   — show cart contents
 *   POST /cart/add     → add()     — add an item
 *   POST /cart/update  → update()  — update item quantity
 *   POST /cart/remove  → remove()  — remove an item
 *
 * NOTE: $pdo may be null if the database is not yet configured.
 *       All methods render empty/placeholder states when DB is unavailable.
 *
 * All POST actions require a valid CSRF token.
 * All cart operations require the user to be logged in.
 *
 * TODO: If you want a guest cart (session-based), implement session cart logic
 *       here and merge it into the DB cart on login.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class CartController
{
    private ?CartItem $cartModel;

    public function __construct(?PDO $pdo)
    {
        $this->cartModel = $pdo ? new CartItem($pdo) : null;
    }

    /**
     * Show the cart page (GET /cart).
     * Loads all cart items for the current user with product details.
     */
    public function index(): void
    {
        /*
         * TODO:
         * - requireLogin()
         * - $user = currentUser()
         * - $cartItems = $this->cartModel ? $this->cartModel->getByUser($user['id']) : []
         * - Calculate $cartTotal from $cartItems (price * quantity)
         * - require_once views/pages/cart.php
         */
    }

    /**
     * Add an item to the cart (POST /cart/add).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Sanitize product_id and quantity from $_POST.
     * TODO: 3. Validate: product exists, quantity > 0, stock available.
     * TODO: 4. $this->cartModel->addOrUpdate($userId, $productId, $quantity)
     * TODO: 5. Redirect to /cart or back to the product page with a success message.
     */
    public function add(): void
    {
        /*
         * TODO: Implement add-to-cart logic (see docblock above).
         */
    }

    /**
     * Update an item's quantity (POST /cart/update).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Sanitize cart_item_id and quantity from $_POST.
     * TODO: 3. $this->cartModel->updateQuantity($userId, $cartItemId, $quantity)
     * TODO: 4. Redirect back to /cart.
     */
    public function update(): void
    {
        /*
         * TODO: Implement update logic (see docblock above).
         */
    }

    /**
     * Remove an item from the cart (POST /cart/remove).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Sanitize cart_item_id from $_POST.
     * TODO: 3. $this->cartModel->remove($userId, $cartItemId)
     * TODO: 4. Redirect back to /cart.
     */
    public function remove(): void
    {
        /*
         * TODO: Implement remove logic (see docblock above).
         */
    }
}