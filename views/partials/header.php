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

$navLinks = [
    '/products'   => ['label' => 'Switches',  'key' => 'products'],
    '/customizer' => ['label' => 'Customize', 'key' => 'customizer'],
    '/about'      => ['label' => 'About',     'key' => 'about'],
    '/cart'       => ['label' => 'Cart',      'key' => 'cart'],
];
?>

<header class="main-header">
    <p class="logo">
        <a href="/" class="site-title" aria-label="KeyForge Home">KeyForge</a>
    </p>

    <button class="mobile-menu-toggle" aria-label="Open navigation menu" aria-expanded="false"
        aria-controls="main-nav">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>

    <nav class="main-nav" id="main-nav" aria-label="Main navigation">
        <ul>
            <?php foreach ($navLinks as $href => $link):
                $isCurrent = (($currentPage ?? '') === $link['key']);
            ?>
            <li>
                <a href="<?= $href ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>>
                    <?= $link['label'] ?>
                </a>
            </li>
            <?php endforeach; ?>

            <?php if (isLoggedIn()): ?>
                <li><a href="/profile">Account</a></li>
                <li>
                    <form method="POST" action="/logout" style="display:inline">
                        <?= csrfInput() ?>
                        <button type="submit" class="btn-link">Logout</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
