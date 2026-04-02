<?php
/*
 * views/pages/order-detail.php
 * Customer order detail page with cancellation action
 */

$pageTitle = 'Order #' . str_pad((string) ($order['order_id'] ?? '0'), 5, '0', STR_PAD_LEFT);
$extraCss = ['/css/order-detail.css'];
ob_start();

$status      = strtolower((string) ($order['status'] ?? 'pending'));
$isPending   = ($status === 'pending');
$isDelivered = ($status === 'delivered');
$isCompleted = ($status === 'completed');
?>

<div class="order-detail-page">
    <nav class="order-breadcrumb" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span>/</span>
        <a href="/orders">Orders</a>
        <span>/</span>
        <span aria-current="page">#<?= str_pad((string) $order['order_id'], 5, '0', STR_PAD_LEFT) ?></span>
    </nav>

    <header class="order-header">
        <div>
            <h1>Order #<?= str_pad((string) $order['order_id'], 5, '0', STR_PAD_LEFT) ?></h1>
            <p>Placed on <?= date('M j, Y g:i A', strtotime((string) $order['created_at'])) ?></p>
        </div>
        <div class="order-header-side">
            <span class="status-badge status-<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>
            </span>
            <strong class="order-total">$<?= number_format((float) $order['total_amount'], 2) ?></strong>
        </div>
    </header>

    <?php if ($isPending): ?>
        <section class="cancel-box" aria-label="Cancel order action">
            <h2>Need to cancel?</h2>
            <p>You can cancel this order while it is still pending.</p>
            <form method="POST" action="/orders/cancel" class="cancel-form">
                <?= csrfInput() ?>
                <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                <button type="submit" class="cancel-btn" onclick="return confirm('Cancel this order? This will restore stock quantities.');">
                    Cancel Order
                </button>
            </form>
        </section>
    <?php elseif ($isDelivered): ?>
        <section class="cancel-box" aria-label="Order received action" style="border-color: #4ade80;">
            <h2>Order received?</h2>
            <p>Confirm you have received your order to close it.</p>
            <form method="POST" action="/orders/received" class="cancel-form">
                <?= csrfInput() ?>
                <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                <button type="submit" class="cancel-btn" style="background: #4ade80; color: #1a1a1a;" onclick="return confirm('Confirm you have received this order?');">
                    Order Received
                </button>
            </form>
        </section>
    <?php elseif ($isCompleted): ?>
        <section class="cancel-box" aria-label="Order completed" style="border-color: #4ade80;">
            <p style="color: #4ade80; font-weight: 700;">You have confirmed receipt of this order.</p>
        </section>
    <?php endif; ?>

    <section class="order-items" aria-label="Order items">
        <h2>Items</h2>
        <div class="order-items-table-wrap">
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($order['items'] ?? []) as $item): ?>
                        <tr>
                            <td data-label="Product">
                                <?= htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Unit Price">
                                $<?= number_format((float) $item['unit_price'], 2) ?>
                            </td>
                            <td data-label="Qty">
                                <?= (int) $item['quantity'] ?>
                            </td>
                            <td data-label="Line Total">
                                <strong>$<?= number_format((float) $item['line_total'], 2) ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="order-actions">
        <a href="/orders" class="back-link">Back to Orders</a>
        <a href="/products" class="shop-link">Continue Shopping</a>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
