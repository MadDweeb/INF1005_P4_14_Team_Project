<?php
/*
 * views/pages/cart.php
 * Shopping cart page with item management
 */

ob_start();
?>

<style>
    .cart-page {
        padding: 140px 5vw 80px;
        min-height: 100vh;
    }

    .cart-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .cart-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .cart-table-wrapper {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 40px;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table th {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        text-align: left;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .cart-table td {
        padding: 25px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .cart-item-product {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .cart-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
    }

    .cart-item-info h3 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.2rem;
        font-weight: 900;
        margin-bottom: 5px;
    }

    .cart-item-info p {
        opacity: 0.7;
        font-size: 0.9rem;
    }

    .cart-item-price {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--accent);
    }

    .cart-item-quantity {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .cart-item-quantity input {
        width: 70px;
        padding: 8px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: inherit;
        border-radius: 6px;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 700;
        text-align: center;
    }

    .update-btn {
        padding: 8px 16px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .update-btn:hover {
        background: #c32a2a;
    }

    .remove-btn {
        padding: 8px 16px;
        background: transparent;
        color: var(--accent);
        border: 2px solid var(--accent);
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .remove-btn:hover {
        background: var(--accent);
        color: white;
    }

    .cart-item-subtotal {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
    }

    .cart-summary {
        background: rgba(255, 255, 255, 0.05);
        padding: 40px;
        border-radius: 12px;
        max-width: 500px;
        margin-left: auto;
    }

    .cart-summary h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 25px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        font-size: 1.1rem;
    }

    .summary-row.total {
        border-top: 2px solid rgba(255, 255, 255, 0.2);
        margin-top: 15px;
        padding-top: 25px;
        font-family: 'Montserrat', sans-serif;
        font-size: 2rem;
        font-weight: 900;
    }

    .summary-row.total .amount {
        color: var(--accent);
    }

    .checkout-btn {
        width: 100%;
        padding: 18px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
        font-size: 1.2rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        margin-top: 25px;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .checkout-btn:hover {
        background: #c32a2a;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(215, 58, 58, 0.4);
    }

    .empty-cart {
        text-align: center;
        padding: 100px 20px;
    }

    .empty-cart h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .empty-cart p {
        font-size: 1.2rem;
        opacity: 0.7;
        margin-bottom: 30px;
    }

    .continue-shopping {
        display: inline-block;
        padding: 15px 40px;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .continue-shopping:hover {
        background: #c32a2a;
        transform: translateY(-2px);
    }

    @media (max-width: 1024px) {
        .cart-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 768px) {
        .cart-page {
            padding: 120px 5vw 60px;
        }

        .cart-table thead {
            display: none;
        }

        .cart-table,
        .cart-table tbody,
        .cart-table tr,
        .cart-table td {
            display: block;
        }

        .cart-table tr {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            padding: 20px;
        }

        .cart-table td {
            padding: 10px 0;
            border: none;
            text-align: left;
        }

        .cart-table td:before {
            content: attr(data-label);
            font-weight: 900;
            text-transform: uppercase;
            font-size: 0.8rem;
            display: block;
            margin-bottom: 8px;
            opacity: 0.7;
        }
    }
</style>

<div class="cart-page">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
    </div>

    <div class="cart-container">
        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any switches yet!</p>
                <a href="/products" class="continue-shopping">Browse Products</a>
            </div>
        <?php else: ?>
            <!-- Cart Items Table -->
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td data-label="Product">
                                    <div class="cart-item-product">
                                        <img src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'placeholder.webp') ?>"
                                            alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                                        <div class="cart-item-info">
                                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                                            <p><?= htmlspecialchars($item['manufacturer']) ?> •
                                                <?= ucfirst($item['switch_type']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Price">
                                    <div class="cart-item-price">$<?= number_format($item['price'], 2) ?></div>
                                </td>
                                <td data-label="Quantity">
                                    <form method="POST" action="/cart/update" class="cart-item-quantity">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                        <label for="qty-<?= $item['cart_item_id'] ?>" class="sr-only">
                                            Quantity for <?= htmlspecialchars($item['name']) ?>
                                        </label>
                                        <input type="number" id="qty-<?= $item['cart_item_id'] ?>" name="quantity" min="1"
                                            max="<?= $item['stock_quantity'] ?>" value="<?= $item['quantity'] ?>"
                                            aria-label="Quantity">
                                        <button type="submit" class="update-btn">Update</button>
                                    </form>
                                </td>
                                <td data-label="Subtotal">
                                    <div class="cart-item-subtotal">
                                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <form method="POST" action="/cart/remove">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                        <button type="submit" class="remove-btn">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="amount">$<?= number_format($cartTotal, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span class="amount">Calculated at checkout</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span class="amount">$<?= number_format($cartTotal, 2) ?></span>
                </div>
                <a href="/checkout" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>