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
ob_start();
?>

<style>
    .admin-order-detail {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .admin-breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 40px;
        font-size: 0.9rem;
        opacity: 0.7;
    }

    .admin-breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .admin-breadcrumb a:hover {
        color: var(--accent);
        opacity: 1;
    }

    .admin-breadcrumb .sep { opacity: 0.4; }

    /* Page header */
    .aod-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .aod-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 6px;
    }

    .aod-header-meta {
        font-size: 0.9rem;
        opacity: 0.65;
    }

    .aod-header-right {
        text-align: right;
    }

    .aod-total {
        font-family: 'Montserrat', sans-serif;
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 10px;
    }

    /* Status badge */
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status-pending    { background: #fbbf24; color: #1a1a1a; }
    .status-processing { background: #60a5fa; color: #fff; }
    .status-shipped    { background: #a78bfa; color: #fff; }
    .status-delivered  { background: #4ade80; color: #1a1a1a; }
    .status-cancelled  { background: #f87171; color: #fff; }
    .status-completed  { background: #34d399; color: #1a1a1a; }

    /* Two-column layout */
    .aod-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 30px;
        align-items: start;
    }

    /* Section card */
    .aod-card {
        background: rgba(255, 255, 255, 0.04);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 24px;
    }

    .aod-card:last-child { margin-bottom: 0; }

    .aod-card h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    /* Items table */
    .aod-items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .aod-items-table th {
        text-align: left;
        padding: 12px 16px;
        background: rgba(0, 0, 0, 0.3);
        font-family: 'Montserrat', sans-serif;
        font-size: 0.78rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .aod-items-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        font-size: 0.95rem;
    }

    .aod-items-table tbody tr:last-child td { border-bottom: none; }

    .aod-items-table .line-total {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        color: var(--accent);
    }

    /* Order total row */
    .aod-totals {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid var(--accent);
    }

    .aod-total-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 16px;
        font-size: 0.95rem;
    }

    .aod-total-row.grand {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.2rem;
        font-weight: 900;
        color: var(--accent);
        padding-top: 12px;
    }

    /* Customer + shipping info */
    .info-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 16px;
    }

    .info-row:last-child { margin-bottom: 0; }

    .info-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        opacity: 0.5;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Status update form */
    .status-form select {
        width: 100%;
        padding: 12px 14px;
        background: rgba(255, 255, 255, 0.08);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        color: inherit;
        font-family: inherit;
        font-size: 0.95rem;
        margin-bottom: 14px;
        cursor: pointer;
    }

    .status-form select option {
        background: #fff;
        color: #1a1a1a;
    }

    .status-form select:focus {
        outline: none;
        border-color: var(--accent);
    }

    .status-update-btn {
        width: 100%;
        padding: 14px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .status-update-btn:hover {
        background: #b82020;
        transform: translateY(-1px);
    }

    /* Back link */
    .aod-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: inherit;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        opacity: 0.7;
        margin-top: 30px;
        transition: opacity 0.2s;
    }

    .aod-back:hover { opacity: 1; }

    @media (max-width: 900px) {
        .aod-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

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
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Line Total</th>
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
                        <span style="color: #4ade80; font-weight: 900;">FREE</span>
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
                    <p style="font-size: 0.9rem; opacity: 0.7;">This order has been confirmed as received by the customer and is now closed.</p>
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
