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
?>

<section class="auth-form-section" aria-labelledby="login-heading">
    <h1 id="login-heading">Log In</h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger" role="alert" id="login-error">
            <?= escapeHtml($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login" novalidate>
        <?= csrfInput() ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                id="email"
                name="email"
                value="<?= escapeHtml($email ?? '') ?>"
                required
                autocomplete="email"
                <?= !empty($errors['general']) ? 'aria-describedby="login-error" aria-invalid="true"' : '' ?>>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                <?= !empty($errors['general']) ? 'aria-describedby="login-error" aria-invalid="true"' : '' ?>>
        </div>

        <button type="submit" class="btn btn-primary">Log In</button>
    </form>

    <p>Don't have an account? <a href="/register">Register</a></p>
</section>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../../layout/main.php';
?>