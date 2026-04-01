<?php
/**
 * src/models/Product.php
 *
 * Product model - all database operations for keyboard switch products.
 *
 * This class handles every query that touches the `products` table.
 * All queries use PDO prepared statements to prevent SQL injection.
 *
 * Product attributes:
 *   product_id          - Unique identifier (auto-increment primary key)
 *   name                - Product display name (e.g., "Cherry MX Red")
 *   manufacturer        - Brand name (e.g., "Cherry", "Gateron")
 *   switch_type         - 'linear', 'tactile', or 'clicky'
 *   actuation_force     - Force needed to actuate, in grams (gf)
 *   bottom_out_force    - Force at full keypress, in grams (gf)
 *   travel_distance     - Total key travel in mm
 *   pre_travel_distance - Distance from resting position to actuation point in mm
 *   sound_profile       - 'silent', 'quiet', 'medium', or 'loud'
 *   compatibility       - PCB/plate compatibility notes
 *   description         - Full marketing/technical description
 *   price               - Unit price in SGD
 *   stock_quantity      - Units currently available
 *   product_image       - Filename stored in public/assets/images/
 *   created_at          - Record creation timestamp
 *   updated_at          - Last update timestamp
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

    // Columns allowed in create() and update().
    // Any key not listed here is silently ignored, preventing mass-assignment attacks.
    private const ALLOWED_COLUMNS = [
        'name', 'manufacturer', 'switch_type', 'actuation_force',
        'bottom_out_force', 'travel_distance', 'pre_travel_distance',
        'sound_profile', 'compatibility', 'description',
        'price', 'stock_quantity', 'product_image',
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ─── READ ──────────────────────────────────────────────────────────────────

    /**
     * Retrieve products with optional filtering, search, and pagination.
     *
     * Supported $filters keys:
     *   'q'         => string        - search term matched against name & description
     *   'type'      => string|array  - one or more switch_type values
     *   'price_min' => float         - minimum price (inclusive)
     *   'price_max' => float         - maximum price (inclusive)
     *
     * Returns an array with two keys:
     *   'products'    => array  - the paginated product rows
     *   'total_count' => int    - total matching rows (for pagination maths)
     *
     * @param  array $filters  Filter options (see above).
     * @param  int   $page     Current page number (1-indexed).
     * @param  int   $perPage  Rows per page.
     * @return array           ['products' => [...], 'total_count' => int]
     */
    public function getAll(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $conditions = [];
        $params     = [];

        // - Search: match name, manufacturer, switch_type, or description -
        // NOTE: PDO with EMULATE_PREPARES=false (native MySQL) does not allow
        // the same named parameter to appear more than once. Use q1–q4 instead.
        if (!empty($filters['q'])) {
            $conditions[] = '(name LIKE :q1 OR manufacturer LIKE :q2 OR switch_type LIKE :q3 OR description LIKE :q4)';
            $term = '%' . $filters['q'] . '%';
            $params[':q1'] = $term;
            $params[':q2'] = $term;
            $params[':q3'] = $term;
            $params[':q4'] = $term;
        }

        // - Switch type: supports a single string or an array of types -
        if (!empty($filters['type'])) {
            $types = (array) $filters['type'];
            // PDO can't bind arrays directly, so we create one named
            // placeholder per value: (:type0, :type1, ...)
            $placeholders = [];
            foreach ($types as $i => $type) {
                $key = ':type' . $i;
                $placeholders[] = $key;
                $params[$key]   = $type;
            }
            $conditions[] = 'switch_type IN (' . implode(', ', $placeholders) . ')';
        }

        // - Price range -
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $conditions[] = 'price >= :price_min';
            $params[':price_min'] = (float) $filters['price_min'];
        }
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $conditions[] = 'price <= :price_max';
            $params[':price_max'] = (float) $filters['price_max'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // - Total count (no LIMIT) so the pagination widget knows how many pages exist -
        $countStmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} {$where}");
        $countStmt->execute($params);
        $totalCount = (int) $countStmt->fetchColumn();

        // - Paginated results -
        // LIMIT/OFFSET are cast to int and interpolated - safe because they are
        // never derived from user input directly.
        $offset = (max(1, $page) - 1) * $perPage;
        $stmt   = $this->pdo->prepare(
            "SELECT * FROM {$this->table} {$where}
             ORDER BY created_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'products'    => $stmt->fetchAll(),
            'total_count' => $totalCount,
        ];
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
     * Search products by name or description.
     * Convenience wrapper around getAll() for simple search-only use.
     * For the catalogue page with combined filters, call getAll() directly.
     *
     * @param  string $query  The search term.
     * @return array          Matching product rows.
     */
    public function search(string $query): array
    {
        $result = $this->getAll(['q' => $query]);
        return $result['products'];
    }

    /**
     * Retrieve featured products for the homepage carousel.
     *
     * @param  int   $limit Number of products to retrieve.
     * @return array        Array of product rows.
     */
    public function getFeatured(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ─── WRITE ─────────────────────────────────────────────────────────────────

    /**
     * Insert a new product into the database.
     *
     * Required keys: name, manufacturer, switch_type, price, stock_quantity
     * Optional keys: actuation_force, bottom_out_force, travel_distance,
     *                pre_travel_distance, sound_profile, compatibility,
     *                description, product_image
     *
     * Upload the product image and set $data['product_image'] to the filename
     * before calling this method.
     *
     * @param  array $data  Column → value pairs for the new product.
     * @return int          The new product_id, or 0 on failure.
     */
    public function create(array $data): int
    {
        $filtered = array_intersect_key($data, array_flip(self::ALLOWED_COLUMNS));
        if (empty($filtered)) {
            return 0;
        }

        $columns      = array_keys($filtered);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql  = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ")
                 VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);

        $params = [];
        foreach ($filtered as $col => $val) {
            $params[':' . $col] = $val;
        }

        $stmt->execute($params);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update an existing product record.
     * Only the keys present in $data are changed - other columns are untouched.
     *
     * Caller must be an admin (enforced in ProductController::adminUpdate).
     *
     * @param  int   $id    The product_id to update.
     * @param  array $data  Fields to update.
     * @return bool         True on success.
     */
    public function update(int $id, array $data): bool
    {
        $filtered = array_intersect_key($data, array_flip(self::ALLOWED_COLUMNS));
        if (empty($filtered)) {
            return false;
        }

        $setClauses = array_map(fn($col) => "{$col} = :{$col}", array_keys($filtered));

        $sql    = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . "
                   WHERE product_id = :product_id";
        $stmt   = $this->pdo->prepare($sql);
        $params = [':product_id' => $id];

        foreach ($filtered as $col => $val) {
            $params[':' . $col] = $val;
        }

        return $stmt->execute($params);
    }

    /**
     * Delete a product by ID.
     *
     * Because order_items references products with ON DELETE RESTRICT, deleting
     * a product that exists in any order will throw a PDOException. Catch it in
     * the controller and show a friendly error, or switch to a soft-delete
     * approach (add an `is_deleted` column) to preserve order history safely.
     *
     * @param  int  $id  The product_id to delete.
     * @return bool      True on success.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM {$this->table} WHERE product_id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}