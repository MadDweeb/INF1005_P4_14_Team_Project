<?php
/*
 * views/partials/header.php
 *
 * TODO: Implement the site-wide header partial.
 *
 * Suggested content:
 *   - Site logo / brand name linking to /
 *   - Utility links (login/register or user account + logout)
 *   - Cart icon with item count badge (once cart is implemented)
 *
 * ACCESSIBILITY: Use <header> as a landmark. Ensure all links have descriptive text.
 */
?>

<header class="main-header" role="banner">
    <p class="logo">
        <a href="/" class="site-title" aria-label="KeyForge Home">KeyForge</a>
    </p>

    <button class="mobile-menu-toggle" aria-label="Open navigation menu" aria-expanded="false">
        <i class="fas fa-bars"></i>
    </button>

    <nav class="main-nav" aria-label="Main navigation">
        <ul>
            <li><a href="/products" aria-label="Browse switches">Switches</a></li>
            <li><a href="/customizer" aria-label="Customize your switches">Customize</a></li>
            <li><a href="/about" aria-label="Learn about KeyForge">About</a></li>
            <li><a href="/cart" aria-label="View shopping cart">Cart</a></li>
            <li><a href="/login" aria-label="Login">Login</a></li>
            <li><a href="/register" aria-label="Register">Register</a></li>
        </ul>
    </nav>
</header>