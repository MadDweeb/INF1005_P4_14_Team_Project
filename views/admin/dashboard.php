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

    .action-btn:focus-visible,
    .lockout-reset-btn:focus-visible {
        outline: 3px solid #ffffff;
        outline-offset: 2px;
    }

    .lockout-section {
        margin-top: 40px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        padding: 30px;
    }

    .lockout-section h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.6rem;
        margin-bottom: 12px;
    }

    .lockout-section > p {
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .lockout-table-wrapper {
        overflow-x: auto;
    }

    .lockout-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .lockout-table th,
    .lockout-table td {
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-align: left;
    }

    .lockout-table th {
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.78rem;
        opacity: 0.75;
    }

    .lockout-status {
        display: inline-block;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .lockout-status.locked {
        background: rgba(248, 113, 113, 0.2);
        color: #fca5a5;
        border: 1px solid rgba(248, 113, 113, 0.7);
    }

    .lockout-status.unlocked {
        background: rgba(74, 222, 128, 0.15);
        color: #86efac;
        border: 1px solid rgba(74, 222, 128, 0.55);
    }

    .lockout-reset-form {
        margin: 0;
    }

    .lockout-reset-btn {
        padding: 9px 14px;
        border-radius: 6px;
        border: 1px solid var(--accent);
        background: transparent;
        color: var(--bg-main);
        font-family: inherit;
        font-weight: 700;
        cursor: pointer;
    }

    .lockout-reset-btn:hover {
        background: var(--accent);
        color: #ffffff;
    }

    .lockout-reset-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .lockout-section {
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

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="/admin/products" class="action-btn">Manage Products</a>
            <a href="/admin/orders" class="action-btn">View Orders</a>
            <a href="/products" class="action-btn">View Store</a>
        </div>
    </div>

    <section class="lockout-section" id="login-lockout-management" aria-labelledby="lockout-heading">
        <h2 id="lockout-heading">Login Lockout Management</h2>
        <p>
            Accounts are locked for 1 hour after 5 failed login attempts.
            Use Reset to clear both the lock timer and failed attempt counter.
        </p>

        <div class="lockout-table-wrapper">
            <table class="lockout-table">
                <caption class="visually-hidden">User login lockout status and reset controls</caption>
                <thead>
                    <tr>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Failed Attempts</th>
                        <th scope="col">Status</th>
                        <th scope="col">Locked Until</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usersWithLockoutStatus)): ?>
                        <tr>
                            <td colspan="7">No user data available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usersWithLockoutStatus as $account): ?>
                            <?php
                            $isLocked = (int) ($account['is_locked'] ?? 0) === 1;
                            $canReset = $isLocked || (int) ($account['failed_login_attempts'] ?? 0) > 0;
                            ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars((string) ($account['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></th>
                                <td><?= htmlspecialchars((string) ($account['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string) ($account['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int) ($account['failed_login_attempts'] ?? 0) ?></td>
                                <td>
                                    <span class="lockout-status <?= $isLocked ? 'locked' : 'unlocked' ?>">
                                        <?= $isLocked ? 'Locked' : 'Not locked' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($account['locked_until'])): ?>
                                        <span><?= htmlspecialchars((string) $account['locked_until'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php else: ?>
                                        <span>Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form class="lockout-reset-form" method="POST" action="/admin/users/reset-lockout">
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="user_id" value="<?= (int) ($account['user_id'] ?? 0) ?>">
                                        <button
                                            type="submit"
                                            class="lockout-reset-btn"
                                            aria-label="Reset login lockout for <?= htmlspecialchars((string) ($account['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                            <?= $canReset ? '' : 'disabled' ?>>
                                            Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php
$pageContent = ob_get_clean();
$pageTitle    = 'Admin Dashboard';
require_once __DIR__ . '/../layout/admin.php';
?>