<?php
/*
 * views/pages/account.php
 * User account dashboard - matches login/register theme
 */

$pageTitle = 'My Account';
ob_start();
?>

<style>
.account-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.account-container {
    background: rgba(255, 255, 255, 0.05);
    padding: 50px 60px;
    border-radius: 12px;
    max-width: 700px;
    width: 100%;
}

.account-header {
    text-align: center;
    margin-bottom: 40px;
}

.account-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
}

.account-header p {
    opacity: 0.92;
    font-size: 1.1rem;
}

.account-section {
    margin-bottom: 35px;
}

.section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.3rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--accent);
}

.info-grid {
    display: grid;
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    transition: all 0.3s;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
}

.info-label {
    font-weight: 700;
    opacity: 0.88;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-weight: 600;
    font-size: 1.05rem;
}

.status-badge {
    display: inline-block;
    padding: 6px 16px;
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
    border: 2px solid #4ade80;
    border-radius: 20px;
    font-weight: 900;
    font-size: 0.85rem;
    text-transform: uppercase;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 35px;
}

.stat-card {
    text-align: center;
    padding: 25px 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    transition: all 0.3s;
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
    transform: translateY(-5px);
}

.stat-value {
    font-family: 'Montserrat', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    color: #ff9f9f;
    margin-bottom: 8px;
}

.stat-label {
    font-weight: 600;
    opacity: 0.88;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.action-btn {
    padding: 18px 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    text-align: center;
    color: inherit;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    display: block;
}

.action-btn:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(215, 58, 58, 0.4);
}

.logout-section {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.logout-btn {
    padding: 14px 40px;
    background: transparent;
    border: 2px solid rgba(248, 113, 113, 0.5);
    color: #f87171;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s;
}

.logout-btn:hover {
    background: rgba(248, 113, 113, 0.1);
    border-color: #f87171;
}

@media (max-width: 768px) {
    .account-page {
        padding: 120px 5vw 60px;
    }
    
    .account-container {
        padding: 40px 30px;
    }

    .account-header h1 {
        font-size: 2rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        grid-template-columns: 1fr;
    }
}
</style>

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
            <form method="POST" action="/logout" style="display: inline;">
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