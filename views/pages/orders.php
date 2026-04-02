<?php
/*
 * views/pages/orders.php
 * User order history — dynamic tracker, status badges, cancel + view-details actions
 */

$pageTitle = 'My Orders';
$extraCss = ['/css/orders.css'];
$extraJs = ['/js/orders.js'];

ob_start();
?>

<div class="orders-page">
    <div class="page-header">
        <h1>My Orders</h1>
        <p>View your order history and track deliveries</p>
    </div>

    <div class="orders-container">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <h2>No orders yet</h2>
                <p>Your orders will appear here once you make a purchase</p>
                <a href="/products" class="shop-btn">Start Shopping</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order):
                $status      = strtolower((string) ($order['status'] ?? 'pending'));
                $isCancelled = ($status === 'cancelled');
                $isPending   = ($status === 'pending');

                // Map status → tracker step index (0-3)
                $statusMap = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];
                $statusIdx = $statusMap[$status] ?? 0;

                // Helper: CSS class for a tracker circle (index 0-3)
                $paddedId = str_pad((string) $order['order_id'], 5, '0', STR_PAD_LEFT);

                $circleClass = function(int $i) use ($statusIdx): string {
                    if ($i <= $statusIdx) return 'completed';
                    if ($i === $statusIdx + 1 && $statusIdx < 3) return 'active';
                    return '';
                };
                // Helper: CSS class for a tracker line (index 0-2, sits between circles i and i+1)
                $lineClass = function(int $i) use ($statusIdx): string {
                    return $i < $statusIdx ? 'completed' : '';
                };
            ?>
                <div class="order-card">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-id">
                            <span class="label">Order</span>
                            <span class="value">#<?= $paddedId ?></span>
                        </div>
                        <div class="order-date">
                            <span class="label">Placed</span>
                            <span class="value"><?= date('M d, Y', strtotime($order['created_at'])) ?></span>
                        </div>
                        <div class="order-status-col">
                            <span class="label">Status</span>
                            <span class="status-badge status-<?= htmlspecialchars($status, ENT_QUOTES) ?>">
                                <?= htmlspecialchars(ucfirst($status), ENT_QUOTES) ?>
                            </span>
                        </div>
                        <div class="order-total">
                            <span class="label">Total</span>
                            <span class="value">$<?= number_format($order['total_amount'], 2) ?></span>
                        </div>
                    </div>

                    <!-- Shipment Tracker (hidden for cancelled orders) -->
                    <?php if ($isCancelled): ?>
                        <div class="cancelled-notice">
                            This order was cancelled and stock has been restored.
                        </div>
                    <?php else: ?>
                        <div class="shipment-tracker">
                            <h3>Shipment Status</h3>
                            <div class="tracker">
                                <div class="tracker-step <?= $circleClass(0) ?>">
                                    <div class="step-circle"><?= $circleClass(0) === 'completed' ? '✓' : '' ?></div>
                                    <div class="step-label">Order Placed</div>
                                </div>
                                <div class="tracker-line <?= $lineClass(0) ?>"></div>
                                <div class="tracker-step <?= $circleClass(1) ?>">
                                    <div class="step-circle"><?= $circleClass(1) === 'completed' ? '✓' : '' ?></div>
                                    <div class="step-label">Processing</div>
                                </div>
                                <div class="tracker-line <?= $lineClass(1) ?>"></div>
                                <div class="tracker-step <?= $circleClass(2) ?>">
                                    <div class="step-circle"><?= $circleClass(2) === 'completed' ? '✓' : '' ?></div>
                                    <div class="step-label">Shipped</div>
                                </div>
                                <div class="tracker-line <?= $lineClass(2) ?>"></div>
                                <div class="tracker-step <?= $circleClass(3) ?>">
                                    <div class="step-circle"><?= $circleClass(3) === 'completed' ? '✓' : '' ?></div>
                                    <div class="step-label">Delivered</div>
                                </div>
                            </div>
                            <?php if ($status !== 'delivered'): ?>
                                <div class="delivery-estimate">
                                    Estimated Delivery: <strong>3–5 business days</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Card Footer -->
                    <div class="order-footer">
                        <span class="order-item-count">
                            <?= (int) $order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?>
                            <?php if (!empty($order['shipping_city'])): ?>
                                · <?= htmlspecialchars($order['shipping_city'], ENT_QUOTES) ?>
                            <?php endif; ?>
                        </span>
                        <div class="order-footer-actions">
                            <?php if ($isPending): ?>
                                <form method="POST" action="/orders/cancel" class="inline-cancel-form"
                                      onsubmit="return confirm('Cancel order #<?= $paddedId ?>? Stock will be restored.');">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                                    <button type="submit" class="order-cancel-btn">Cancel Order</button>
                                </form>
                            <?php endif; ?>
                            <a href="/orders/<?= (int) $order['order_id'] ?>" class="view-order-btn">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>
