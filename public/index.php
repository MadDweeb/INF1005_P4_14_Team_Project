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

    // ── Checkout & Order routes ───────────────────────────────────────────────────

} elseif ($uri === '/checkout' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->showCheckout();

} elseif ($uri === '/checkout/process' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->processCheckout();

} elseif ($uri === '/orders' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->orderHistory();

} elseif (preg_match('#^/orders/(\d+)$#', $uri, $matches) && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->orderDetail((int) $matches[1]);

} elseif ($uri === '/orders/cancel' && $method === 'POST') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->cancelOrder();

    // ── Admin routes ──────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/orders' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/OrderController.php';
    (new OrderController($pdo))->adminIndex();

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
    // ── API routes ────────────────────────────────────────────────────────────────

} elseif ($uri === '/api/search' && $method === 'GET') {
    require_once __DIR__ . '/../src/controllers/ProductController.php';
    (new ProductController($pdo))->apiSearch();

    // ── 404 fallback ──────────────────────────────────────────────────────────────

} else {
    http_response_code(404);
    require __DIR__ . '/../views/pages/404.php';
}