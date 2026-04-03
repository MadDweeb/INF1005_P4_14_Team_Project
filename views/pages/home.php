<?php
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
        <img src="/assets/images/switch1.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch2.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch3.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch4.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch5.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch6.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch1.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch2.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch3.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch4.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch5.webp" alt="" width="100%" height="100%" loading="lazy">
        <img src="/assets/images/switch6.webp" alt="" width="100%" height="100%" loading="lazy">
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
        <button class="carousel-nav left" aria-label="Previous product" type="button">
            <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
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
                        <a href="/products/<?= $p['product_id'] ?>" class="carousel-img-link">
                            <?php if (!empty($p['product_image'])): ?>
                                <img src="/assets/images/<?= htmlspecialchars($p['product_image']) ?>"
                                    alt="" class="product-image-placeholder"
                                    style="object-fit: cover; padding: 0;">
                            <?php else: ?>
                                <div class="product-image-placeholder" aria-label="<?= htmlspecialchars($p['name']) ?>">
                                    <span class="placeholder-text"><?= htmlspecialchars($p['name']) ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="carousel-item-content">
                            <h3><?= strtoupper((string) $p['name']) ?></h3>
                            <p style="font-weight: bold; color: var(--accent); margin-bottom: 10px;">
                                $<?= number_format((float) $p['price'], 2) ?>
                            </p>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <a href="/products/<?= $p['product_id'] ?>" class="btn-shop">SHOP NOW <i class="fas fa-arrow-right"
                                    aria-hidden="true"></i></a>
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
        <button class="carousel-nav right" aria-label="Next product" type="button">
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
        <noscript>
            <div style="margin-top: 40px; text-align: center;">
                <p style="margin-bottom: 20px; font-weight: 600;">Interactive carousel is disabled. Explore our full
                    range of
                    switches below.</p>
                <a href="/products" class="btn-shop">VIEW ALL PRODUCTS <i class="fas fa-arrow-right"
                        aria-hidden="true"></i></a>
            </div>
        </noscript>
    </div>
</section>

<section class="product-catalog" data-theme="blue" aria-labelledby="catalogHeading">
    <div class="catalog-header">
        <h2 id="catalogHeading">Typing, Reimagined</h2>
    </div>
    <div class="catalog-grid">
        <a href="/products?q=&type%5B%5D=linear&price_min=&price_max=&sort=" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog1.webp" alt="" width="100%" height="100%" loading="lazy">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Linear Switches</h3>
                <i class="fas fa-arrow-right catalog-arrow" aria-hidden="true"></i>
            </div>
        </a>
        <a href="/products?q=&type%5B%5D=tactile&price_min=&price_max=&sort=" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog2.webp" alt="" width="100%" height="100%"
                    loading="lazy">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Tactile Switches</h3>
                <i class="fas fa-arrow-right catalog-arrow" aria-hidden="true"></i>
            </div>
        </a>
        <a href="/products?q=&type%5B%5D=clicky&price_min=&price_max=&sort=" class="catalog-card">
            <div class="catalog-image-wrapper">
                <img src="/assets/images/catalog3.webp" alt="" width="100%" height="100%" loading="lazy">
            </div>
            <div class="catalog-overlay"></div>
            <div class="catalog-content">
                <h3>Clicky Switches</h3>
                <i class="fas fa-arrow-right catalog-arrow" aria-hidden="true"></i>
            </div>
        </a>
    </div>
</section>

<section class="home-custom-wrapper" data-theme="dark" aria-labelledby="homeCustomHeading">
    <div class="home-custom-sticky">
        <div class="home-assembly-container" aria-hidden="true">
            <img src="/assets/images/pc_smoky_top.webp" alt="" class="home-assembly-part h-part-top-housing"
                loading="lazy">
            <img src="/assets/images/tactile_stem.webp" alt="" class="home-assembly-part h-part-stem" loading="lazy">
            <img src="/assets/images/spring_45g.webp" alt="" class="home-assembly-part h-part-spring" loading="lazy">
            <img src="/assets/images/pc_milky_bottom.webp" alt="" class="home-assembly-part h-part-bottom"
                loading="lazy">
        </div>

        <div class="home-custom-content">
            <h2 id="homeCustomHeading">Custom Switches?</h2>
            <h3 class="home-custom-subheading">Find your preferred Switch</h3>
            <noscript>
                <div style="color: var(--accent); margin-bottom: 20px; font-weight: 600;">
                    Note: Interactive 3D assembly animation is disabled. You can still customize your switches below.
                </div>
            </noscript>
            <a href="/customizer" class="btn-shop">CUSTOMIZE NOW <i class="fas fa-arrow-right"
                    aria-hidden="true"></i></a>
        </div>
    </div>
</section>


<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>