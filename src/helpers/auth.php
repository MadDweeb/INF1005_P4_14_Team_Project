<?php
/**
 * src/helpers/auth.php
 *
 * Session-based authentication helpers.
 *
 * Include this file in any controller or view that needs to check login state.
 * It is already included globally by public/index.php.
 *
 * Available functions:
 *   isLoggedIn()           → bool
 *   currentUser()          → array|null  (id, username, email, role)
 *   requireLogin($url)     → void  (redirects if not logged in)
 *   requireAdmin()         → void  (redirects if not admin)
 *
 * Session keys set on successful login (in UserController::processLogin):
 *   $_SESSION['user_id']
 *   $_SESSION['username']
 *   $_SESSION['email']
 *   $_SESSION['user_role']
 */

if (session_status() === PHP_SESSION_NONE) {
    session_name($_ENV['SESSION_NAME'] ?? 'switchstore_session');
    session_set_cookie_params([
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 3600),
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']), // HTTPS only in production
        'httponly' => true,                      // Prevent JS access to session cookie
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Returns true if a user is currently logged in.
 */
function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

/**
 * Returns an array of the current user's session data, or null if not logged in.
 */
function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'       => (int)$_SESSION['user_id'],
        'username' => $_SESSION['username']  ?? '',
        'email'    => $_SESSION['email']     ?? '',
        'role'     => $_SESSION['user_role'] ?? 'customer',
    ];
}

/**
 * Redirect to the login page if the user is not authenticated.
 * Pass the intended URL to redirect back after login (optional).
 *
 * @param string $redirectTo  URL to redirect to if not logged in.
 */
function requireLogin(string $redirectTo = '/login'): void
{
    if (!isLoggedIn()) {
        // TODO: Store the intended URL in the session to redirect back after login.
        header('Location: ' . $redirectTo);
        exit;
    }
}

/**
 * Redirect away if the user is not an admin.
 * Always calls requireLogin() first.
 */
function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        header('Location: /');
        exit;
    }
}