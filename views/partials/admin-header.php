<?php
/*
 * views/partials/admin-header.php
 *
 * Admin-specific header partial - replaces the main site header on admin pages.
 * Uses $currentAdminPage (set by each admin view) to mark the active nav link.
 */

$adminNavLinks = [
    '/admin/dashboard' => ['label' => 'Dashboard', 'key' => 'dashboard'],
    '/admin/products'  => ['label' => 'Products',  'key' => 'products'],
    '/admin/orders'    => ['label' => 'Orders',    'key' => 'orders'],
    '/admin/users'     => ['label' => 'Users',     'key' => 'users'],
];
?>

<header class="admin-header-bar">
    <div class="admin-header-left">
        <a href="/admin/dashboard" class="admin-logo" aria-label="KeyForge Admin">
            KeyForge <span class="admin-badge">Admin</span>
        </a>
    </div>

    <button class="admin-mobile-toggle" aria-label="Open admin menu" aria-expanded="false" aria-controls="admin-nav">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>

    <nav class="admin-nav-bar" id="admin-nav" aria-label="Admin navigation">
        <ul>
            <?php foreach ($adminNavLinks as $href => $link):
                $isActive = (($currentAdminPage ?? '') === $link['key']);
            ?>
                <li>
                    <a href="<?= $href ?>"
                       class="admin-nav-link<?= $isActive ? ' active' : '' ?>"
                       <?= $isActive ? 'aria-current="page"' : '' ?>>
                        <?= $link['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="admin-header-actions">
            <form method="POST" action="/logout" style="display:inline">
                <?= csrfInput() ?>
                <button type="submit" class="admin-logout-btn">Logout</button>
            </form>
        </div>
    </nav>
</header>
