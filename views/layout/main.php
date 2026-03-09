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
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
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

    <main id="main-content" tabindex="-1">
        <?= $pageContent ?>
    </main>

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