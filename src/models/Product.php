<?php
/**
 * src/models/Product.php
 *
 * Product model — all database operations for keyboard switch products.
 *
 * This class handles every query that touches the `products` table.
 * All queries use PDO prepared statements to prevent SQL injection.
 *
 * Product attributes:
 *   product_id          — Unique identifier (auto-increment primary key)
 *   name                — Product display name (e.g., "Cherry MX Red")
 *   manufacturer        — Brand name (e.g., "Cherry", "Gateron")
 *   switch_type         — 'linear', 'tactile', or 'clicky'
 *   actuation_force     — Force needed to actuate, in grams (gf)
 *   bottom_out_force    — Force at full keypress, in grams (gf)
 *   travel_distance     — Total key travel in mm
 *   pre_travel_distance — Distance from resting position to actuation point in mm
 *   sound_profile       — 'silent', 'quiet', 'medium', or 'loud'
 *   compatibility       — PCB/plate compatibility notes
 *   description         — Full marketing/technical description
 *   price               — Unit price in SGD
 *   stock_quantity      — Units currently available
 *   product_image       — Filename stored in public/assets/images/
 *   created_at          — Record creation timestamp
 *   updated_at          — Last update timestamp
 *
 * Usage:
 *   $product = new Product($pdo);
 *   $all     = $product->getAll();
 *   $single  = $product->getById(3);
 */

class Product
{
    private PDO    $pdo;
    private string $table = 'products';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ─── READ ──────────────────────────────────────────────────────────────────

    /**
     * Retrieve all products, with optional filtering and pagination.
     *
     * TODO: Accept a $filters array with keys:
     *         'type'      => string|array  — filter by switch_type
     *         'price_min' => float         — minimum price
     *         'price_max' => float         — maximum price
     *         'q'         => string        — search term
     * TODO: Accept $page and $perPage for pagination.
     * TODO: Return total count separately for pagination component.
     */
    public function getAll(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        // TODO: Build a dynamic WHERE clause from $filters using bound parameters.
        // Never interpolate filter values directly into the SQL string.
        // TODO: build and execute SELECT query
        return [];
    }

    /**
     * Retrieve a single product by its primary key.
     *
     * @param  int         $id  The product_id to look up.
     * @return array|false      The product row, or false if not found.
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE product_id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Search products by name or description (LIKE-based).
     *
     * TODO: Consider a full-text index on (name, description) for better performance.
     *
     * @param  string $query  The search term.
     * @return array          Matching product rows.
     */
    public function search(string $query): array
    {
        // TODO: LIKE-based search on name and description
        // TODO: Consider a full-text index on (name, description) for better performance.
        return [];
    }

    // ─── WRITE ─────────────────────────────────────────────────────────────────

    /**
     * Insert a new product into the database.
     *
     * @param  array $data  Associative array matching the products table columns.
     * @return int          The new product_id (lastInsertId), or 0 on failure.
     *
     * TODO: Validate $data fields before calling this (in the controller).
     * TODO: Handle product image upload separately before calling this.
     */
    public function create(array $data): int
    {
        // TODO: Build and execute an INSERT prepared statement.
        // Required keys: name, manufacturer, switch_type, price, stock_quantity
        // Optional keys: actuation_force, bottom_out_force, travel_distance,
        //                pre_travel_distance, sound_profile, compatibility,
        //                description, product_image
        return 0;
    }

    /**
     * Update an existing product record.
     *
     * @param  int   $id    The product_id to update.
     * @param  array $data  Fields to update (only provided keys are changed).
     * @return bool         True on success.
     *
     * TODO: Only admin users should be able to call this (guard in controller).
     */
    public function update(int $id, array $data): bool
    {
        // TODO: Build a dynamic SET clause from $data keys.
        // Never build SQL with interpolated user values.
        return false;
    }

    /**
     * Delete a product by ID.
     *
     * @param  int  $id  The product_id to delete.
     * @return bool      True on success.
     *
     * TODO: Consider soft-delete (add `is_deleted TINYINT DEFAULT 0`) instead of
     *       hard DELETE, so order history referencing the product is not broken.
     */
    public function delete(int $id): bool
    {
        // TODO: Implement DELETE prepared statement.
        return false;
    }
}