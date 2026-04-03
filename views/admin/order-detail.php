<?php
/*
 * views/admin/order-detail.php
 * Admin order detail - view full order info and update shipping status
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$status    = strtolower((string) ($order['status'] ?? 'pending'));
$paddedId  = str_pad((string) ($order['order_id'] ?? 0), 5, '0', STR_PAD_LEFT);

$currentAdminPage = 'orders';
$adminOrderDetailCssVersion = @filemtime(__DIR__ . '/../../public/css/admin-order-detail.css') ?: time();
$extraCss = ['/css/admin-order-detail.css?v=' . $adminOrderDetailCssVersion];
ob_start();
?>

<div class="admin-page admin-order-detail">

    <nav class="admin-breadcrumb">
        <a href="/admin/dashboard">Dashboard</a>
        <span class="sep">/</span>
        <a href="/admin/orders">Orders</a>
        <span class="sep">/</span>
        <span>#<?= $paddedId ?></span>
    </nav>

    <header class="aod-header">
        <div>
            <h1>Order #<?= $paddedId ?></h1>
            <p class="aod-header-meta">
                Placed on <?= date('M j, Y g:i A', strtotime((string) $order['created_at'])) ?>
            </p>
        </div>
        <div class="aod-header-right">
            <div class="aod-total">$<?= number_format((float) $order['total_amount'], 2) ?></div>
            <span class="status-badge status-<?= htmlspecialchars($status, ENT_QUOTES) ?>">
                <?= htmlspecialchars(ucfirst($status), ENT_QUOTES) ?>
            </span>
        </div>
    </header>

    <div class="aod-grid">

        <!-- Left column: items + totals -->
        <div>
            <div class="aod-card">
                <h2>Order Items</h2>
                <table class="aod-items-table">
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
                                <td><?= htmlspecialchars((string) $item['product_name'], ENT_QUOTES) ?></td>
                                <td>$<?= number_format((float) $item['unit_price'], 2) ?></td>
                                <td><?= (int) $item['quantity'] ?></td>
                                <td class="line-total">$<?= number_format((float) $item['line_total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="aod-totals">
                    <?php $subtotal = $order['total_amount'] / 1.09; ?>
                    <div class="aod-total-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="aod-total-row">
                        <span>Tax (9%)</span>
                        <span>$<?= number_format($order['total_amount'] - $subtotal, 2) ?></span>
                    </div>
                    <div class="aod-total-row">
                        <span>Shipping</span>
                        <span class="aod-free-label">FREE</span>
                    </div>
                    <div class="aod-total-row grand">
                        <span>Order Total</span>
                        <span>$<?= number_format((float) $order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right column: customer, shipping, status update -->
        <div>

            <!-- Customer info -->
            <div class="aod-card">
                <h2>Customer</h2>
                <div class="info-row">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= htmlspecialchars((string) ($order['username'] ?? '-'), ENT_QUOTES) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars((string) ($order['email'] ?? '-'), ENT_QUOTES) ?></span>
                </div>
            </div>

            <!-- Shipping address -->
            <div class="aod-card">
                <h2>Shipping Address</h2>
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?= htmlspecialchars((string) ($order['shipping_name'] ?? '-'), ENT_QUOTES) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value"><?= htmlspecialchars((string) ($order['shipping_address'] ?? '-'), ENT_QUOTES) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">City</span>
                    <span class="info-value">
                        <?= htmlspecialchars((string) ($order['shipping_city'] ?? '-'), ENT_QUOTES) ?>
                        <?php if (!empty($order['shipping_postal'])): ?>
                            <?= htmlspecialchars((string) $order['shipping_postal'], ENT_QUOTES) ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- Status update -->
            <?php if ($status === 'completed'): ?>
                <div class="aod-card">
                    <h2>Update Status</h2>
                    <p class="aod-completed-note">This order has been confirmed as received by the customer and is now closed.</p>
                </div>
            <?php elseif ($status !== 'cancelled'): ?>
                <div class="aod-card">
                    <h2>Update Status</h2>
                    <form method="POST" action="/admin/orders/status" class="status-form">
                        <?= csrfInput() ?>
                        <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                        <input type="hidden" name="redirect" value="/admin/orders/<?= (int) $order['order_id'] ?>">
                        <select name="status" aria-label="New order status">
                            <?php foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>>
                                    <?= ucfirst($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="status-update-btn">Update Status</button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <a href="/admin/orders" class="aod-back">
        <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to Orders
    </a>

</div>

<?php
$pageContent = ob_get_clean();
$pageTitle   = 'Admin - Order #' . $paddedId;
require_once __DIR__ . '/../layout/admin.php';
?>
