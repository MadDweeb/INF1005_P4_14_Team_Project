<?php
/*
 * views/pages/orders.php
 * User order history and tracking
 */

$pageTitle = 'My Orders';
$extraCss = ['/css/orders.css'];
$extraJs = ['/js/orders.js'];

ob_start();
?>

<div class="orders-page">
    <!-- Header -->
    <div class="orders-header">
        <h1>My Orders</h1>
        <p>Track and view your order history</p>
    </div>

    <div class="orders-container">
        <?php if (empty($orders)): ?>
            <!-- Empty State -->
            <div class="empty-orders">
                <div class="empty-icon">📦</div>
                <h2>No orders yet</h2>
                <p>Start shopping to see your orders here!</p>
                <a href="/products" class="browse-btn">Browse Switches</a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <!-- Order Header -->
                        <div class="order-card-header">
                            <div class="order-main-info">
                                <h3 class="order-number">Order #<?= htmlspecialchars($order['order_id']) ?></h3>
                                <span class="order-date">
                                    Placed on <?= date('F d, Y', strtotime($order['created_at'])) ?>
                                </span>
                            </div>
                            <div class="order-status-badge status-<?= strtolower($order['status']) ?>">
                                <?= ucfirst($order['status']) ?>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="order-details">
                            <div class="order-items">
                                <h4>Items (<?= count($order['items'] ?? []) ?>)</h4>
                                <div class="items-list">
                                    <?php foreach (($order['items'] ?? []) as $item): ?>
                                        <div class="order-item">
                                            <img src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'placeholder.webp') ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="item-image">
                                            <div class="item-info">
                                                <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
                                                <span class="item-quantity">Qty: <?= $item['quantity'] ?></span>
                                            </div>
                                            <div class="item-price">
                                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>$<?= number_format($order['total_amount'], 2) ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>$<?= number_format($order['shipping_cost'] ?? 0, 2) ?></span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total</span>
                                    <span>$<?= number_format($order['total_amount'] + ($order['shipping_cost'] ?? 0), 2) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Progress -->
                        <?php if ($order['status'] !== 'cancelled'): ?>
                        <div class="order-progress">
                            <div class="progress-track">
                                <div class="progress-step <?= in_array($order['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon">📋</div>
                                    <span class="step-label">Pending</span>
                                </div>
                                <div class="progress-line <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>"></div>
                                
                                <div class="progress-step <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon">⚙️</div>
                                    <span class="step-label">Processing</span>
                                </div>
                                <div class="progress-line <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : '' ?>"></div>
                                
                                <div class="progress-step <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : '' ?>">
                                    <div class="step-icon">🚚</div>
                                    <span class="step-label">Shipped</span>
                                </div>
                                <div class="progress-line <?= $order['status'] === 'delivered' ? 'completed' : '' ?>"></div>
                                
                                <div class="progress-step <?= $order['status'] === 'delivered' ? 'completed' : '' ?>">
                                    <div class="step-icon">✅</div>
                                    <span class="step-label">Delivered</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Shipping Info -->
                        <div class="shipping-info">
                            <h4>Shipping Address</h4>
                            <address>
                                <?= htmlspecialchars($order['shipping_name'] ?? $user['username']) ?><br>
                                <?= htmlspecialchars($order['shipping_address']) ?><br>
                                <?= htmlspecialchars($order['shipping_city']) ?>, 
                                <?= htmlspecialchars($order['shipping_state']) ?> 
                                <?= htmlspecialchars($order['shipping_zip']) ?>
                            </address>
                        </div>

                        <!-- Order Actions -->
                        <div class="order-actions">
                            <?php if ($order['tracking_number']): ?>
                                <button class="track-btn" onclick="alert('Tracking: <?= $order['tracking_number'] ?>')">
                                    📍 Track Package
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($order['status'] === 'delivered'): ?>
                                <a href="/products/<?= $order['items'][0]['product_id'] ?? '' ?>" class="reorder-btn">
                                    🔄 Reorder
                                </a>
                            <?php endif; ?>

                            <button class="details-btn" onclick="toggleOrderDetails(<?= $order['order_id'] ?>)">
                                📄 View Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>