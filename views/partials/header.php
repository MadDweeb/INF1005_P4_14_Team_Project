<?php
/*
 * views/partials/header.php
 *
 * Site-wide header partial - included by views/layout/main.php.
 *
 * ACCESSIBILITY:
 *   - <header> is a native banner landmark; role="banner" is redundant and omitted.
 *   - Nav links must NOT carry aria-label when they have matching visible text
 *     (WCAG 2.5.3 Label in Name). Only the logo link has aria-label because it
 *     has no visible text.
 *   - aria-current="page" is applied by comparing $currentPage to each route.
 *     $currentPage is set by every controller before requiring a view.
 */

?>

<header class="main-header">
    <p class="logo">
        <a href="/" class="site-title" aria-label="KeyForge Home">KeyForge</a>
    </p>

    <button class="mobile-menu-toggle" aria-label="Open navigation menu" aria-expanded="false" aria-controls="main-nav">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>

    <?php include __DIR__ . '/navbar.php'; ?>
</header>