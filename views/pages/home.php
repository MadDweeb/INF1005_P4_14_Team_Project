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

$pageTitle = 'Welcome to KeyForge';
ob_start();
?>
<!-- Hero section -->
<section class="hero" aria-labelledby="heroHeading">
    <h1 id="heroHeading">Find Your Perfect Switch</h1>
    <p>
        Explore our collection of premium mechanical keyboard switches —
        linear, tactile, and clicky.
    </p>
    <!-- TODO: Link to /products once the product catalogue is implemented -->
    <a href="/products">Shop Switches</a>
</section>

<!-- Featured products placeholder -->
<section aria-labelledby="featuredHeading">
    <h2 id="featuredHeading">Featured Switches</h2>

    <!--
        TODO: Load featured products from the database.
        Example:
            $featuredProducts = $productModel->getFeatured(4);
        Then loop through $featuredProducts and render each product card.
    -->
    <p>Featured products will appear here once the catalogue is set up.</p>
</section>

<!-- Value propositions placeholder -->
<section aria-labelledby="whyHeading">
    <h2 id="whyHeading">Why KeyForge?</h2>

    <!--
        TODO: Add value proposition content here, for example:
        - Curated selection of switches
        - Fast local delivery
        - Expert advice
    -->
    <p>Content coming soon.</p>
</section>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>