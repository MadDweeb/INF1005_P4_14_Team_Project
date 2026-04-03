<?php
/*
 * views/admin/dashboard.php
 * Admin dashboard with overview statistics
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin(); // Ensure only admins can access

$currentAdminPage = 'dashboard';
$usersWithLockoutStatus = $usersWithLockoutStatus ?? [];
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

    .charts-section {
        margin-top: 50px;
    }

    .charts-section h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: 25px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.05);
        padding: 30px;
        border-radius: 12px;
    }

    .chart-card h3 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.7;
        margin-bottom: 20px;
    }

    .chart-card canvas {
        max-height: 280px;
    }

    @media (max-width: 900px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr 1fr;
        }

        .action-buttons {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .stat-card {
            padding: 24px;
        }

        .stat-value {
            font-size: 2.2rem;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }

        .action-btn {
            padding: 14px 20px;
        }

        .chart-card {
            padding: 20px;
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

    <div class="quick-actions" style="margin-top:50px;">
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
    Chart.defaults.color = 'rgba(255,255,255,0.7)';
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