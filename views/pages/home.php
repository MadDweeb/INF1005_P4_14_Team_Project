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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KeyForge — Keyboard Switch Store</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <!-- ── Header ──────────────────────────────────────────────────────────── -->
    <header>
        <a href="/" class="site-title">⌨ KeyForge</a>

        <!-- TODO: Replace placeholder links once pages are implemented -->
        <nav aria-label="Main navigation">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/products">Switches</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- ── Main Content ────────────────────────────────────────────────────── -->
    <main id="main-content" tabindex="-1">

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

    </main>

    <!-- ── Footer ──────────────────────────────────────────────────────────── -->
    <footer>
        <!-- TODO: Replace placeholder links and contact info with real details -->
        <nav aria-label="Footer navigation">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/products">Switches</a></li>
                <li><a href="/about">About Us</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
        <address>
            <!-- TODO: Replace with real (fictitious) company contact details -->
            <p>hello@keyforge.example</p>
        </address>
        <p>&copy; <?= date('Y') ?> KeyForge. All rights reserved.</p>
    </footer>

</body>
</html>
