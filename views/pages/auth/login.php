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
}
</style>

<div class="auth-page">
    <section class="auth-form-section" aria-labelledby="login-heading">
        <h1 id="login-heading">Log In</h1>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger" role="alert">
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
                       autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password">
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