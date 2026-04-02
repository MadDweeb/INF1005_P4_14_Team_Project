<?php
/*
 * views/pages/auth/login.php
 *
 * Login form page.
 *
 * Expected variables from UserController::showLogin():
 *   $errors (array) - validation errors (on POST failure)
 *
 * ACCESSIBILITY: All inputs need <label>. Error messages need aria-describedby.
 */

$pageTitle = 'Log In';
ob_start();
$extraCss = ['/css/auth.css'];
$generalError = $errors['general'] ?? '';
?>

<div class="auth-page">
    <section class="auth-form-section" aria-labelledby="login-heading">
        <h1 id="login-heading">Log In</h1>

        <?php if (!empty($generalError)): ?>
            <div class="alert alert-danger" id="login-general-error" role="alert" aria-live="assertive">
                <?= escapeHtml($generalError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" novalidate>
            <?= csrfInput() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= escapeHtml($email ?? '') ?>" required
                    aria-required="true" autocomplete="email" <?= !empty($generalError) ? 'aria-describedby="login-general-error" aria-invalid="true"' : '' ?>>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required aria-required="true"
                    autocomplete="current-password" <?= !empty($generalError) ? 'aria-describedby="login-general-error" aria-invalid="true"' : '' ?>>
            </div>

            <button type="submit" class="btn btn-primary">Log In</button>
        </form>

        <p>Don't have an account? <a href="/register">Register</a></p>
    </section>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../../layout/main.php';
?>