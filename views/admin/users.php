<?php
/*
 * views/admin/users.php
 * Admin users management - login lockout status and reset controls
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'users';
$adminUsersCssVersion = @filemtime(__DIR__ . '/../../public/css/admin-users.css') ?: time();
$extraCss = ['/css/admin-users.css?v=' . $adminUsersCssVersion];
ob_start();
?>

<div class="admin-page">
    <div class="admin-header">
        <h1>User Management</h1>
        <p>View all accounts and manage login lockout states.</p>
    </div>

    <section class="lockout-section" aria-labelledby="lockout-heading">
        <h2 id="lockout-heading">Login Lockout Status</h2>
        <p>Accounts are locked for 1 hour after 5 failed login attempts. Use Reset to clear the lock and failed attempt counter.</p>

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
                            <td colspan="7" class="lockout-empty-cell">No user data available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usersWithLockoutStatus as $account):
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
                                        <?= htmlspecialchars((string) $account['locked_until'], ENT_QUOTES, 'UTF-8') ?>
                                    <?php else: ?>
                                        <span class="lockout-empty-dash">-</span>
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
$pageTitle    = 'Admin - Users';
require_once __DIR__ . '/../layout/admin.php';
?>
