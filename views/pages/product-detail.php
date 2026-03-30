<?php
/*
 * views/pages/product-detail.php
 * Single product detail page with specifications and add to cart
 */

ob_start();
?>

<style>
.product-detail-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
}

.breadcrumb {
    margin-bottom: 30px;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: inherit;
    text-decoration: none;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.breadcrumb a:hover {
    opacity: 1;
    color: var(--accent);
}

.breadcrumb span {
    margin: 0 8px;
    opacity: 0.5;
}

.product-detail-container {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

.product-image-section {
    position: sticky;
    top: 120px;
}

.product-main-image {
    width: 100%;
    height: auto;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    max-height: 600px;
    object-fit: cover;
}

.product-details-section {
    padding: 20px 0;
}

.product-type-badge {
    display: inline-block;
    padding: 8px 16px;
    background: var(--accent);
    color: white;
    font-size: 0.75rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-radius: 20px;
    margin-bottom: 20px;
}

.product-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 900;
    margin-bottom: 10px;
    line-height: 1.1;
}

.product-manufacturer-detail {
    font-size: 1.2rem;
    opacity: 0.7;
    margin-bottom: 25px;
}

.product-price-detail {
    font-family: 'Montserrat', sans-serif;
    font-size: 3rem;
    font-weight: 900;
    color: var(--accent);
    margin-bottom: 15px;
}

.product-stock-detail {
    font-size: 1.1rem;
    margin-bottom: 30px;
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    display: inline-block;
}

.in-stock {
    color: #4ade80;
}

.out-of-stock {
    color: #f87171;
}

.product-description {
    line-height: 1.8;
    font-size: 1.05rem;
    margin-bottom: 40px;
    padding: 25px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
}

.specifications {
    margin-bottom: 40px;
}

.specifications h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.5rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 20px;
    letter-spacing: 1px;
}

.spec-list {
    display: grid;
    gap: 15px;
}

.spec-item {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    padding: 15px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.spec-label {
    font-weight: 700;
    opacity: 0.7;
}

.spec-value {
    font-weight: 600;
}

.add-to-cart-form {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-bottom: 20px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-selector label {
    font-weight: 700;
}

.quantity-selector input {
    width: 80px;
    padding: 12px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    color: inherit;
    border-radius: 6px;
    font-family: inherit;
    font-size: 1.1rem;
    font-weight: 700;
    text-align: center;
}

.add-to-cart-btn {
    flex: 1;
    padding: 16px 40px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 1.1rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
    background: #c32a2a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(215, 58, 58, 0.4);
}

.add-to-cart-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 30px;
    color: inherit;
    text-decoration: none;
    font-weight: 700;
    opacity: 0.7;
    transition: all 0.3s;
}

.back-link:hover {
    opacity: 1;
    color: var(--accent);
    transform: translateX(-5px);
}

@media (max-width: 1024px) {
    .product-detail-container {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .product-image-section {
        position: static;
    }
    
    .spec-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}

@media (max-width: 768px) {
    .product-detail-page {
        padding: 120px 5vw 60px;
    }
    
    .add-to-cart-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .quantity-selector {
        justify-content: space-between;
    }
}
</style>

<div class="product-detail-page">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span>/</span>
        <a href="/products">Products</a>
        <span>/</span>
        <span aria-current="page"><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <div class="product-detail-container">
        <!-- Product Image -->
        <div class="product-image-section">
            <img 
                src="/assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.webp') ?>" 
                alt="<?= htmlspecialchars($product['name']) ?>"
                class="product-main-image"
            >
        </div>

        <!-- Product Details -->
        <div class="product-details-section">
            <span class="product-type-badge"><?= htmlspecialchars($product['switch_type']) ?></span>
            
            <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="product-manufacturer-detail">
                by <?= htmlspecialchars($product['manufacturer']) ?>
            </div>

            <div class="product-price-detail">
                $<?= number_format($product['price'], 2) ?>
            </div>

            <div class="product-stock-detail <?= $product['stock_quantity'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                <?= $product['stock_quantity'] > 0 
                    ? "✓ {$product['stock_quantity']} in stock" 
                    : "✗ Out of stock" 
                ?>
            </div>

            <?php if ($product['description']): ?>
                <div class="product-description">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </div>
            <?php endif; ?>

            <!-- Specifications -->
            <div class="specifications">
                <h2>Technical Specifications</h2>
                <dl class="spec-list">
                    <div class="spec-item">
                        <dt class="spec-label">Switch Type</dt>
                        <dd class="spec-value"><?= ucfirst(htmlspecialchars($product['switch_type'])) ?></dd>
                    </div>
                    
                    <?php if ($product['actuation_force']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Actuation Force</dt>
                            <dd class="spec-value"><?= htmlspecialchars($product['actuation_force']) ?>gf</dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['bottom_out_force']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Bottom-Out Force</dt>
                            <dd class="spec-value"><?= htmlspecialchars($product['bottom_out_force']) ?>gf</dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['travel_distance']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Total Travel</dt>
                            <dd class="spec-value"><?= htmlspecialchars($product['travel_distance']) ?>mm</dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['pre_travel_distance']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Pre-Travel</dt>
                            <dd class="spec-value"><?= htmlspecialchars($product['pre_travel_distance']) ?>mm</dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['sound_profile']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Sound Profile</dt>
                            <dd class="spec-value"><?= ucfirst(htmlspecialchars($product['sound_profile'])) ?></dd>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($product['compatibility']): ?>
                        <div class="spec-item">
                            <dt class="spec-label">Compatibility</dt>
                            <dd class="spec-value"><?= htmlspecialchars($product['compatibility']) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Add to Cart Form -->
            <?php if ($product['stock_quantity'] > 0): ?>
                <form method="POST" action="/cart/add" class="add-to-cart-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            min="1" 
                            max="<?= $product['stock_quantity'] ?>" 
                            value="1"
                            required
                            aria-label="Quantity"
                        >
                    </div>
                    
                    <button type="submit" class="add-to-cart-btn">
                        Add to Cart
                    </button>
                </form>
            <?php else: ?>
                <button class="add-to-cart-btn" disabled>
                    Out of Stock
                </button>
            <?php endif; ?>

            <a href="/products" class="back-link">
                ← Back to Products
            </a>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>