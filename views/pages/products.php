<?php
/*
 * views/pages/products.php
 * Product catalogue with filters and pagination
 */

$pageTitle = 'Products';
$extraCss = ['/css/products.css'];
$extraJs = ['/js/products.js'];
ob_start();
?>


<div class="products-page">
    <div class="products-header">
        <h1>Keyboard Switches</h1>
        <p>Explore our collection of premium mechanical keyboard switches</p>
    </div>

    <div class="products-container">
        <!-- Filter Sidebar -->
        <aside class="filter-sidebar" role="complementary" aria-label="Product filters">
            <form method="GET" action="/products" id="filter-form">
                <!-- Search -->
                <div class="filter-section">
                    <h3>Search</h3>
                    <input 
                        type="text" 
                        name="q" 
                        class="search-box" 
                        placeholder="Search products..."
                        value="<?= htmlspecialchars($filters['q'] ?? '') ?>"
                        aria-label="Search products"
                    >
                </div>

                <!-- Switch Type Filter -->
                <div class="filter-section">
                    <h3>Switch Type</h3>
                    <?php
                    $types = ['linear', 'tactile', 'clicky'];
                    $selectedTypes = (array)($filters['type'] ?? []);
                    foreach ($types as $type):
                    ?>
                        <div class="filter-option">
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="type[]" 
                                    value="<?= $type ?>"
                                    <?= in_array($type, $selectedTypes) ? 'checked' : '' ?>
                                >
                                <?= ucfirst($type) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-section">
                    <h3>Price Range</h3>
                    <div class="price-inputs">
                        <input 
                            type="number" 
                            name="price_min" 
                            placeholder="Min"
                            step="0.01"
                            value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>"
                            aria-label="Minimum price"
                        >
                        <span>-</span>
                        <input 
                            type="number" 
                            name="price_max" 
                            placeholder="Max"
                            step="0.01"
                            value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>"
                            aria-label="Maximum price"
                        >
                    </div>
                </div>

                <!-- Sort -->
                <div class="filter-section">
                    <h3>Sort By</h3>
                    <select name="sort" class="sort-select" aria-label="Sort products">
                        <option value="">Default</option>
                        <option value="price_asc"  <?= ($filters['sort'] ?? '') === 'price_asc'  ? 'selected' : '' ?>>Price: Low → High</option>
                        <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
                        <option value="name_asc"   <?= ($filters['sort'] ?? '') === 'name_asc'   ? 'selected' : '' ?>>Name: A → Z</option>
                        <option value="name_desc"  <?= ($filters['sort'] ?? '') === 'name_desc'  ? 'selected' : '' ?>>Name: Z → A</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-apply">Apply</button>
                    <a href="/products" class="filter-btn filter-reset">Reset</a>
                </div>
            </form>
        </aside>

        <!-- Products Grid -->
        <main class="products-main" role="main">
            <?php if (!empty($products)): ?>
                <p class="results-count"><?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> found</p>
            <?php endif; ?>
            <?php if (empty($products)): ?>
                <div class="no-products">
                    <h2>No products found</h2>
                    <p>Try adjusting your filters or search terms</p>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="/products/<?= $product['product_id'] ?>" class="product-card">
                            <img 
                                src="/assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.webp') ?>" 
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                class="product-image"
                                loading="lazy"
                            >
                            <div class="product-info">
                                <div class="product-type"><?= htmlspecialchars($product['switch_type']) ?></div>
                                <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
                                <div class="product-manufacturer"><?= htmlspecialchars($product['manufacturer']) ?></div>
                                <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                                <div class="product-stock <?=
                                    $product['stock_quantity'] == 0 ? 'stock-out' :
                                    ($product['stock_quantity'] < 20 ? 'stock-low' : 'stock-in')
                                ?>">
                                    <?= $product['stock_quantity'] > 0
                                        ? "{$product['stock_quantity']} in stock"
                                        : "Out of stock"
                                    ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="pagination" aria-label="Product pagination">
                        <?php
                        $currentPageNum = $page ?? 1;
                        
                        // Build query string for pagination links
                        $query = $_GET;
                        unset($query['page']);
                        $queryString = http_build_query($query);
                        $baseUrl = '/products' . ($queryString ? '?' . $queryString . '&' : '?');
                        
                        // Previous button
                        if ($currentPageNum > 1):
                        ?>
                            <a href="<?= $baseUrl ?>page=<?= $currentPageNum - 1 ?>" aria-label="Previous page">← Prev</a>
                        <?php endif; ?>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPageNum): ?>
                                <span class="active" aria-current="page"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= $baseUrl ?>page=<?= $i ?>" aria-label="Page <?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Next button -->
                        <?php if ($currentPageNum < $totalPages): ?>
                            <a href="<?= $baseUrl ?>page=<?= $currentPageNum + 1 ?>" aria-label="Next page">Next →</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>