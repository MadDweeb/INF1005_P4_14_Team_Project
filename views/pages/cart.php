<?php
/*
 * views/pages/cart.php
 * Shopping cart page with item management
 */

ob_start();
$extraCss = ['/css/cart.css'];
$cartJsVersion = @filemtime(__DIR__ . '/../../public/js/cart.js') ?: time();
$extraJs = ['/js/cart.js?v=' . $cartJsVersion];
?>

<div class="cart-page">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
    </div>

    <div class="cart-container">
        <?php if (empty($cartItems) && empty($customBuilds)): ?>
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
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                        <label for="qty-<?= $item['cart_item_id'] ?>" class="sr-only">
                                            Quantity for <?= htmlspecialchars($item['name']) ?>
                                        </label>
                                        <input type="number" id="qty-<?= $item['cart_item_id'] ?>" name="quantity" min="1"
                                            max="<?= $item['stock_quantity'] ?>" value="<?= $item['quantity'] ?>">
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
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                        <button type="submit" class="remove-btn">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php foreach ($customBuilds as $index => $build): ?>
                            <tr class="custom-build-item">
                                <td data-label="Product">
                                    <div class="cart-item-product">
                                        <img src="/assets/images/<?= htmlspecialchars($build['product_image'] ?? 'custom_switch.webp') ?>"
                                            alt="<?= htmlspecialchars($build['name']) ?>" class="cart-item-image">
                                        <div class="cart-item-info">
                                            <h3><?= htmlspecialchars($build['name']) ?> <span class="custom-badge">CUSTOM</span></h3>
                                            <p><?= htmlspecialchars($build['description']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Price">
                                    <div class="cart-item-price">$<?= number_format($build['price'], 2) ?></div>
                                </td>
                                <td data-label="Quantity">
                                    <form method="POST" action="/cart/update-custom" class="cart-item-quantity">
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="custom_index" value="<?= $index ?>">
                                        <label for="custom-qty-<?= $index ?>" class="sr-only">
                                            Quantity for <?= htmlspecialchars($build['name']) ?>
                                        </label>
                                        <input type="number" id="custom-qty-<?= $index ?>" name="quantity"
                                            min="10" value="<?= $build['quantity'] ?>">
                                        <button type="submit" class="update-btn">Update</button>
                                    </form>
                                </td>
                                <td data-label="Subtotal">
                                    <div class="cart-item-subtotal">
                                        $<?= number_format($build['price'] * $build['quantity'], 2) ?>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <form method="POST" action="/cart/remove-custom">
                                        <?= csrfInput() ?>
                                        <input type="hidden" name="custom_index" value="<?= $index ?>">
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