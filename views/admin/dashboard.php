<?php
/*
 * views/admin/dashboard.php
 * Admin dashboard with overview statistics
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin(); // Ensure only admins can access

$currentAdminPage = 'dashboard';
$usersWithLockoutStatus = $usersWithLockoutStatus ?? [];
$adminDashboardCssVersion = @filemtime(__DIR__ . '/../../public/css/admin-dashboard.css') ?: time();
$extraCss = ['/css/admin-dashboard.css?v=' . $adminDashboardCssVersion];
ob_start();
?>

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

    <div class="charts-section">
        <h2>Analytics</h2>
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Orders by Status</h3>
                <canvas id="statusChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Revenue - Last 30 Days</h3>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="quick-actions quick-actions-spacing">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="/admin/products" class="action-btn">Manage Products</a>
            <a href="/admin/orders" class="action-btn">View Orders</a>
            <a href="/admin/users" class="action-btn">Manage Users</a>
            <a href="/products" class="action-btn">View Store</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.color = 'rgba(255,255,255,0.9)';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.08)';

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Completed'],
            datasets: [{
                data: [
                    <?= (int)($ordersByStatus['pending']    ?? 0) ?>,
                    <?= (int)($ordersByStatus['processing'] ?? 0) ?>,
                    <?= (int)($ordersByStatus['shipped']    ?? 0) ?>,
                    <?= (int)($ordersByStatus['delivered']  ?? 0) ?>,
                    <?= (int)($ordersByStatus['cancelled']  ?? 0) ?>,
                    <?= (int)($ordersByStatus['completed']  ?? 0) ?>
                ],
                backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#4ade80','#f87171','#34d399'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { padding: 16, boxWidth: 12 } } },
            cutout: '65%'
        }
    });

    const revenueLabels = <?= json_encode($revenueLabels ?? []) ?>;
    const revenueData   = <?= json_encode($revenueData   ?? []) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue ($)',
                data: revenueData,
                borderColor: '#e63535',
                backgroundColor: 'rgba(230,53,53,0.15)',
                borderWidth: 2,
                pointRadius: revenueLabels.length > 14 ? 2 : 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: 'rgba(255,255,255,0.06)' }, beginAtZero: true,
                     ticks: { callback: v => '$' + v.toLocaleString() } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();
</script>

<?php
$pageContent = ob_get_clean();
$pageTitle    = 'Admin Dashboard';
require_once __DIR__ . '/../layout/admin.php';
?>