<?php
/*
 * views/admin/orders.php
 * Admin orders management page
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'orders';
ob_start();
?>

<style>
    .admin-header {
        margin-bottom: 40px;
    }

    .admin-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .orders-table-wrapper {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .orders-table th {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        text-align: left;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .orders-table td {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fbbf24;
        color: #1a1a1a;
    }

    .status-processing {
        background: #60a5fa;
        color: white;
    }

    .status-shipped {
        background: #a78bfa;
        color: white;
    }

    .status-delivered {
        background: #4ade80;
        color: #1a1a1a;
    }

    .status-cancelled {
        background: #f87171;
        color: white;
    }

    .view-details-btn {
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        color: inherit;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .view-details-btn:hover {
        background: var(--accent);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 2rem;
        margin-bottom: 15px;
    }

</style>

<div class="admin-page">
    <div class="admin-header">
        <h1>Order Management</h1>
    </div>

    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <h2>No orders yet</h2>
                                <p>Orders will appear here when customers make purchases.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#
                                    <?= str_pad($order['order_id'], 5, '0', STR_PAD_LEFT) ?>
                                </strong></td>
                            <td>
                                <?= htmlspecialchars($order['username'] ?? 'Guest') ?>
                            </td>
                            <td>
                                <?= date('M j, Y', strtotime($order['created_at'])) ?>
                            </td>
                            <td><strong>$
                                    <?= number_format($order['total_amount'], 2) ?>
                                </strong></td>
                            <td>
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/orders/<?= $order['order_id'] ?>" class="view-details-btn">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
$pageTitle    = 'Admin — Orders';
require_once __DIR__ . '/../layout/admin.php';
?>