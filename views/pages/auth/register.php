<?php
/*
 * views/pages/auth/register.php
 *
 * User registration form page.
 *
 * Expected variables from UserController::showRegister():
 *   $errors (array) - validation errors (on POST failure)
 *
 * ACCESSIBILITY: All inputs need <label>. Error messages need aria-describedby.
 */

$pageTitle = 'Register';
ob_start();
?>

<style>
.auth-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-form-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 50px 60px;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
}

.auth-form-section h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 30px;
    text-align: center;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-weight: 600;
}

.alert-danger {
    background: rgba(248, 113, 113, 0.2);
    color: #f87171;
    border: 2px solid #f87171;
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

.form-group input {
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

.form-group input:focus {
    outline: none;
    border-color: var(--accent);
    background: rgba(255, 255, 255, 0.08);
}

.form-group input[aria-invalid="true"] {
    border-color: #f87171;
}

.error-text {
    display: block;
    color: #f87171;
    font-size: 0.85rem;
    margin-top: 6px;
    font-weight: 600;
}

.btn {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 1rem;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: #c32a2a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(215, 58, 58, 0.4);
}

.auth-form-section > p {
    text-align: center;
    margin-top: 25px;
    opacity: 0.8;
}

.auth-form-section > p a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 700;
    transition: opacity 0.3s;
}

.auth-form-section > p a:hover {
    opacity: 0.8;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .auth-page {
        padding: 120px 5vw 60px;
    }
    
    .auth-form-section {
        padding: 40px 30px;
    }
    
    .auth-form-section h1 {
        font-size: 2rem;
    }
}
</style>

<div class="auth-page">
    <section class="auth-form-section" aria-labelledby="register-heading">
        <h1 id="register-heading">Create an Account</h1>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= escapeHtml($errors['general']) ?>
            </div>
        <?php endif; ?>

    <form method="POST" action="/register" novalidate>
        <?= csrfInput() ?>

        <!-- Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?= escapeHtml($username ?? '') ?>"
                   required
                   minlength="3"
                   maxlength="50"
                   autocomplete="username"
                   <?= !empty($errors['username']) ? 'aria-describedby="username-error" aria-invalid="true"' : '' ?>>
            <?php if (!empty($errors['username'])): ?>
                <span id="username-error" class="error-text" role="alert">
                    <?= escapeHtml($errors['username']) ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= escapeHtml($email ?? '') ?>"
                   required
                   autocomplete="email"
                   <?= !empty($errors['email']) ? 'aria-describedby="email-error" aria-invalid="true"' : '' ?>>
            <?php if (!empty($errors['email'])): ?>
                <span id="email-error" class="error-text" role="alert">
                    <?= escapeHtml($errors['email']) ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password (minimum 8 characters)</label>
            <input type="password"
                   id="password"
                   name="password"
                   required
                   minlength="8"
                   autocomplete="new-password"
                   <?= !empty($errors['password']) ? 'aria-describedby="password-error" aria-invalid="true"' : '' ?>>
            <?php if (!empty($errors['password'])): ?>
                <span id="password-error" class="error-text" role="alert">
                    <?= escapeHtml($errors['password']) ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password"
                   id="confirm_password"
                   name="confirm_password"
                   required
                   minlength="8"
                   autocomplete="new-password"
                   <?= !empty($errors['confirm_password']) ? 'aria-describedby="confirm-error" aria-invalid="true"' : '' ?>>
            <?php if (!empty($errors['confirm_password'])): ?>
                <span id="confirm-error" class="error-text" role="alert">
                    <?= escapeHtml($errors['confirm_password']) ?>
                </span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <p>Already have an account? <a href="/login">Log in</a></p>
</section>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../../layout/main.php';
?>