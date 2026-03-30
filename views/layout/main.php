<?php
/*
 * views/layout/main.php
 *
 * TODO: Implement the shared HTML layout (master template).
 *
 * This file should define the full HTML document shell used by all pages.
 * Page-specific content is passed via $pageTitle and $pageContent variables
 * (using the output buffer pattern).
 *
 * Usage pattern in a view:
 *   $pageTitle = 'My Page';
 *   ob_start();
 *   // ... render page HTML ...
 *   $pageContent = ob_get_clean();
 *   require_once __DIR__ . '/../layout/main.php';
 *
 * ACCESSIBILITY CHECKLIST:
 *   - lang="en" on <html>
 *   - Unique, descriptive <title> per page
 *   - Skip-to-main link as the first focusable element
 *   - <main id="main-content"> matching the skip link href
 *   - <header>, <nav>, <main>, <footer> landmark elements
 */
?>

<!-- TODO: Implement layout here -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' | ' : '' ?>Keyboard Switch Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Montserrat:wght@900&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
    <script src="/js/main.js" defer></script>
</head>

<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <header class="main-header">
        <div class="logo">
            <a href="/" class="site-title" aria-label="KeyForge Home">KeyForge</a>
        </div>

        <button class="mobile-menu-toggle" aria-label="Open navigation menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="main-nav" aria-label="Main navigation">
            <ul>
                <li><a href="/products">Switches</a></li>
                <li><a href="/customizer">Customize</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/cart">Cart</a></li>
            </ul>
        </nav>
    </header>
    <!-- skip links -->
    <?php include __DIR__ . '/../partials/skip-links.php'; ?>
    <!-- header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main id="main-content" tabindex="-1">
        <?= $pageContent ?>
    </main>

    <footer class="main-footer">
        <div class="footer-top">
            <section class="newsletter" aria-labelledby="newsletter-heading">
                <h2 id="newsletter-heading">STAY IN THE LOOP</h2>
                <form action="/subscribe" method="POST" class="subscribe-form" aria-labelledby="newsletter-heading">
                    <?= csrfInput() ?>
                    <label for="email-subscribe" class="sr-only">Email address</label>
                    <input type="email" name="email" id="email-subscribe" placeholder="Your email" required autocomplete="email">
                    <button type="submit">SIGN UP</button>
                </form>
            </section>

            <ul class="social-links">
                <li>
                    <a href="#" aria-label="Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="#" aria-label="Facebook">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="#" aria-label="X (Twitter)">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </div>

        <hr class="footer-divider" aria-hidden="true">

        <div class="footer-giant-logo" aria-hidden="true">
            KEYFORGE
        </div>

        <nav class="footer-nav" aria-label="Footer navigation">
            <ul class="nav-left">
                <li><a href="/products">SWITCHES</a></li>
                <li><a href="/customizer">CUSTOMIZE</a></li>
                <li><a href="/about">ABOUT US</a></li>
            </ul>
            <ul class="nav-right">
                <li><a href="/contact">CONTACT</a></li>
                <li><a href="/faq">FAQ</a></li>
            </ul>
        </nav>
        <div class="footer-legal">
            <small>
                &copy; <?= date('Y') ?> KeyForge. All rights reserved. | 
                <a href="/privacy">Privacy Policy</a> | 
                <a href="/terms">Terms & Conditions</a>
            </small>
            <address>
                <a href="mailto:hello@keyforge.example">hello@keyforge.example</a>
            </address>
        </div>
    </footer>

    <!-- footer -->
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <!-- Accessibility Widget -->
    <?php include __DIR__ . '/../partials/accessibility.php'; ?>
</body>
</html>
