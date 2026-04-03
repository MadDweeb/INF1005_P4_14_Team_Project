<?php
/*
 * views/admin/users.php
 * Admin users management - login lockout status and reset controls
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'users';
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

    .admin-header p {
        font-size: 1rem;
        opacity: 0.7;
        margin-top: 8px;
    }

    .lockout-section {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        padding: 30px;
    }

    .lockout-section h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.4rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .lockout-section > p {
        margin-bottom: 24px;
        opacity: 0.7;
        font-size: 0.95rem;
    }

    .lockout-table-wrapper {
        overflow-x: auto;
    }

    .lockout-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .lockout-table th,
    .lockout-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        text-align: left;
    }

    .lockout-table thead th {
        background: rgba(255, 255, 255, 0.05);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.8rem;
        opacity: 0.75;
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

    .lockout-status {
        display: inline-block;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 0.75rem;
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
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid var(--accent);
        background: transparent;
        color: var(--accent);
        font-family: inherit;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .lockout-reset-btn:hover {
        background: var(--accent);
        color: #ffffff;
    }

    .lockout-reset-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
        border-color: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.3);
    }

    .lockout-reset-btn:focus-visible {
        outline: 3px solid #ffffff;
        outline-offset: 2px;
    }

    @media (max-width: 768px) {
        .lockout-section { padding: 20px; }

        .lockout-table th,
        .lockout-table td {
            padding: 10px 10px;
            font-size: 0.82rem;
        }

        .lockout-reset-btn {
            padding: 6px 10px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .lockout-table th,
        .lockout-table td {
            padding: 8px;
            font-size: 0.78rem;
        }
    }
</style>

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
                            <td colspan="7" style="text-align:center; padding:40px; opacity:0.5;">No user data available.</td>
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
                                        <span style="opacity:0.4;">-</span>
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
