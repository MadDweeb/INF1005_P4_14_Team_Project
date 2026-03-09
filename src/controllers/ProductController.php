<?php
/**
 * src/controllers/ProductController.php
 *
 * Handles all HTTP requests related to products.
 *
 * Routed from public/index.php:
 *   GET  /products              → index()        — catalogue with filters
 *   GET  /products/{id}         → show($id)      — single product detail
 *   GET  /admin/products        → adminIndex()   — admin product list
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

    public function __construct(?PDO $pdo)
    {
        // Only instantiate the model if we have a DB connection.
        $this->productModel = $pdo ? new Product($pdo) : null;
    }

    /**
     * Display the product catalogue.
     *
     * Reads filter and search parameters from $_GET:
     *   q         — search query string
     *   type[]    — array of switch_type values (linear, tactile, clicky)
     *   price_min — minimum price filter
     *   price_max — maximum price filter
     *   page      — current pagination page
     */
    public function index(): void
    {
        /*
         * TODO:
         * - Read filter parameters from $_GET (q, type[], price_min, price_max, page)
         * - Query $this->productModel->getAll($filters, $page) for matching products
         * - Set $products, $totalPages, $productCount, $currentPage = 'products'
         * - require_once views/pages/products.php
         */
    }

    /**
     * Display a single product detail page.
     *
     * @param int $id  The product_id from the URL.
     */
    public function show(int $id): void
    {
        /*
         * TODO:
         * - Validate $id with isPositiveInt(); return 404 if invalid
         * - $product = $this->productModel ? $this->productModel->getById($id) : false
         * - Return 404 if $product is false
         * - Set $currentPage = 'products'
         * - require_once views/pages/product-detail.php
         */
    }

    // ─── Admin ─────────────────────────────────────────────────────────────────

    /**
     * TODO: Implement adminIndex(), adminCreate(), adminUpdate(), adminDelete().
     * All admin methods must call requireAdmin() as the first line.
     * adminCreate() and adminUpdate() must handle product image file uploads.
     */
}