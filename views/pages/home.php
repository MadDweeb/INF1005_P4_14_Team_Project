<?php
/*
 * views/pages/home.php
 *
 * Scaffold homepage for the KeyForge keyboard switch store.
 *
 * TODO: Replace inline styles with public/css/main.css once the layout is designed.
 * TODO: Load $featuredProducts from the Product model and render them dynamically.
 * TODO: Connect the navigation links once those pages are implemented.
 */

$pageTitle = 'KeyForge';
$extraCss = ['/css/home.css'];
$extraJs = ['/js/home.js'];
ob_start();
?>
<!-- Hero section -->
<section class="hero" data-theme="dark" aria-labelledby="heroHeading">
    <img src="/assets/images/home2.webp" alt="" fetchpriority="high" width="100%" height="100%" class="hero-image">
    <div class="hero-content">
        <h1 id="heroHeading">BUILD YOUR<br>DREAM KEYBOARD</h1>
    </div>
</section>

<div class="ticker-bar">
    <div class="ticker-wrapper" aria-hidden="true">
        <div class="ticker-list">
            <span>CURATED SWITCHES</span>
            <span>FAST LOCAL DELIVERY</span>
            <span>PREMIUM QUALITY</span>
            <span>COMMUNITY FOCUS</span>
            <span>EXPERT ADVICE</span>
            <span>CURATED SWITCHES</span>
            <span>FAST LOCAL DELIVERY</span>
            <span>PREMIUM QUALITY</span>
            <span>COMMUNITY FOCUS</span>
            <span>EXPERT ADVICE</span>
        </div>
        <div class="ticker-list">
            <span>CURATED SWITCHES</span>
            <span>FAST LOCAL DELIVERY</span>
            <span>PREMIUM QUALITY</span>
            <span>COMMUNITY FOCUS</span>
            <span>EXPERT ADVICE</span>
            <span>CURATED SWITCHES</span>
            <span>FAST LOCAL DELIVERY</span>
            <span>PREMIUM QUALITY</span>
            <span>COMMUNITY FOCUS</span>
            <span>EXPERT ADVICE</span>
        </div>
    </div>
</div>

<section class="value-propositions" data-theme="dark" aria-labelledby="whyHeading">
    <div class="vp-center-text">
        <h2 id="whyHeading">CURATED<br>SELECTION<br>OF<br>SWITCHES</h2>
    </div>

    <div class="vp-spin-ring vp-outer-icons" aria-hidden="true">
        <img src="/assets/images/switch1.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch2.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch3.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch4.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch5.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch6.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch1.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch2.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch3.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch4.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch5.webp" alt="" width="100%" height="100%">
        <img src="/assets/images/switch6.webp" alt="" width="100%" height="100%">
    </div>
    <div class="vp-spin-ring text-ring" aria-hidden="true">
        <svg viewBox="0 0 800 800" class="circular-text-svg">
            <path id="text-path" d="M 400, 400 m -320, 0 a 320,320 0 1,1 640,0 a 320,320 0 1,1 -640,0"
                fill="transparent" />
            <text>
                <textPath href="#text-path" startOffset="0%">
                    HIGHEST PREMIUM QUALITY • EXPERT ADVICE • FAST LOCAL DELIVERY • COMMUNITY FOCUS • PERFECTED
                    CRAFTSMANSHIP • HIGHEST PREMIUM QUALITY • EXPERT ADVICE • FAST LOCAL DELIVERY • COMMUNITY FOCUS •
                    PERFECTED CRAFTSMANSHIP •
                </textPath>
            </text>
        </svg>
    </div>
</section>

<section class="featured-drops" data-theme="maroon" aria-labelledby="featuredHeading">
    <h2 id="featuredHeading">Featured Switches</h2>

    <div class="carousel-container">
        <button class="carousel-nav left" aria-label="Previous product" type="button">❮</button>
        <div class="carousel-track" id="featuredCarouselTrack">
            <?php
            $displayProducts = $featuredProducts ?? [];
            if (!empty($displayProducts) && count($displayProducts) < 5) {
                $original = $displayProducts;
                while (count($displayProducts) < 5) {
                    $displayProducts = array_merge($displayProducts, $original);
                }
                $displayProducts = array_slice($displayProducts, 0, 5);
            }
            if (!empty($displayProducts)):
                foreach ($displayProducts as $index => $p):
                    ?>
                    <div class="carousel-item" data-index="<?= $index ?>">
                        <a href="/products/<?= $p['product_id'] ?>" class="carousel-img-link" tabindex="-1">
                            <?php if (!empty($p['product_image'])): ?>
                                <img src="/assets/images/<?= htmlspecialchars($p['product_image']) ?>"
                                    alt="<?= htmlspecialchars($p['name']) ?>" class="product-image-placeholder"
                                    style="object-fit: cover; padding: 0;">
                            <?php else: ?>
                                <div class="product-image-placeholder" aria-label="<?= htmlspecialchars($p['name']) ?>">
                                    <span class="placeholder-text"><?= htmlspecialchars($p['name']) ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="carousel-item-content">
                            <h3><?= mb_strtoupper((string) $p['name']) ?></h3>
                            <p style="font-weight: bold; color: var(--accent); margin-bottom: 10px;">
                                $<?= number_format((float) $p['price'], 2) ?>
                            </p>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <a href="/products/<?= $p['product_id'] ?>" class="btn-shop">SHOP NOW ➞</a>
                        </div>
                    </div>
                    <?php
                endforeach;
            else:
                ?>
                <div class="carousel-empty">
                    <p>No switches available.</p>
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-nav right" aria-label="Next product" type="button">❯</button>
    </div>
</section>

<section class="product-catalog" data-theme="beige" aria-labelledby="catalogHeading">
    <div class="catalog-header">
        <h2 id="catalogHeading">Typing, Reimagined</h2>
    </div>
    <div class="catalog-grid">
        <a href="/products" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog1.webp" alt="Linear Switches" width="100%" height="100%">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Linear Switches</h3>
                <span class="catalog-arrow">→</span>
            </div>
        </a>
        <a href="/products" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog2.webp" alt="Tactile Switches" width="100%" height="100%">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Tactile Switches</h3>
                <span class="catalog-arrow">→</span>
            </div>
        </a>
        <a href="/products" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog3.webp" alt="Clicky Switches" width="100%" height="100%">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Clicky Switches</h3>
                <span class="catalog-arrow">→</span>
            </div>
        </a>
    </div>
</section>

<section class="home-custom-wrapper" data-theme="dark" aria-labelledby="homeCustomHeading">
    <div class="home-custom-sticky">
        <div class="home-assembly-container" aria-hidden="true">
            <img src="/assets/images/top_housing.webp" alt="" fetchpriority="high" class="home-assembly-part h-part-top-housing">
            <img src="/assets/images/stem.webp" alt="" fetchpriority="high" class="home-assembly-part h-part-stem">
            <img src="/assets/images/spring.webp" alt="" fetchpriority="high" class="home-assembly-part h-part-spring">
            <img src="/assets/images/bottom_housing.webp" alt="" fetchpriority="high" class="home-assembly-part h-part-bottom">
        </div>
        
        <div class="home-custom-content">
            <h2 id="homeCustomHeading">Custom Switches?</h2>
            <h3 class="home-custom-subheading">Find your preferred Switch</h3>
            <a href="/customizer" class="btn-shop">CUSTOMIZE NOW ➞</a>
        </div>
    </div>
</section>


<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>