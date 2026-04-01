<?php
/*
 * views/pages/account.php
 * User account dashboard
 */

$pageTitle = 'My Account';
$extraCss = ['/css/account.css'];
$extraJs = ['/js/account.js'];

ob_start();
?>

<div class="account-page">
    <!-- Header -->
    <div class="account-header">
        <h1>My Account</h1>
        <p>Welcome back, <?= htmlspecialchars($user['username']) ?>!</p>
    </div>

    <div class="account-container">
        <!-- Account Info Card -->
        <div class="account-card">
            <div class="card-header">
                <h2>Account Information</h2>
                <button class="edit-btn" id="editAccountBtn">
                    <span>✏️</span> Edit
                </button>
            </div>
            
            <div class="info-grid" id="accountInfo">
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= htmlspecialchars($user['username']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value"><?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Status</span>
                    <span class="info-value status-active">Active</span>
                </div>
            </div>

            <!-- Edit Form (Hidden by default) -->
            <form method="POST" action="/account/update" class="edit-form hidden" id="editForm">
                <?= csrfInput() ?>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelEditBtn">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Password Card -->
        <div class="account-card">
            <div class="card-header">
                <h2>Change Password</h2>
                <button class="edit-btn" id="showPasswordBtn">
                    <span>🔒</span> Update
                </button>
            </div>

            <form method="POST" action="/account/password" class="password-form hidden" id="passwordForm">
                <?= csrfInput() ?>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" 
                           minlength="8" required>
                    <small>Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelPasswordBtn">Cancel</button>
                    <button type="submit" class="save-btn">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Quick Actions Card -->
        <div class="account-card actions-card">
            <div class="card-header">
                <h2>Quick Actions</h2>
            </div>
            
            <div class="actions-grid">
                <a href="/orders" class="action-btn">
                    <span class="action-icon">📦</span>
                    <span class="action-label">My Orders</span>
                    <span class="action-count"><?= $orderCount ?? 0 ?> orders</span>
                </a>

                <a href="/products" class="action-btn">
                    <span class="action-icon">🛍️</span>
                    <span class="action-label">Browse Switches</span>
                </a>

                <a href="/customizer" class="action-btn">
                    <span class="action-icon">⚙️</span>
                    <span class="action-label">Build Custom Switch</span>
                </a>

                <a href="/cart" class="action-btn">
                    <span class="action-icon">🛒</span>
                    <span class="action-label">View Cart</span>
                    <?php if (!empty($cartCount)): ?>
                        <span class="action-count"><?= $cartCount ?> items</span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Recent Orders Preview -->
        <?php if (!empty($recentOrders)): ?>
        <div class="account-card">
            <div class="card-header">
                <h2>Recent Orders</h2>
                <a href="/orders" class="view-all-link">View All →</a>
            </div>
            
            <div class="recent-orders">
                <?php foreach (array_slice($recentOrders, 0, 3) as $order): ?>
                    <div class="order-preview">
                        <div class="order-preview-header">
                            <span class="order-number">Order #<?= htmlspecialchars($order['order_id']) ?></span>
                            <span class="order-status status-<?= strtolower($order['status']) ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                        <div class="order-preview-details">
                            <span class="order-date">
                                <?= date('M d, Y', strtotime($order['created_at'])) ?>
                            </span>
                            <span class="order-total">
                                $<?= number_format($order['total_amount'], 2) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Danger Zone -->
        <div class="account-card danger-card">
            <div class="card-header">
                <h2>Account Actions</h2>
            </div>
            
            <div class="danger-actions">
                <button class="logout-btn" onclick="window.location.href='/logout'">
                    <span>🚪</span> Sign Out
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>