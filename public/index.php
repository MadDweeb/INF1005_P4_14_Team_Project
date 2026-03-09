<?php
/*
 * public/index.php
 *
 * Front controller — single entry point for all HTTP requests.
 *
 * For local development, run with the PHP built-in web server:
 *   php -S localhost:8000 -t public router.php
 *
 * Currently only the homepage (GET /) is active.
 * All other routes return 404 until they are implemented.
 *
 * TODO: Require config/database.php and helpers once they are implemented.
 * TODO: Add routes for products, auth, cart, checkout, admin, etc.
 */
declare(strict_types=1);

$uri    = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// ── Router ────────────────────────────────────────────────────────────────────

if ($uri === '/' && $method === 'GET') {
    /*
     * Homepage — the only active route in this scaffold.
     * TODO: Load featured products from the database and pass to the view.
     */
    require __DIR__ . '/../views/pages/home.php';

} else {
    /*
     * All other routes return 404 until implemented.
     * TODO: Add routes here as you build each feature, for example:
     *   GET  /products         -> ProductController->index()
     *   GET  /products/{id}    -> ProductController->show($id)
     *   GET  /login            -> UserController->showLogin()
     *   POST /login            -> UserController->processLogin()
     *   GET  /register         -> UserController->showRegister()
     *   POST /register         -> UserController->processRegister()
     *   GET  /logout           -> UserController->logout()
     *   GET  /cart             -> CartController->index()
     *   POST /cart/add         -> CartController->add()
     *   POST /cart/update      -> CartController->update()
     *   POST /cart/remove      -> CartController->remove()
     *   GET  /checkout         -> OrderController->showCheckout()
     *   POST /checkout/process -> OrderController->processCheckout()
     *   GET  /admin/products   -> ProductController->adminIndex()
     */
    http_response_code(404);
    require __DIR__ . '/../views/pages/404.php';
}
