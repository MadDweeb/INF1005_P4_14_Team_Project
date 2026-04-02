<?php
/*
 * views/partials/navbar.php
 *
 * Main navigation partial.
 */

$navLinks = [
    '/products' => ['label' => 'Switches', 'key' => 'products'],
    '/orders' => ['label' => 'Orders', 'key' => 'orders'],
    '/customizer' => ['label' => 'Customize', 'key' => 'customizer'],
    '/about' => ['label' => 'About', 'key' => 'about'],
    '/cart' => ['label' => 'Cart', 'key' => 'cart'],
];
?>
<nav class="main-nav" id="main-nav" aria-label="Main navigation">
    <ul>
        <?php foreach ($navLinks as $href => $link):
            $isCurrent = (($currentPage ?? '') === $link['key']);
            ?>
            <li>
                <a href="<?= $href ?>" <?= $isCurrent ? ' aria-current="page"' : '' ?>>
                    <?= $link['label'] ?>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if (isLoggedIn()): ?>
            <li><a href="/account">Account</a></li>
            <li>
                <form method="POST" action="/logout" class="logout-form">
                    <?= csrfInput() ?>
                    <button type="submit" class="nav-logout-btn" aria-label="Logout">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        Logout
                    </button>
                </form>
            </li>
        <?php else: ?>
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
