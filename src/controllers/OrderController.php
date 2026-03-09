<?php
/**
 * src/controllers/OrderController.php
 *
 * Handles order checkout and order history.
 *
 * Routed from public/index.php:
 *   GET  /checkout          → showCheckout()    (login required)
 *   POST /checkout/process  → processCheckout() (login required)
 *   GET  /orders            → orderHistory()    (login required)
 *   GET  /orders/{id}       → orderDetail($id)  (login required)
 *
 * NOTE: $pdo may be null if the database is not yet configured.
 *       All methods render empty/placeholder states when DB is unavailable.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class OrderController
{
    private ?Order    $orderModel;
    private ?CartItem $cartModel;

    public function __construct(?PDO $pdo)
    {
        $this->orderModel = $pdo ? new Order($pdo)    : null;
        $this->cartModel  = $pdo ? new CartItem($pdo) : null;
    }

    /**
     * Show the checkout page (GET /checkout).
     * Loads cart items and displays the shipping/billing form.
     *
     * TODO: Redirect to /cart if the cart is empty.
     */
    public function showCheckout(): void
    {
        /*
         * TODO:
         * - requireLogin()
         * - $user = currentUser()
         * - $cartItems = $this->cartModel ? $this->cartModel->getByUser($user['id']) : []
         * - Redirect to /cart if $cartItems is empty
         * - Calculate $cartTotal from $cartItems (price * quantity)
         * - require_once views/pages/checkout.php
         */
    }

    /**
     * Process the checkout form submission (POST /checkout/process).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Validate shipping/billing fields.
     * TODO: 3. Re-verify cart is not empty and all items are in stock.
     * TODO: 4. $this->orderModel->create($userId, $cartItems, $total) in a transaction.
     * TODO: 5. Redirect to order confirmation page.
     */
    public function processCheckout(): void
    {
        /*
         * TODO: Implement checkout processing logic (see docblock above).
         */
    }

    /**
     * Show order history for the logged-in user (GET /orders).
     * TODO: Create views/pages/orders.php.
     */
    public function orderHistory(): void
    {
        /*
         * TODO:
         * - requireLogin()
         * - $user = currentUser()
         * - $orders = $this->orderModel ? $this->orderModel->getByUser($user['id']) : []
         * - require_once views/pages/orders.php
         */
    }

    /**
     * Show a single order's detail page (GET /orders/{id}).
     * TODO: Create views/pages/order-detail.php.
     * TODO: Verify the order belongs to the current user before displaying.
     */
    public function orderDetail(int $id): void
    {
        /*
         * TODO:
         * - requireLogin()
         * - $user = currentUser()
         * - $order = $this->orderModel ? $this->orderModel->getById($id) : false
         * - Return 403 if $order is false or order's user_id !== $user['id']
         * - require_once views/pages/order-detail.php
         */
    }
}