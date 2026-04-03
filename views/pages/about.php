<?php
/*
 * views/pages/about.php
 * About KeyForge - Company mission, story, and values
 */

ob_start();
$aboutCssVersion = @filemtime(__DIR__ . '/../../public/css/about.css') ?: time();
$extraCss = ['/css/about.css?v=' . $aboutCssVersion];
$extraJs = ['/js/about.js'];
?>

<div class="about-page">
    <!-- Hero Section -->
    <div class="about-hero">
        <h1>About KeyForge</h1>
        <p>We're on a mission to help keyboard enthusiasts discover the perfect switches for their ideal typing
            experience.</p>
    </div>

    <!-- Story Section -->
    <section class="about-section">
        <h2>Our Story</h2>
        <div class="story-content">
            <p>
                KeyForge was born from a simple frustration: finding the right mechanical keyboard switch shouldn't be
                this hard.
                As passionate keyboard enthusiasts ourselves, we spent countless hours researching switches, reading
                reviews,
                and testing different options.
            </p>
            <p>
                We realized that the mechanical keyboard community needed a better way to explore and compare switches.
                That's why we created KeyForge – a curated selection of premium switches with detailed specifications,
                honest descriptions, and tools to help you make the right choice.
            </p>
            <p>
                Today, we're proud to serve thousands of keyboard builders, gamers, and typing enthusiasts who trust us
                to deliver quality switches and expert guidance.
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Switch Types</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">10K+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">99%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support Available</div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <section class="about-section">
        <h2>What We Stand For</h2>
        <div class="values-grid">
            <div class="value-card">
                <span class="value-icon">🎯</span>
                <h3>Quality First</h3>
                <p>We only stock switches that meet our rigorous quality standards. Every product is tested and verified
                    before it reaches you.</p>
            </div>

            <div class="value-card">
                <span class="value-icon">💡</span>
                <h3>Expert Guidance</h3>
                <p>Our team of keyboard enthusiasts provides honest, detailed information to help you make informed
                    decisions.</p>
            </div>

            <div class="value-card">
                <span class="value-icon">🚀</span>
                <h3>Fast Delivery</h3>
                <p>We know you're excited about your new switches. That's why we ship orders quickly and reliably.</p>
            </div>

            <div class="value-card">
                <span class="value-icon">🤝</span>
                <h3>Community Driven</h3>
                <p>We're part of the mechanical keyboard community. Your feedback shapes what we stock and how we serve
                    you.</p>
            </div>

            <div class="value-card">
                <span class="value-icon">🔧</span>
                <h3>Innovation</h3>
                <p>From our switch customizer to detailed specs, we're constantly building tools to improve your
                    experience.</p>
            </div>

            <div class="value-card">
                <span class="value-icon">♻️</span>
                <h3>Sustainability</h3>
                <p>We're committed to reducing waste through minimal packaging and supporting eco-friendly
                    manufacturers.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Ready to Build?</h2>
        <p>Explore our curated collection of premium mechanical keyboard switches and find your perfect match.</p>
        <a href="/products" class="cta-button">Browse Switches</a>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>