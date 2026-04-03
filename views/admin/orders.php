<?php
/*
 * views/admin/orders.php
 * Admin orders management page
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'orders';
$adminOrdersCssVersion = @filemtime(__DIR__ . '/../../public/css/admin-orders.css') ?: time();
$extraCss = ['/css/admin-orders.css?v=' . $adminOrdersCssVersion];
ob_start();
?>

<div class="admin-page">
    <div class="admin-header">
        <h1>Order Management</h1>
    </div>

    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Date</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
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
                                <?php if ($order['status'] === 'cancelled'): ?>
                                    <form method="POST" action="/admin/orders/delete" class="inline-delete-order-form"
                                          onsubmit="return confirm('Permanently delete order #<?= str_pad($order['order_id'], 5, '0', STR_PAD_LEFT) ?>?');">
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                        <button type="submit" class="delete-order-btn">Delete</button>
                                    </form>
                                <?php endif; ?>
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
$pageTitle    = 'Admin - Orders';
require_once __DIR__ . '/../layout/admin.php';
?>