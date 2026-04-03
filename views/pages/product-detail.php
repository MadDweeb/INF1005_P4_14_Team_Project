<?php
/*
 * views/pages/product-detail.php
 * Single product detail page with specifications and add to cart
 */

ob_start();
$extraCss = ['/css/product-detail.css'];
?>

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
            <img src="/assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.webp') ?>"
                alt="<?= htmlspecialchars($product['name']) ?>" class="product-main-image">
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
                <form method="POST" action="<?= htmlspecialchars(appUrl('/cart/add'), ENT_QUOTES, 'UTF-8') ?>" class="add-to-cart-form">
                    <?= csrfInput() ?>
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="redirect" value="/products/<?= $product['product_id'] ?>">

                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?= $product['stock_quantity'] ?>"
                            value="1" required>
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