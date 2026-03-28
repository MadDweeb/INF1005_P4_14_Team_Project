<?php
/*
 * views/pages/auth/register.php
 *
 * User registration form page.
 *
 * Expected variables from UserController::showRegister():
 *   $errors (array) — validation errors (on POST failure)
 *
 * ACCESSIBILITY: All inputs need <label>. Error messages need aria-describedby.
 */

$pageTitle = 'Register';
ob_start();
?>

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

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../../layout/main.php';
?>