<?php
/*
 * views/pages/about.php
 * About KeyForge - Company mission, story, and values
 */

ob_start();
?>

<style>
    .about-page {
        padding: 140px 5vw 80px;
        min-height: 100vh;
    }

    .about-hero {
        text-align: center;
        margin-bottom: 80px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .about-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 25px;
        line-height: 1.1;
    }

    .about-hero p {
        font-size: 1.3rem;
        opacity: 0.8;
        line-height: 1.8;
    }

    .about-section {
        max-width: 1200px;
        margin: 0 auto 100px;
    }

    .about-section h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 30px;
        text-align: center;
    }

    .story-content {
        max-width: 800px;
        margin: 0 auto;
        font-size: 1.1rem;
        line-height: 1.9;
        opacity: 0.9;
    }

    .story-content p {
        margin-bottom: 25px;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 40px;
        margin-top: 50px;
    }

    .value-card {
        background: rgba(255, 255, 255, 0.05);
        padding: 40px 30px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s;
    }

    .value-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-5px);
    }

    .value-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        display: block;
    }

    .value-card h3 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.4rem;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }

    .value-card p {
        opacity: 0.8;
        line-height: 1.7;
    }

    .stats-section {
        background: rgba(255, 255, 255, 0.03);
        padding: 80px 5vw;
        border-radius: 20px;
        margin-bottom: 100px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 50px;
        text-align: center;
    }

    .stat-item {
        padding: 20px;
    }

    .stat-number {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(3rem, 5vw, 4.5rem);
        font-weight: 900;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 1rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        opacity: 0.7;
    }

    .cta-section {
        text-align: center;
        padding: 80px 5vw;
        background: linear-gradient(135deg, rgba(215, 58, 58, 0.1) 0%, rgba(215, 58, 58, 0.05) 100%);
        border-radius: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .cta-section h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 25px;
    }

    .cta-section p {
        font-size: 1.2rem;
        opacity: 0.8;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button {
        display: inline-block;
        padding: 20px 50px;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 1.1rem;
        letter-spacing: 1.5px;
        transition: all 0.3s;
    }

    .cta-button:hover {
        background: #c32a2a;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(215, 58, 58, 0.4);
    }

    @media (max-width: 768px) {
        .about-page {
            padding: 120px 5vw 60px;
        }

        .values-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
    }
</style>

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