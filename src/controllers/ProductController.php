<?php
/**
 * src/controllers/ProductController.php
 *
 * Handles all HTTP requests related to products.
 *
 * Routed from public/index.php:
 *   GET  /products              → index()        - catalogue with filters
 *   GET  /products/{id}         → show($id)      - single product detail
 *   GET  /admin/products        → adminIndex()   - admin product list
 *   POST /admin/products/create → adminCreate()
 *   POST /admin/products/update → adminUpdate()
 *   POST /admin/products/delete → adminDelete()
 *
 * NOTE: $pdo may be null if the database is not yet configured.
 *       All methods handle this gracefully by rendering empty/placeholder states.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class ProductController
{
    private ?Product $productModel;

    // Allowed image MIME types for product photo uploads.
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2 MB
    private const IMAGE_UPLOAD_DIR = __DIR__ . '/../../public/assets/images/';

    public function __construct(?PDO $pdo)
    {
        $this->productModel = $pdo ? new Product($pdo) : null;
    }

    // ─── Public catalogue ──────────────────────────────────────────────────────

    /**
     * Display the product catalogue with filters and pagination (GET /products).
     *
     * Reads from $_GET:
     *   q         - free-text search
     *   type[]    - one or more switch types (linear, tactile, clicky)
     *   price_min - minimum price
     *   price_max - maximum price
     *   page      - page number (defaults to 1)
     */
    public function index(): void
    {
        $perPage = 12;

        // Collect and lightly sanitize filter parameters from the query string.
        $filters = [
            'q'         => sanitizeString($_GET['q'] ?? ''),
            'type'      => $_GET['type'] ?? [],
            'price_min' => $_GET['price_min'] ?? '',
            'price_max' => $_GET['price_max'] ?? '',
            'sort'      => sanitizeString($_GET['sort'] ?? ''),
        ];
        $page = max(1, sanitizeInt($_GET['page'] ?? 1));

        // Fetch from model (returns ['products' => [...], 'total_count' => int])
        $result = $this->productModel
            ? $this->productModel->getAll($filters, $page, $perPage)
            : ['products' => [], 'total_count' => 0];

        $products = $result['products'];
        $productCount = $result['total_count'];
        $totalPages = $productCount > 0 ? (int) ceil($productCount / $perPage) : 1;
        $currentPage = 'products';

        require_once __DIR__ . '/../../views/pages/products.php';
    }

    /**
     * Display a single product detail page (GET /products/{id}).
     *
     * @param int $id  The product_id from the URL segment.
     */
    public function show(int $id): void
    {
        // Reject invalid IDs immediately - no point hitting the DB.
        if (!isPositiveInt($id)) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $product = $this->productModel
            ? $this->productModel->getById($id)
            : false;

        if (!$product) {
            http_response_code(404);
            require_once __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $currentPage = 'products';
        require_once __DIR__ . '/../../views/pages/product-detail.php';
    }

    // ─── JSON API ──────────────────────────────────────────────────────────────

    /**
     * Live search endpoint for the AJAX product search (GET /api/search).
     *
     * Accepts:
     *   q         - search term (matched against name and description)
     *   type[]    - optional switch type filter(s)
     *   price_min - optional minimum price
     *   price_max - optional maximum price
     *   limit     - max results to return (capped at 24, default 12)
     *
     * Returns JSON:
     *   { "products": [...], "count": int, "query": string }
     *
     * Each product object includes only the fields needed to render a card,
     * keeping the payload small.
     */
    public function apiSearch(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        $query    = sanitizeString($_GET['q'] ?? '');
        $limit    = min(24, max(1, sanitizeInt($_GET['limit'] ?? 12)));

        $filters = [
            'q'         => $query,
            'type'      => $_GET['type'] ?? [],
            'price_min' => $_GET['price_min'] ?? '',
            'price_max' => $_GET['price_max'] ?? '',
        ];

        $result   = $this->productModel
            ? $this->productModel->getAll($filters, 1, $limit)
            : ['products' => [], 'total_count' => 0];

        // Return only the fields the product card needs - keeps payload lean.
        $cards = array_map(fn($p) => [
            'product_id'     => $p['product_id'],
            'name'           => $p['name'],
            'manufacturer'   => $p['manufacturer'],
            'switch_type'    => $p['switch_type'],
            'price'          => $p['price'],
            'stock_quantity' => $p['stock_quantity'],
            'product_image'  => $p['product_image'] ?? 'placeholder.webp',
        ], $result['products']);

        echo json_encode([
            'products' => $cards,
            'count'    => $result['total_count'],
            'query'    => $query,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }

    // ─── Admin ─────────────────────────────────────────────────────────────────

    /**
     * List all products in the admin panel (GET /admin/products).
     * Shows every product regardless of stock - unlike the public catalogue.
     */
    public function adminIndex(): void
    {
        requireAdmin();

        // Fetch all products with no filters and a generous per-page limit.
        $result = $this->productModel
            ? $this->productModel->getAll([], 1, 200)
            : ['products' => [], 'total_count' => 0];

        $products = $result['products'];
        $currentPage = 'admin';

        require_once __DIR__ . '/../../views/admin/products.php';
    }

    /**
     * Create a new product (POST /admin/products/create).
     *
     * Expects $_POST fields: name, manufacturer, switch_type, price,
     *   stock_quantity, and optionally all other product columns.
     * Expects $_FILES['product_image'] for the product photo (optional).
     */
    public function adminCreate(): void
    {
        requireAdmin();
        verifyCsrf();

        $errors = $this->validateProductPost();

        if (empty($errors)) {
            $data = $this->collectProductPost();

            // Handle optional image upload.
            $imageName = $this->handleImageUpload($_FILES['product_image'] ?? null, $errors);
            if ($imageName) {
                $data['product_image'] = $imageName;
            }
        }

        if (!empty($errors)) {
            // Re-render the admin products view with validation errors.
            $result = $this->productModel
                ? $this->productModel->getAll([], 1, 200)
                : ['products' => [], 'total_count' => 0];
            $products = $result['products'];
            $currentPage = 'admin';
            require_once __DIR__ . '/../../views/admin/products.php';
            return;
        }

        $this->productModel->create($data);

        header('Location: /admin/products');
        exit;
    }

    /**
     * Update an existing product (POST /admin/products/update).
     *
     * Expects $_POST['product_id'] plus any fields to change.
     * A new image upload replaces the old file; omitting the file leaves it unchanged.
     */
    public function adminUpdate(): void
    {
        requireAdmin();
        verifyCsrf();

        $productId = sanitizeInt($_POST['product_id'] ?? 0);
        if (!isPositiveInt($productId)) {
            http_response_code(400);
            echo 'Invalid product ID.';
            return;
        }

        $errors = $this->validateProductPost(isUpdate: true);
        $data = [];

        if (empty($errors)) {
            $data = $this->collectProductPost();

            // Only replace the image if a new file was uploaded.
            if (!empty($_FILES['product_image']['name'])) {
                $imageName = $this->handleImageUpload($_FILES['product_image'], $errors);
                if ($imageName) {
                    $data['product_image'] = $imageName;
                }
            }
        }

        if (!empty($errors)) {
            $result = $this->productModel
                ? $this->productModel->getAll([], 1, 200)
                : ['products' => [], 'total_count' => 0];
            $products = $result['products'];
            $currentPage = 'admin';
            require_once __DIR__ . '/../../views/admin/products.php';
            return;
        }

        $this->productModel->update($productId, $data);

        header('Location: /admin/products');
        exit;
    }

    /**
     * Delete a product (POST /admin/products/delete).
     *
     * Expects $_POST['product_id'].
     * Will fail with a PDOException if the product is referenced in order_items -
     * the ON DELETE RESTRICT foreign key prevents orphaning order history.
     */
    public function adminDelete(): void
    {
        requireAdmin();
        verifyCsrf();

        $productId = sanitizeInt($_POST['product_id'] ?? 0);
        if (!isPositiveInt($productId)) {
            http_response_code(400);
            echo 'Invalid product ID.';
            return;
        }

        try {
            // Remove this product's line items from closed orders first,
            // so the FK no longer blocks the delete.
            if ($this->productModel) {
                $this->productModel->detachFromClosedOrders($productId);
            }
            $this->productModel->delete($productId);
        } catch (\PDOException $e) {
            // FK still fired - product is part of an active order.
            $_SESSION['flash_error'] = 'Cannot delete this product - it is part of an active order (pending, processing, shipped, or delivered).';
            header('Location: /admin/products');
            exit;
        }

        header('Location: /admin/products');
        exit;
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    /**
     * Validate $_POST fields for a product create or update form.
     * Pass isUpdate: true to relax "required" rules (only changed fields needed).
     *
     * @param  bool  $isUpdate  Whether this is an update (relaxed required rules).
     * @return array            Associative array of field => error message.
     */
    private function validateProductPost(bool $isUpdate = false): array
    {
        $errors = [];

        $name = sanitizeString($_POST['name'] ?? '');
        $manufacturer = sanitizeString($_POST['manufacturer'] ?? '');
        $switchType = sanitizeString($_POST['switch_type'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock_quantity'] ?? '';

        if (!$isUpdate || $name !== '') {
            if (strlen($name) < 2 || strlen($name) > 150) {
                $errors['name'] = 'Name must be 2–150 characters.';
            }
        }
        if (!$isUpdate || $manufacturer !== '') {
            if (strlen($manufacturer) < 2 || strlen($manufacturer) > 100) {
                $errors['manufacturer'] = 'Manufacturer must be 2–100 characters.';
            }
        }
        if (!$isUpdate || $switchType !== '') {
            if (!in_array($switchType, ['linear', 'tactile', 'clicky'], true)) {
                $errors['switch_type'] = 'Switch type must be linear, tactile, or clicky.';
            }
        }
        if (!$isUpdate || $price !== '') {
            if (!is_numeric($price) || (float) $price < 0) {
                $errors['price'] = 'Price must be a non-negative number.';
            }
        }
        if (!$isUpdate || $stock !== '') {
            if (!ctype_digit((string) $stock)) {
                $errors['stock_quantity'] = 'Stock must be a whole number.';
            }
        }

        return $errors;
    }

    /**
     * Collect and sanitize all product fields from $_POST.
     * Only returns fields that were actually submitted and are non-empty.
     *
     * @return array  Sanitized column => value pairs ready for the model.
     */
    private function collectProductPost(): array
    {
        $data = [];

        $stringFields = ['name', 'manufacturer', 'switch_type', 'sound_profile', 'compatibility', 'description'];
        foreach ($stringFields as $field) {
            if (isset($_POST[$field]) && $_POST[$field] !== '') {
                $data[$field] = sanitizeString($_POST[$field]);
            }
        }

        $floatFields = ['actuation_force', 'bottom_out_force', 'travel_distance', 'pre_travel_distance', 'price'];
        foreach ($floatFields as $field) {
            if (isset($_POST[$field]) && $_POST[$field] !== '') {
                $data[$field] = (float) $_POST[$field];
            }
        }

        if (isset($_POST['stock_quantity']) && $_POST['stock_quantity'] !== '') {
            $data['stock_quantity'] = (int) $_POST['stock_quantity'];
        }

        return $data;
    }

    /**
     * Validate and move an uploaded product image into the public images directory.
     * Returns the saved filename on success, or null if no file was uploaded.
     * Appends to $errors on validation failure.
     *
     * @param  array|null  $file    Entry from $_FILES (e.g. $_FILES['product_image']).
     * @param  array       &$errors Errors array to append validation messages to.
     * @return string|null          Saved filename, or null.
     */
    private function handleImageUpload(?array $file, array &$errors): ?string
    {
        // No file chosen - skip silently (image stays as default).
        if (empty($file['name'])) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['product_image'] = 'Image upload failed. Please try again.';
            return null;
        }

        // Validate size.
        if ($file['size'] > self::MAX_IMAGE_BYTES) {
            $errors['product_image'] = 'Image must be under 2 MB.';
            return null;
        }

        // Validate MIME type using finfo (safer than relying on the browser-supplied type).
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, self::ALLOWED_IMAGE_TYPES, true)) {
            $errors['product_image'] = 'Only JPEG, PNG, and WebP images are allowed.';
            return null;
        }

        // Build a unique filename to avoid overwriting existing images.
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_', more_entropy: true) . '.' . strtolower($extension);
        $dest = self::IMAGE_UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $errors['product_image'] = 'Could not save the image. Check server permissions.';
            return null;
        }

        return $filename;
    }
}