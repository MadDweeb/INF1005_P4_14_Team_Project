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

        // Include custom builds from session
        $customBuilds = $_SESSION['custom_builds'] ?? [];

        // Redirect if BOTH cart and custom builds are empty
        if (empty($cartItems) && empty($customBuilds)) {
            header('Location: /cart');
            exit;
        }

        $cartTotal   = $this->calculateTotal($cartItems);
        
        // Add custom builds to total
        foreach ($customBuilds as $build) {
            $cartTotal += ($build['price'] * $build['quantity']);
        }
        
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

        error_log('=== CHECKOUT DEBUG START ===');
        error_log('POST data: ' . print_r($_POST, true));
        $user = currentUser();
        error_log('User ID: ' . $user['id']);

        // - Validate shipping fields -
        $errors = [];

        // Combine first + last name
        $name = trim(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? ''));

        // Use existing form fields
        $address = sanitizeString($_POST['address'] ?? '');
        $city = sanitizeString($_POST['city'] ?? '');
        $postal = sanitizeString($_POST['zip'] ?? '');

        if (strlen($name) < 2) {
            $errors['first_name'] = 'Please enter your full name.';
        }

        if (strlen($address) < 5) {
            $errors['address'] = 'Please enter a valid address.';
        }

        if (strlen($city) < 2) {
            $errors['city'] = 'Please enter your city.';
        }

        if (!preg_match('/^\d{6}$/', $postal)) {
            $errors['zip'] = 'Please enter a valid 6-digit postal code.';
        }

        // - Re-fetch cart (never trust the browser to tell us what's in the cart) -
        $cartItems = $this->cartModel
            ? $this->cartModel->getByUser($user['id'])
            : [];

        // Include custom builds from session
        $customBuilds = $_SESSION['custom_builds'] ?? [];

        // Redirect if BOTH are empty
        if (empty($cartItems) && empty($customBuilds)) {
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
        
        // Custom builds always have stock (they're built to order)
        // No stock check needed for custom builds

        // - Re-render checkout with errors if validation failed -
        if (!empty($errors)) {
            $cartTotal = $this->calculateTotal($cartItems);
            // Add custom builds to total
            foreach ($customBuilds as $build) {
                $cartTotal += ($build['price'] * $build['quantity']);
            }
            $currentPage = 'checkout';
            require_once __DIR__ . '/../../views/pages/checkout.php';
            return;
        }

        // - Place the order inside a transaction -
        $cartTotal = $this->calculateTotal($cartItems);
        // Add custom builds to total
        foreach ($customBuilds as $build) {
            $cartTotal += ($build['price'] * $build['quantity']);
        }
        
        $orderId = $this->orderModel
            ? $this->orderModel->create($user['id'], $cartItems, $cartTotal, $name, $address, $city, $postal)
            : 0;

        if ($orderId === 0) {
            // Transaction failed - most likely a last-second stock race condition.
            $errors['stock'] = 'Your order could not be placed. A product may have just sold out. Please review your cart.';
            $currentPage     = 'checkout';
            require_once __DIR__ . '/../../views/pages/checkout.php';
            return;
        }

        // - Clear custom builds from session after successful order -
        unset($_SESSION['custom_builds']);

        // - Success - send the user to their new order's detail page -
        $_SESSION['flash_success'] = 'Order successfully created and updated in your Orders page.';
        header('Location: /orders');
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
     * Cancel a pending order and restore stock (POST /orders/cancel).
     * Only the order's owner may cancel; only 'pending' orders are eligible.
     */
    public function cancelOrder(): void
    {
        requireLogin();
        verifyCsrf();

        $orderId = sanitizeInt($_POST['order_id'] ?? 0);
        if (!isPositiveInt($orderId)) {
            $_SESSION['flash_error'] = 'Invalid order ID.';
            header('Location: /orders');
            exit;
        }

        $user  = currentUser();
        $order = $this->orderModel ? $this->orderModel->getById($orderId) : false;

        if (!$order || (int) $order['user_id'] !== $user['id']) {
            $_SESSION['flash_error'] = 'Order not found or access denied.';
            header('Location: /orders');
            exit;
        }

        $result = $this->orderModel
            ? $this->orderModel->cancelWithRestock($orderId, ['pending'])
            : ['ok' => false, 'reason' => 'no_model'];

        if ($result['ok']) {
            $_SESSION['flash_success'] = 'Order #' . str_pad((string) $orderId, 5, '0', STR_PAD_LEFT) . ' has been cancelled and stock restored.';
        } else {
            $_SESSION['flash_error'] = match ($result['reason']) {
                'invalid_status'   => 'This order can no longer be cancelled (it may already be in progress).',
                'already_cancelled' => 'This order has already been cancelled.',
                default            => 'Could not cancel the order. Please try again.',
            };
        }

        header('Location: /orders');
        exit;
    }

    /**
     * Customer: mark a delivered order as received/completed (POST /orders/received).
     * Locks the order to 'completed' - no further status changes by anyone.
     */
    public function markReceived(): void
    {
        requireLogin();
        verifyCsrf();

        $orderId = sanitizeInt($_POST['order_id'] ?? 0);
        if (!isPositiveInt($orderId)) {
            $_SESSION['flash_error'] = 'Invalid order ID.';
            header('Location: /orders');
            exit;
        }

        $user   = currentUser();
        $result = $this->orderModel
            ? $this->orderModel->markAsReceived($orderId, $user['id'])
            : ['ok' => false, 'reason' => 'no_model'];

        if ($result['ok']) {
            $_SESSION['flash_success'] = 'Order #' . str_pad((string) $orderId, 5, '0', STR_PAD_LEFT) . ' marked as received. Thank you!';
        } else {
            $_SESSION['flash_error'] = match ($result['reason']) {
                'invalid_status' => 'This order is not in a delivered state.',
                'forbidden'      => 'You do not have permission to update this order.',
                default          => 'Could not update the order. Please try again.',
            };
        }

        header('Location: /orders');
        exit;
    }

    /**
     * User: remove a cancelled or completed order from their history (POST /orders/delete).
     */
    public function userDeleteOrder(): void
    {
        requireLogin();
        verifyCsrf();

        $orderId = sanitizeInt($_POST['order_id'] ?? 0);
        if (!isPositiveInt($orderId)) {
            $_SESSION['flash_error'] = 'Invalid order ID.';
            header('Location: /orders');
            exit;
        }

        $user   = currentUser();
        $result = $this->orderModel
            ? $this->orderModel->deleteFinished($orderId, $user['id'])
            : ['ok' => false, 'reason' => 'no_model'];

        if ($result['ok']) {
            $_SESSION['flash_success'] = 'Order #' . str_pad((string) $orderId, 5, '0', STR_PAD_LEFT) . ' has been removed from your history.';
        } else {
            $_SESSION['flash_error'] = $result['reason'] === 'not_eligible'
                ? 'Only cancelled or completed orders can be removed.'
                : 'Could not remove the order. Please try again.';
        }

        header('Location: /orders');
        exit;
    }

    /**
     * Admin: delete a cancelled or completed order (POST /admin/orders/delete).
     */
    public function adminDeleteOrder(): void
    {
        requireAdmin();
        verifyCsrf();

        $orderId  = sanitizeInt($_POST['order_id'] ?? 0);
        $redirect = sanitizeString($_POST['redirect'] ?? '/admin/orders');

        if (!str_starts_with($redirect, '/admin/')) {
            $redirect = '/admin/orders';
        }

        if (!isPositiveInt($orderId)) {
            $_SESSION['flash_error'] = 'Invalid order ID.';
            header('Location: ' . $redirect);
            exit;
        }

        $result = $this->orderModel
            ? $this->orderModel->deleteFinished($orderId)
            : ['ok' => false, 'reason' => 'no_model'];

        if ($result['ok']) {
            $_SESSION['flash_success'] = 'Order #' . str_pad((string) $orderId, 5, '0', STR_PAD_LEFT) . ' has been deleted.';
            header('Location: /admin/orders');
        } else {
            $_SESSION['flash_error'] = $result['reason'] === 'not_eligible'
                ? 'Only cancelled or completed orders can be deleted.'
                : 'Could not delete the order. Please try again.';
            header('Location: ' . $redirect);
        }
        exit;
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
     * Admin: view a single order's detail page (GET /admin/orders/{id}).
     */
    public function adminOrderDetail(int $id): void
    {
        requireAdmin();

        if (!isPositiveInt($id)) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $order = $this->orderModel ? $this->orderModel->getById($id) : false;

        if (!$order) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $currentAdminPage = 'orders';
        require_once __DIR__ . '/../../views/admin/order-detail.php';
    }

    /**
     * Admin: update an order's status (POST /admin/orders/status).
     *
     * Expects $_POST: order_id, status, redirect (optional - defaults to /admin/orders)
     */
    public function adminUpdateStatus(): void
    {
        requireAdmin();
        verifyCsrf();

        $orderId  = sanitizeInt($_POST['order_id'] ?? 0);
        $status   = sanitizeString($_POST['status'] ?? '');
        $redirect = sanitizeString($_POST['redirect'] ?? '/admin/orders');

        // Only allow redirects within the admin area.
        if (!str_starts_with($redirect, '/admin/')) {
            $redirect = '/admin/orders';
        }

        if (!isPositiveInt($orderId)) {
            http_response_code(400);
            echo 'Invalid order ID.';
            return;
        }

        // Block any edits to orders the customer has already confirmed as received.
        $order = $this->orderModel ? $this->orderModel->getById($orderId) : false;
        if ($order && $order['status'] === 'completed') {
            $_SESSION['flash_error'] = 'This order has been confirmed as received and cannot be changed.';
            header('Location: ' . $redirect);
            exit;
        }

        // When cancelling, use cancelWithRestock() so inventory is restored.
        if ($status === 'cancelled') {
            $result = $this->orderModel
                ? $this->orderModel->cancelWithRestock($orderId, null)
                : ['ok' => false, 'reason' => 'no_model'];

            if ($result['ok']) {
                $_SESSION['flash_success'] = 'Order cancelled and stock restored.';
            } else {
                $_SESSION['flash_error'] = match ($result['reason']) {
                    'already_cancelled' => 'This order is already cancelled.',
                    default             => 'Could not cancel the order. Please try again.',
                };
            }
        } else {
            $updated = $this->orderModel
                ? $this->orderModel->updateStatus($orderId, $status)
                : false;

            if ($updated) {
                $_SESSION['flash_success'] = 'Order status updated to ' . ucfirst($status) . '.';
            } else {
                $_SESSION['flash_error'] = 'Invalid status value.';
            }
        }

        header('Location: ' . $redirect);
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

    /**
     * Show the orders history page (GET /orders).
     */
    public function index(): void
    {
        requireLogin();
        $user = currentUser();
        
        // TODO: Get real orders from database
        $orders = [];
        
        $currentPage = 'orders';
        require_once __DIR__ . '/../../views/pages/orders.php';
    }
}