<?php
/*
 * views/pages/checkout.php
 * Checkout page with shipping details and order summary
 */

ob_start();
$extraCss = ['/css/checkout.css'];
$extraJs = ['/js/checkout.js'];

// Get custom builds from session
$customBuilds = $_SESSION['custom_builds'] ?? [];
?>

<div class="checkout-page">
    <div class="checkout-header">
        <h1>Checkout</h1>
    </div>

    <?php if (empty($cartItems) && empty($customBuilds)): ?>
        <div class="empty-cart-message">
            <h2>Your cart is empty</h2>
            <p>Please add items to your cart before checking out.</p>
            <a href="/products">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="checkout-container">
            <!-- Shipping Form -->
            <div class="checkout-form-section">
                <h2>Shipping Information</h2>

                <form method="POST" action="/checkout/process" id="checkout-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-row">
                        <div class="form-group <?= isset($errors['first_name']) ? 'error' : '' ?>">
                            <label for="first_name">
                                First Name <span class="required">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name"
                                value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required aria-required="true"
                                <?= isset($errors['first_name']) ? 'aria-describedby="first_name-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['first_name'])): ?>
                                <span id="first_name-error" class="form-error" role="alert"><?= htmlspecialchars($errors['first_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['last_name']) ? 'error' : '' ?>">
                            <label for="last_name">
                                Last Name <span class="required">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required aria-required="true"
                                <?= isset($errors['last_name']) ? 'aria-describedby="last_name-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['last_name'])): ?>
                                <span id="last_name-error" class="form-error" role="alert"><?= htmlspecialchars($errors['last_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group <?= isset($errors['email']) ? 'error' : '' ?>">
                        <label for="email">
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($_POST['email'] ?? $user['email'] ?? '') ?>" required
                            aria-required="true" <?= isset($errors['email']) ? 'aria-describedby="email-error" aria-invalid="true"' : '' ?>>
                        <?php if (isset($errors['email'])): ?>
                            <span id="email-error" class="form-error" role="alert"><?= htmlspecialchars($errors['email']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['phone']) ? 'error' : '' ?>">
                        <label for="phone">
                            Phone Number <span class="required">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                            required aria-required="true" <?= isset($errors['phone']) ? 'aria-describedby="phone-error" aria-invalid="true"' : '' ?>>
                        <?php if (isset($errors['phone'])): ?>
                            <span id="phone-error" class="form-error" role="alert"><?= htmlspecialchars($errors['phone']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['address']) ? 'error' : '' ?>">
                        <label for="address">
                            Street Address <span class="required">*</span>
                        </label>
                        <input type="text" id="address" name="address"
                            value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required aria-required="true"
                            <?= isset($errors['address']) ? 'aria-describedby="address-error" aria-invalid="true"' : '' ?>>
                        <?php if (isset($errors['address'])): ?>
                            <span id="address-error" class="form-error" role="alert"><?= htmlspecialchars($errors['address']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group <?= isset($errors['city']) ? 'error' : '' ?>">
                            <label for="city">
                                City <span class="required">*</span>
                            </label>
                            <input type="text" id="city" name="city" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                                required aria-required="true" <?= isset($errors['city']) ? 'aria-describedby="city-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['city'])): ?>
                                <span id="city-error" class="form-error" role="alert"><?= htmlspecialchars($errors['city']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['state']) ? 'error' : '' ?>">
                            <label for="state">
                                State/Province <span class="required">*</span>
                            </label>
                            <input type="text" id="state" name="state"
                                value="<?= htmlspecialchars($_POST['state'] ?? '') ?>" required aria-required="true"
                                <?= isset($errors['state']) ? 'aria-describedby="state-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['state'])): ?>
                                <span id="state-error" class="form-error" role="alert"><?= htmlspecialchars($errors['state']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group <?= isset($errors['zip']) ? 'error' : '' ?>">
                            <label for="zip">
                                ZIP/Postal Code <span class="required">*</span>
                            </label>
                            <input type="text" id="zip" name="zip" value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>"
                                required aria-required="true" <?= isset($errors['zip']) ? 'aria-describedby="zip-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['zip'])): ?>
                                <span id="zip-error" class="form-error" role="alert"><?= htmlspecialchars($errors['zip']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($errors['country']) ? 'error' : '' ?>">
                            <label for="country">
                                Country <span class="required">*</span>
                            </label>
                            <input type="text" id="country" name="country"
                                value="<?= htmlspecialchars($_POST['country'] ?? 'United States') ?>" required
                                aria-required="true" <?= isset($errors['country']) ? 'aria-describedby="country-error" aria-invalid="true"' : '' ?>>
                            <?php if (isset($errors['country'])): ?>
                                <span id="country-error" class="form-error" role="alert"><?= htmlspecialchars($errors['country']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">
                            Order Notes (Optional)
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                            placeholder="Special instructions for your order..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="submit-order-btn">
                        Place Order
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary-section">
                <div class="order-summary">
                    <h2>Order Summary</h2>

                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <img src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'placeholder.webp') ?>"
                                alt="" class="order-item-image">
                            <div class="order-item-info">
                                <div class="order-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="order-item-details">
                                    Qty: <?= $item['quantity'] ?> × $<?= number_format($item['price'], 2) ?>
                                </div>
                            </div>
                            <div class="order-item-price">
                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php foreach ($customBuilds as $build): ?>
                        <div class="order-item custom-build-item">
                            <img src="/assets/images/<?= htmlspecialchars($build['product_image'] ?? 'custom_switch.webp') ?>"
                                alt="" class="order-item-image">
                            <div class="order-item-info">
                                <div class="order-item-name">
                                    <?= htmlspecialchars($build['name']) ?> 
                                    <span class="custom-badge">CUSTOM</span>
                                </div>
                                <div class="order-item-details">
                                    <?= htmlspecialchars($build['description']) ?><br>
                                    Qty: <?= $build['quantity'] ?> × $<?= number_format($build['price'], 2) ?>
                                </div>
                            </div>
                            <div class="order-item-price">
                                $<?= number_format($build['price'] * $build['quantity'], 2) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="order-totals">
                        <div class="order-total-row">
                            <span>Subtotal</span>
                            <span class="amount">$<?= number_format($cartTotal, 2) ?></span>
                        </div>
                        <div class="order-total-row">
                            <span>Shipping</span>
                            <span class="amount">FREE</span>
                        </div>
                        <div class="order-total-row">
                            <span>Tax</span>
                            <span class="amount">Calculated at next step</span>
                        </div>
                        <div class="order-total-row final">
                            <span>Total</span>
                            <span class="amount">$<?= number_format($cartTotal, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>