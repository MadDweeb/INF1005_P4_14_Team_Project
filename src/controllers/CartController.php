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

        // Add custom builds from session
        $customBuilds = $_SESSION['custom_builds'] ?? [];

        // Calculate the running total from the joined price * quantity columns.
        $cartTotal = array_reduce($cartItems, function (float $carry, array $item): float {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);

        // Add custom builds to total
        foreach ($customBuilds as $build) {
            $cartTotal += ($build['price'] * $build['quantity']);
        }

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

        // Handle custom build (product_id = 0)
        if ($productId === 0 && isset($_POST['custom_build'])) {
            $this->addCustomBuild();
            return;
        }

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
        if (!$this->cartModel) {
            $_SESSION['flash_error'] = 'Cart service is currently unavailable. Please try again shortly.';
            $redirect = sanitizeString($_POST['redirect'] ?? '/products/' . $productId);
            header('Location: ' . $redirect);
            exit;
        }

        try {
            $added = $this->cartModel->addOrUpdate($user['id'], $productId, $quantity);
        } catch (\Throwable $e) {
            error_log('Cart add failed: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Unable to add this item to cart right now. Please try again.';
            $redirect = sanitizeString($_POST['redirect'] ?? '/products/' . $productId);
            header('Location: ' . $redirect);
            exit;
        }

        if (!$added) {
            $_SESSION['flash_error'] = 'Unable to add this item to cart right now. Please try again.';
            $redirect = sanitizeString($_POST['redirect'] ?? '/products/' . $productId);
            header('Location: ' . $redirect);
            exit;
        }

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

        if ($quantity < 1) {
            $_SESSION['flash_error'] = 'Quantity must be at least 1.';
            header('Location: /cart');
            exit;
        }

        $user = currentUser();

        if (!$this->cartModel) {
            $_SESSION['flash_error'] = 'Cart service is currently unavailable. Please try again shortly.';
            header('Location: /cart');
            exit;
        }

        // updateQuantity() handles quantity <= 0 by calling remove() internally.
        try {
            $updated = $this->cartModel->updateQuantity($user['id'], $cartItemId, $quantity);
        } catch (\Throwable $e) {
            error_log('Cart update failed: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Unable to update quantity right now. Please try again.';
            header('Location: /cart');
            exit;
        }

        if (!$updated) {
            $_SESSION['flash_error'] = 'Could not update this cart item. Please refresh and try again.';
            header('Location: /cart');
            exit;
        }

        $_SESSION['flash_success'] = 'Cart quantity updated.';

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

    /**
     * Handle custom switch builds from the customizer.
     * Stores the build in session as a special cart item.
     */
    private function addCustomBuild(): void
    {
        $customData = json_decode($_POST['custom_build'] ?? '{}', true);
        $quantity = sanitizeInt($_POST['quantity'] ?? 10);
        $customPrice = floatval($_POST['custom_price'] ?? 0);

        if (empty($customData) || $customPrice <= 0) {
            $_SESSION['flash_error'] = 'Invalid custom build data.';
            header('Location: /customizer');
            exit;
        }

        // Store custom build in session (not in database since it's a custom configuration)
        if (!isset($_SESSION['custom_builds'])) {
            $_SESSION['custom_builds'] = [];
        }

        // Create a description
        $description = sprintf(
            'Custom Switch: %s top, %s stem, %s spring, %s bottom',
            $customData['top_housing'] ?? '',
            $customData['stem'] ?? '',
            $customData['spring'] ?? '',
            $customData['bottom_housing'] ?? ''
        );

        // Add to custom builds array
        $_SESSION['custom_builds'][] = [
            'name' => 'Custom Built Switch',
            'description' => $description,
            'price' => $customPrice,
            'quantity' => $quantity,
            'build_data' => $customData,
            'product_image' => 'custom_switch.webp' // Generic custom switch image
        ];

        $_SESSION['flash_success'] = 'Custom switch added to cart!';
        
        $redirect = sanitizeString($_POST['redirect'] ?? '/cart');
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Remove a custom build from the session cart.
     */
    public function removeCustom(): void
    {
        requireLogin();
        verifyCsrf();

        $customIndex = sanitizeInt($_POST['custom_index'] ?? -1);

        if (!isset($_SESSION['custom_builds'][$customIndex])) {
            $_SESSION['flash_error'] = 'Custom build not found.';
            header('Location: /cart');
            exit;
        }

        // Remove the custom build
        array_splice($_SESSION['custom_builds'], $customIndex, 1);

        $_SESSION['flash_success'] = 'Custom build removed from cart.';
        header('Location: /cart');
        exit;
    }
}