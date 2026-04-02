<?php
/*
 * views/admin/dashboard.php
 * Admin dashboard with overview statistics
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin(); // Ensure only admins can access

$currentAdminPage = 'dashboard';
ob_start();
?>

<style>
    .admin-header {
        margin-bottom: 50px;
    }

    .admin-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .admin-header p {
        font-size: 1.1rem;
        opacity: 0.7;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.05);
        padding: 35px;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-5px);
    }

    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.7;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }

    .stat-value {
        font-family: 'Montserrat', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        color: var(--accent);
        line-height: 1;
    }

    .quick-actions {
        background: rgba(255, 255, 255, 0.03);
        padding: 40px;
        border-radius: 12px;
    }

    .quick-actions h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: 25px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .action-btn {
        padding: 18px 25px;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        text-align: center;
        transition: all 0.3s;
        display: block;
    }

    .action-btn:hover {
        background: #c32a2a;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page">
    <div class="admin-header">
        <h1>Admin Dashboard</h1>
        <p>Manage your KeyForge store</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-label">Total Products</div>
            <div class="stat-value">
                <?= $productCount ?? 0 ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">
                <?= $orderCount ?? 0 ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">$
                <?= number_format($totalRevenue ?? 0, 0) ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">
                <?= $pendingOrders ?? 0 ?>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="/admin/products" class="action-btn">Manage Products</a>
            <a href="/admin/orders" class="action-btn">View Orders</a>
            <a href="/products" class="action-btn">View Store</a>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$pageTitle    = 'Admin Dashboard';
require_once __DIR__ . '/../layout/admin.php';
?>