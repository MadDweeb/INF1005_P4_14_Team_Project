<?php
/**
 * src/controllers/OrderController.php
 *
 * Handles order checkout and order history.
 *
 * Routed from public/index.php:
 *   GET  /checkout               → showCheckout()    (login required)
 *   POST /checkout/process       → processCheckout() (login required)
 *   GET  /orders                 → orderHistory()    (login required)
 *   GET  /orders/{id}            → orderDetail($id)  (login required)
 *   POST /admin/orders/status    → adminUpdateStatus()  (admin required)
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
     * Redirects to /cart if the cart is empty - nothing to check out.
     */
    public function showCheckout(): void
    {
        requireLogin();

        $user      = currentUser();
        $cartItems = $this->cartModel
            ? $this->cartModel->getByUser($user['id'])
            : [];

        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }

        $cartTotal   = $this->calculateTotal($cartItems);
        $currentPage = 'checkout';
        require_once __DIR__ . '/../../views/pages/checkout.php';
    }

    /**
     * Process the checkout form submission (POST /checkout/process).
     *
     * Validates the shipping form, re-verifies stock, then hands off to
     * Order::create() which runs the full DB transaction atomically.
     * On success, redirects to the new order's detail page.
     */
    public function processCheckout(): void
    {
        requireLogin();
        verifyCsrf();

        $user = currentUser();

        // - Validate shipping fields -
        $errors = [];

        $name    = sanitizeString($_POST['shipping_name']    ?? '');
        $address = sanitizeString($_POST['shipping_address'] ?? '');
        $city    = sanitizeString($_POST['shipping_city']    ?? '');
        $postal  = sanitizeString($_POST['shipping_postal']  ?? '');

        if (strlen($name)    < 2)  $errors['shipping_name']    = 'Please enter your full name.';
        if (strlen($address) < 5)  $errors['shipping_address'] = 'Please enter a valid address.';
        if (strlen($city)    < 2)  $errors['shipping_city']    = 'Please enter your city.';
        if (!preg_match('/^\d{6}$/', $postal)) {
            $errors['shipping_postal'] = 'Please enter a valid 6-digit postal code.';
        }

        // - Re-fetch cart (never trust the browser to tell us what's in the cart) -
        $cartItems = $this->cartModel
            ? $this->cartModel->getByUser($user['id'])
            : [];

        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }

        // - Re-verify stock for every item before charging -
        foreach ($cartItems as $item) {
            if ($item['stock_quantity'] < $item['quantity']) {
                $errors['stock'] = "Sorry, \"{$item['name']}\" no longer has enough stock. Please update your cart.";
                break;
            }
        }

        // - Re-render checkout with errors if validation failed -
        if (!empty($errors)) {
            $cartTotal   = $this->calculateTotal($cartItems);
            $currentPage = 'checkout';
            require_once __DIR__ . '/../../views/pages/checkout.php';
            return;
        }

        // - Place the order inside a transaction -
        $cartTotal = $this->calculateTotal($cartItems);
        $orderId   = $this->orderModel
            ? $this->orderModel->create($user['id'], $cartItems, $cartTotal)
            : 0;

        if ($orderId === 0) {
            // Transaction failed - most likely a last-second stock race condition.
            $errors['stock'] = 'Your order could not be placed. A product may have just sold out. Please review your cart.';
            $currentPage     = 'checkout';
            require_once __DIR__ . '/../../views/pages/checkout.php';
            return;
        }

        // - Success - send the user to their new order's detail page -
        $_SESSION['flash_success'] = 'Order placed successfully!';
        header('Location: /orders/' . $orderId);
        exit;
    }

    /**
     * Show order history for the logged-in user (GET /orders).
     */
    public function orderHistory(): void
    {
        requireLogin();

        $user    = currentUser();
        $orders  = $this->orderModel
            ? $this->orderModel->getByUser($user['id'])
            : [];

        $currentPage = 'orders';
        require_once __DIR__ . '/../../views/pages/orders.php';
    }

    /**
     * Show a single order's detail page (GET /orders/{id}).
     *
     * Returns 403 if the order belongs to a different user - users must
     * not be able to view each other's orders by guessing an order_id.
     */
    public function orderDetail(int $id): void
    {
        requireLogin();

        if (!isPositiveInt($id)) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $user  = currentUser();
        $order = $this->orderModel
            ? $this->orderModel->getById($id)
            : false;

        // 404 if not found; 403 if it belongs to someone else.
        if (!$order) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        if ((int) $order['user_id'] !== $user['id']) {
            http_response_code(403);
            echo 'You do not have permission to view this order.';
            return;
        }

        $currentPage = 'orders';
        require_once __DIR__ . '/../../views/pages/order-detail.php';
    }

    /**
     * Admin: show all orders (GET /admin/orders).
     */
    public function adminIndex(): void
    {
        requireAdmin();

        $orders      = $this->orderModel ? $this->orderModel->getAll() : [];
        $currentPage = 'admin';
        require_once __DIR__ . '/../../views/admin/orders.php';
    }

    /**
     * Admin: update an order's status (POST /admin/orders/status).
     *
     * Expects $_POST: order_id, status
     */
    public function adminUpdateStatus(): void
    {
        requireAdmin();
        verifyCsrf();

        $orderId = sanitizeInt($_POST['order_id'] ?? 0);
        $status  = sanitizeString($_POST['status'] ?? '');

        if (!isPositiveInt($orderId)) {
            http_response_code(400);
            echo 'Invalid order ID.';
            return;
        }

        $updated = $this->orderModel
            ? $this->orderModel->updateStatus($orderId, $status)
            : false;

        if (!$updated) {
            $_SESSION['flash_error'] = 'Invalid status value.';
        }

        header('Location: /admin/orders');
        exit;
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    /**
     * Sum price × quantity across all cart items.
     *
     * @param  array  $cartItems  Rows from CartItem::getByUser().
     * @return float              Total in SGD.
     */
    private function calculateTotal(array $cartItems): float
    {
        return array_reduce($cartItems, function (float $carry, array $item): float {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);
    }
}