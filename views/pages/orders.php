<?php
/*
 * views/pages/orders.php
 * Customer order history page
 */

$pageTitle = 'My Orders';
$extraCss = ['/css/orders.css'];
ob_start();
?>

<div class="orders-page">
    <div class="orders-header">
        <h1>My Orders</h1>
        <p>Track your purchases and view order details.</p>
    </div>

    <?php if (empty($orders)): ?>
        <section class="orders-empty" aria-live="polite">
            <h2>No orders yet</h2>
            <p>When you place an order, it will appear here.</p>
            <a href="/products" class="orders-empty-link">Browse products</a>
        </section>
    <?php else: ?>
        <section class="orders-table-wrapper" aria-label="Order history">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th scope="col">Order</th>
                        <th scope="col">Placed</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <?php $status = strtolower((string) ($order['status'] ?? 'pending')); ?>
                        <tr>
                            <td data-label="Order">
                                <strong>#<?= str_pad((string) $order['order_id'], 5, '0', STR_PAD_LEFT) ?></strong>
                            </td>
                            <td data-label="Placed">
                                <?= date('M j, Y', strtotime((string) $order['created_at'])) ?>
                            </td>
                            <td data-label="Items">
                                <?= (int) ($order['item_count'] ?? 0) ?>
                            </td>
                            <td data-label="Total">
                                <strong>$<?= number_format((float) $order['total_amount'], 2) ?></strong>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge status-<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td data-label="Action">
                                <a class="details-link" href="/orders/<?= (int) $order['order_id'] ?>">View details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
