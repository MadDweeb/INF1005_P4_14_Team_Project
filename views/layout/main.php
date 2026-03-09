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
    <title>KeyForge — Keyboard Switch Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Montserrat:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="/" class="site-title">KeyForge</a>
        </div>

        <nav class="main-nav" aria-label="Main navigation">
            <ul>
                <li><a href="/products">Switches</a></li>
                <li><a href="/customizer">Customize</a></li>
                <li><a href="/about">About</a></li>
                <li>
                    <a href="/cart">
                        Cart <span class="cart-count">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="main-content" tabindex="-1">
        <?= $pageContent ?>
    </main>

    <footer class="main-footer">
        <div class="footer-top">
            <div class="newsletter">
                <h2>STAY IN THE LOOP</h2>
                <form action="/subscribe" method="POST" class="subscribe-form">
                    <input type="email" name="email" placeholder="Your email" required>
                    <button type="submit">SIGN UP</button>
                </form>
            </div>

            <div class="social-links">
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="X (Twitter)"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <hr class="footer-divider">

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
            <p>
                &copy; <?= date('Y') ?> KeyForge. All rights reserved. | 
                <a href="/privacy">Privacy Policy</a> | 
                <a href="/terms">Terms & Conditions</a>
            </p>
            <address>
                <p>hello@keyforge.example</p>
            </address>
        </div>
    </footer>

</body>
</html>