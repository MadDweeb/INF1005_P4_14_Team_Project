<?php
/*
 * views/pages/home.php
 *
 * Scaffold homepage for the KeyForge keyboard switch store.
 *
 * TODO: Replace inline styles with public/css/style.css once the layout is designed.
 * TODO: Load $featuredProducts from the Product model and render them dynamically.
 * TODO: Connect the navigation links once those pages are implemented.
 */

$pageTitle = 'KeyForge';
ob_start();
?>
<!-- Hero section -->
<section class="hero" aria-labelledby="heroHeading">
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

<section class="value-propositions" aria-labelledby="whyHeading">
    <div class="vp-center-text">
        <h2 id="whyHeading">CURATED<br>SELECTION<br>OF<br>SWITCHES</h2>
    </div>

    <div class="vp-spin-ring vp-outer-icons" aria-hidden="true">
        <img src="/assets/images/switch1.png" alt="">
        <img src="/assets/images/switch2.png" alt="">
        <img src="/assets/images/switch3.png" alt="">
        <img src="/assets/images/switch4.png" alt="">
        <img src="/assets/images/switch5.png" alt="">
        <img src="/assets/images/switch6.png" alt="">
        <img src="/assets/images/switch1.png" alt="">
        <img src="/assets/images/switch2.png" alt="">
        <img src="/assets/images/switch3.png" alt="">
        <img src="/assets/images/switch4.png" alt="">
        <img src="/assets/images/switch5.png" alt="">
        <img src="/assets/images/switch6.png" alt="">
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

<section class="featured-drops" aria-labelledby="featuredHeading">
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
                            <div class="product-image-placeholder" aria-label="<?= htmlspecialchars($p['name']) ?>">
                                <span class="placeholder-text"><?= htmlspecialchars($p['name']) ?></span>
                            </div>
                        </a>
                        <div class="carousel-item-content">
                            <h3><?= mb_strtoupper((string) $p['name']) ?></h3>
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

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>