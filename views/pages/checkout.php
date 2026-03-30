<?php
/*
 * views/pages/checkout.php
 * Checkout page with shipping details and order summary
 */

ob_start();
?>

<style>
.checkout-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
}

.checkout-header {
    text-align: center;
    margin-bottom: 60px;
}

.checkout-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 60px;
}

.checkout-form-section {
    background: rgba(255, 255, 255, 0.03);
    padding: 40px;
    border-radius: 12px;
}

.checkout-form-section h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.form-group label .required {
    color: var(--accent);
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    color: inherit;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--accent);
    background: rgba(255, 255, 255, 0.08);
}

.form-group.error input,
.form-group.error textarea,
.form-group.error select {
    border-color: #f87171;
}

.form-error {
    color: #f87171;
    font-size: 0.85rem;
    margin-top: 6px;
    display: block;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.order-summary-section {
    position: sticky;
    top: 120px;
    align-self: start;
}

.order-summary {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 12px;
}

.order-summary h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 25px;
}

.order-item {
    display: flex;
    gap: 15px;
    padding: 20px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
}

.order-item-info {
    flex: 1;
}

.order-item-name {
    font-weight: 900;
    margin-bottom: 5px;
}

.order-item-details {
    font-size: 0.85rem;
    opacity: 0.7;
}

.order-item-price {
    font-weight: 900;
    color: var(--accent);
}

.order-totals {
    margin-top: 30px;
    padding-top: 25px;
    border-top: 2px solid rgba(255, 255, 255, 0.2);
}

.order-total-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    font-size: 1.1rem;
}

.order-total-row.final {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    font-weight: 900;
    margin-top: 15px;
    padding-top: 20px;
    border-top: 2px solid rgba(255, 255, 255, 0.2);
}

.order-total-row.final .amount {
    color: var(--accent);
}

.submit-order-btn {
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
    margin-top: 30px;
    transition: all 0.3s;
}

.submit-order-btn:hover {
    background: #c32a2a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(215, 58, 58, 0.4);
}

.empty-cart-message {
    text-align: center;
    padding: 100px 20px;
}

.empty-cart-message h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 20px;
}

.empty-cart-message a {
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

.empty-cart-message a:hover {
    background: #c32a2a;
    transform: translateY(-2px);
}

@media (max-width: 1024px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
    
    .order-summary-section {
        position: static;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .checkout-page {
        padding: 120px 5vw 60px;
    }
    
    .checkout-form-section,
    .order-summary {
        padding: 30px 20px;
    }
}
</style>

<div class="checkout-page">
    <div class="checkout-header">
        <h1>Checkout</h1>
    </div>

    <?php if (empty($cartItems)): ?>
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
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['first_name'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['first_name']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group <?= isset($errors['last_name']) ? 'error' : '' ?>">
                            <label for="last_name">
                                Last Name <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['last_name'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['last_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group <?= isset($errors['email']) ? 'error' : '' ?>">
                        <label for="email">
                            Email Address <span class="required">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= htmlspecialchars($_POST['email'] ?? $user['email'] ?? '') ?>"
                            required
                            aria-required="true"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span class="form-error"><?= htmlspecialchars($errors['email']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group <?= isset($errors['phone']) ? 'error' : '' ?>">
                        <label for="phone">
                            Phone Number <span class="required">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                            required
                            aria-required="true"
                        >
                        <?php if (isset($errors['phone'])): ?>
                            <span class="form-error"><?= htmlspecialchars($errors['phone']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group <?= isset($errors['address']) ? 'error' : '' ?>">
                        <label for="address">
                            Street Address <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                            required
                            aria-required="true"
                        >
                        <?php if (isset($errors['address'])): ?>
                            <span class="form-error"><?= htmlspecialchars($errors['address']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group <?= isset($errors['city']) ? 'error' : '' ?>">
                            <label for="city">
                                City <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['city'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['city']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group <?= isset($errors['state']) ? 'error' : '' ?>">
                            <label for="state">
                                State/Province <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="state" 
                                name="state" 
                                value="<?= htmlspecialchars($_POST['state'] ?? '') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['state'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['state']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group <?= isset($errors['zip']) ? 'error' : '' ?>">
                            <label for="zip">
                                ZIP/Postal Code <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="zip" 
                                name="zip" 
                                value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['zip'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['zip']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group <?= isset($errors['country']) ? 'error' : '' ?>">
                            <label for="country">
                                Country <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="country" 
                                name="country" 
                                value="<?= htmlspecialchars($_POST['country'] ?? 'United States') ?>"
                                required
                                aria-required="true"
                            >
                            <?php if (isset($errors['country'])): ?>
                                <span class="form-error"><?= htmlspecialchars($errors['country']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">
                            Order Notes (Optional)
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="4"
                            placeholder="Special instructions for your order..."
                        ><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
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
                            <img 
                                src="/assets/images/<?= htmlspecialchars($item['product_image'] ?? 'placeholder.webp') ?>" 
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                class="order-item-image"
                            >
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