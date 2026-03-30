<?php
/*
 * views/pages/products.php
 * Product catalogue with filters and pagination
 */

ob_start();
?>

<style>
.products-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
}

.products-header {
    text-align: center;
    margin-bottom: 60px;
}

.products-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
}

.products-header p {
    font-size: 1.1rem;
    opacity: 0.8;
}

.products-container {
    display: flex;
    gap: 40px;
    max-width: 1800px;
    margin: 0 auto;
}

.filter-sidebar {
    flex: 0 0 280px;
    position: sticky;
    top: 120px;
    align-self: flex-start;
}

.filter-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.filter-section h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 15px;
    letter-spacing: 1px;
}

.filter-option {
    margin-bottom: 12px;
}

.filter-option label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 0.95rem;
}

.filter-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.price-inputs {
    display: flex;
    gap: 10px;
    align-items: center;
}

.price-inputs input {
    width: 100px;
    padding: 8px 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: inherit;
    border-radius: 4px;
    font-family: inherit;
}

.search-box {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: inherit;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.95rem;
}

.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.filter-btn {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    font-size: 0.85rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-apply {
    background-color: var(--accent);
    color: white;
}

.filter-reset {
    background-color: rgba(255, 255, 255, 0.1);
    color: inherit;
}

.products-main {
    flex: 1;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.product-card {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    background: rgba(255, 255, 255, 0.05);
}

.product-image {
    width: 100%;
    height: 280px;
    object-fit: cover;
    background: rgba(255, 255, 255, 0.05);
}

.product-info {
    padding: 25px;
}

.product-type {
    font-size: 0.75rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--accent);
    margin-bottom: 8px;
}

.product-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.4rem;
    font-weight: 900;
    margin-bottom: 6px;
}

.product-manufacturer {
    font-size: 0.9rem;
    opacity: 0.7;
    margin-bottom: 12px;
}

.product-price {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.6rem;
    font-weight: 900;
    color: var(--accent);
}

.product-stock {
    font-size: 0.85rem;
    opacity: 0.6;
    margin-top: 8px;
}

.no-products {
    text-align: center;
    padding: 80px 20px;
}

.no-products h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    margin-bottom: 15px;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
}

.pagination a,
.pagination span {
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    text-decoration: none;
    color: inherit;
    font-weight: 700;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: var(--accent);
    color: white;
}

.pagination .active {
    background: var(--accent);
    color: white;
}

@media (max-width: 1024px) {
    .products-container {
        flex-direction: column;
    }
    
    .filter-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .products-page {
        padding: 120px 5vw 60px;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

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

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="filter-btn filter-apply">Apply</button>
                    <a href="/products" class="filter-btn filter-reset">Reset</a>
                </div>
            </form>
        </aside>

        <!-- Products Grid -->
        <main class="products-main" role="main">
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
                                <div class="product-stock">
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