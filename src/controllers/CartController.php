<?php
/**
 * src/controllers/CartController.php
 *
 * Handles shopping cart operations.
 *
 * Routed from public/index.php:
 *   GET  /cart         → index()   - show cart contents
 *   POST /cart/add     → add()     - add an item
 *   POST /cart/update  → update()  - update item quantity
 *   POST /cart/remove  → remove()  - remove an item
 *
 * All POST actions require a valid CSRF token.
 * All cart operations require the user to be logged in.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class CartController
{
    private ?CartItem $cartModel;
    private ?Product $productModel;

    public function __construct(?PDO $pdo)
    {
        $this->cartModel = $pdo ? new CartItem($pdo) : null;
        $this->productModel = $pdo ? new Product($pdo) : null;
    }

    /**
     * Show the cart page (GET /cart).
     * Loads all cart items for the current user with product details joined.
     */
    public function index(): void
    {
        requireLogin();

        $user = currentUser();
        $cartItems = $this->cartModel
            ? $this->cartModel->getByUser($user['id'])
            : [];

        // Calculate the running total from the joined price * quantity columns.
        $cartTotal = array_reduce($cartItems, function (float $carry, array $item): float {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);

        $currentPage = 'cart';
        require_once __DIR__ . '/../../views/pages/cart.php';
    }

    /**
     * Add an item to the cart (POST /cart/add).
     *
     * Expects $_POST:
     *   product_id - ID of the product to add
     *   quantity   - number of units (defaults to 1)
     *   redirect   - optional URL to send the user back to (e.g. the product page)
     */
    public function add(): void
    {
        requireLogin();
        verifyCsrf();

        $productId = sanitizeInt($_POST['product_id'] ?? 0);
        $quantity = sanitizeInt($_POST['quantity'] ?? 1);

        // Basic validation - reject nonsensical values immediately.
        if (!isPositiveInt($productId) || $quantity < 1) {
            $_SESSION['flash_error'] = 'Invalid product or quantity.';
            header('Location: /products');
            exit;
        }

        // Verify the product actually exists and has sufficient stock.
        $product = $this->productModel
            ? $this->productModel->getById($productId)
            : false;

        if (!$product) {
            $_SESSION['flash_error'] = 'Product not found.';
            header('Location: /products');
            exit;
        }

        if ($product['stock_quantity'] < $quantity) {
            $_SESSION['flash_error'] = 'Not enough stock available.';
            // Send the user back to where they came from (product page or cart).
            $redirect = sanitizeString($_POST['redirect'] ?? '/products/' . $productId);
            header('Location: ' . $redirect);
            exit;
        }

        $user = currentUser();
        $this->cartModel->addOrUpdate($user['id'], $productId, $quantity);

        $_SESSION['flash_success'] = 'Item added to cart.';

        // Redirect back to wherever the add-to-cart form was submitted from.
        $redirect = sanitizeString($_POST['redirect'] ?? '/cart');
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Update an item's quantity (POST /cart/update).
     *
     * Expects $_POST:
     *   cart_item_id - the cart row to update
     *   quantity     - new quantity (0 removes the item)
     */
    public function update(): void
    {
        requireLogin();
        verifyCsrf();

        $cartItemId = sanitizeInt($_POST['cart_item_id'] ?? 0);
        $quantity = sanitizeInt($_POST['quantity'] ?? 0);

        if (!isPositiveInt($cartItemId)) {
            $_SESSION['flash_error'] = 'Invalid cart item.';
            header('Location: /cart');
            exit;
        }

        $user = currentUser();

        // updateQuantity() handles quantity <= 0 by calling remove() internally.
        $this->cartModel->updateQuantity($user['id'], $cartItemId, $quantity);

        header('Location: /cart');
        exit;
    }

    /**
     * Remove an item from the cart (POST /cart/remove).
     *
     * Expects $_POST:
     *   cart_item_id - the cart row to delete
     */
    public function remove(): void
    {
        requireLogin();
        verifyCsrf();

        $cartItemId = sanitizeInt($_POST['cart_item_id'] ?? 0);

        if (!isPositiveInt($cartItemId)) {
            $_SESSION['flash_error'] = 'Invalid cart item.';
            header('Location: /cart');
            exit;
        }

        $user = currentUser();

        // user_id is passed so the model's WHERE clause prevents cross-user deletion.
        $this->cartModel->remove($user['id'], $cartItemId);

        header('Location: /cart');
        exit;
    }
}