<?php
/*
 * views/pages/account.php
 * User account dashboard - matches login/register theme
 */

$pageTitle = 'My Account';
$accountPageCssVersion = @filemtime(__DIR__ . '/../../public/css/account-page.css') ?: time();
$extraCss = ['/css/account-page.css?v=' . $accountPageCssVersion];
ob_start();
?>

<div class="account-page">
    <div class="account-container">
        <div class="account-header">
            <h1>My Account</h1>
            <p>Welcome back, <?= htmlspecialchars($user['username']) ?>!</p>
        </div>

        <!-- Account Information -->
        <div class="account-section">
            <h2 class="section-title">Account Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= htmlspecialchars($user['username']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value"><?= date('F d, Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Status</span>
                    <span class="status-badge">Active</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="account-section">
            <h2 class="section-title">Quick Stats</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $orderCount ?? 0 ?></div>
                    <div class="stat-label">Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $cartCount ?? 0 ?></div>
                    <div class="stat-label">Cart Items</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= ucfirst($user['role'] ?? 'Customer') ?></div>
                    <div class="stat-label">Account Type</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="account-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="action-buttons">
                <a href="/orders" class="action-btn">View My Orders</a>
                <a href="/cart" class="action-btn">Shopping Cart</a>
                <a href="/products" class="action-btn">Browse Products</a>
                <a href="/customizer" class="action-btn">Build Custom</a>
            </div>
        </div>

        <!-- Logout -->
        <div class="logout-section">
            <form method="POST" action="/logout" class="account-inline-form">
                <?= csrfInput() ?>
                <button type="submit" class="logout-btn">Sign Out</button>
            </form>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>