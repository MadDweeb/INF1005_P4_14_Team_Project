<?php
/*
 * public/index.php
 *
 * Front controller - single entry point for all HTTP requests.
 *
 * For local development, run with the PHP built-in web server:
 *   php -S localhost:8000 -t public router.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/helpers/auth.php';
require_once __DIR__ . '/../src/helpers/csrf.php';
require_once __DIR__ . '/../src/helpers/sanitize.php';
require_once __DIR__ . '/../src/helpers/url.php';


$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// ── Router ────────────────────────────────────────────────────────────────────

if ($uri === '/' && $method === 'GET') {
    require_once __DIR__ . '/../src/models/Product.php';
    $featuredProducts = [];
    if ($pdo !== null) {
        $productModel = new Product($pdo);
        $featuredProducts = $productModel->getFeatured(5);
    }
    require __DIR__ . '/../views/pages/home.php';

    // ── Auth routes ───────────────────────────────────────────────────────────────

} elseif ($uri === '/login' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->showLogin();

} elseif ($uri === '/login' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->processLogin();

} elseif ($uri === '/register' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->showRegister();

} elseif ($uri === '/register' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->processRegister();

} elseif ($uri === '/logout' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->logout();

    // ── Product routes ────────────────────────────────────────────────────────────

} elseif ($uri === '/products' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->index();

} elseif (preg_match('#^/products/(\d+)$#', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->show((int) $matches[1]);

    // ── Cart routes ───────────────────────────────────────────────────────────────

} elseif ($uri === '/cart' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/CartController.php';
    (new CartController($pdo))->index();

} elseif ($uri === '/cart/add' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/CartController.php';
    (new CartController($pdo))->add();

} elseif ($uri === '/cart/update' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/CartController.php';
    (new CartController($pdo))->update();

} elseif ($uri === '/cart/remove' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/CartController.php';
    (new CartController($pdo))->remove();

} elseif ($uri === '/cart/remove-custom' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/CartController.php';
    (new CartController($pdo))->removeCustom();

    // ── Checkout & Order routes ───────────────────────────────────────────────────

} elseif ($uri === '/checkout' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->showCheckout();

} elseif ($uri === '/checkout/process' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->processCheckout();

    // ── Admin routes ──────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/dashboard' && $method === 'GET') {
    requireAdmin();
    require_once __DIR__ . '/../src/models/Product.php';
    require_once __DIR__ . '/../src/models/Order.php';
    $productCount = 0;
    $orderCount = 0;
    $totalRevenue = 0;
    $pendingOrders = 0;
    if ($pdo !== null) {
        $productModel = new Product($pdo);
        $productResult = $productModel->getAll([], 1, 1);
        $productCount = $productResult['total'] ?? 0;
        $stmt = $pdo->query("SELECT COUNT(*) AS cnt, COALESCE(SUM(total_amount), 0) AS revenue FROM orders");
        $row = $stmt->fetch();
        $orderCount = (int) ($row['cnt'] ?? 0);
        $totalRevenue = (float) ($row['revenue'] ?? 0);
        $stmt2 = $pdo->query("SELECT COUNT(*) AS cnt FROM orders WHERE status = 'pending'");
        $pendingOrders = (int) ($stmt2->fetchColumn() ?: 0);
    }
    require __DIR__ . '/../views/admin/dashboard.php';

} elseif ($uri === '/admin/orders' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->adminIndex();

} elseif (preg_match('#^/admin/orders/(\d+)$#', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->adminOrderDetail((int) $matches[1]);

} elseif ($uri === '/admin/orders/status' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->adminUpdateStatus();

} elseif ($uri === '/admin/products' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->adminIndex();

} elseif ($uri === '/admin/products/create' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->adminCreate();

} elseif ($uri === '/admin/products/update' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->adminUpdate();

} elseif ($uri === '/admin/products/delete' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->adminDelete();

    // ── Static pages ──────────────────────────────────────────────────────────────

} elseif ($uri === '/about' && $method === 'GET') {
    $currentPage = 'about';
    require __DIR__ . '/../views/pages/about.php';

} elseif ($uri === '/contact' && $method === 'GET') {
    $currentPage = 'contact';
    require __DIR__ . '/../views/pages/contact.php';

} elseif ($uri === '/faq' && $method === 'GET') {
    $currentPage = 'faq';
    require __DIR__ . '/../views/pages/faq.php';
} elseif ($uri === '/privacy' && $method === 'GET') {
    $currentPage = '';
    require __DIR__ . '/../views/pages/privacy.php';

} elseif ($uri === '/terms' && $method === 'GET') {
    $currentPage = '';
    require __DIR__ . '/../views/pages/terms.php';

} elseif ($uri === '/contact' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/ContactController.php';
    (new ContactController($pdo))->submit();

} elseif ($uri === '/subscribe' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/SubscriptionController.php';
    (new SubscriptionController($pdo))->subscribe();

} elseif ($uri === '/customizer' && $method === 'GET') {
    require_once __DIR__ . '/../src/models/Product.php';
    $products = [];
    if ($pdo !== null) {
        $productModel = new Product($pdo);
        $result = $productModel->getAll([], 1, 100); // Get all products for customizer
        $products = $result['products'];
    }
    $currentPage = 'customizer';
    require __DIR__ . '/../views/pages/customizer.php';

    // ── Account & Orders routes ───────────────────────────────────────────────────

} elseif ($uri === '/account' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->showAccount();

} elseif ($uri === '/account/update' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->updateAccount();

} elseif ($uri === '/account/password' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/UserController.php';
    (new UserController($pdo))->updatePassword();

} elseif ($uri === '/orders' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->orderHistory();

} elseif (preg_match('#^/orders/(\d+)$#', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->orderDetail((int) $matches[1]);

} elseif ($uri === '/orders/cancel' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->cancelOrder();

} elseif ($uri === '/orders/received' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->markReceived();

    // ── API routes ────────────────────────────────────────────────────────────────

} elseif ($uri === '/api/search' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->apiSearch();

    // ── 404 fallback ──────────────────────────────────────────────────────────────

} else {
    http_response_code(404);
    require __DIR__ . '/../views/pages/404.php';
}