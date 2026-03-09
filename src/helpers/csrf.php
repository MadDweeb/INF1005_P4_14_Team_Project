<?php
/**
 * src/helpers/csrf.php
 *
 * CSRF (Cross-Site Request Forgery) token helpers.
 *
 * Every HTML form that submits via POST must include a CSRF token.
 * Every POST handler must verify the token before processing the request.
 *
 * Usage in a view template:
 *   <form method="POST" action="/cart/add">
 *       <?= csrfInput() ?>
 *       ... other inputs ...
 *   </form>
 *
 * Usage in a controller POST handler:
 *   verifyCsrf(); // Exits with 403 if token is invalid or missing
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate a CSRF token and store it in the session.
 * Returns the existing token if one has already been generated this session.
 *
 * @return string  A 64-character hex token.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden input field containing the current CSRF token.
 * Drop this call inside every POST form.
 *
 * @return string  HTML <input> element string.
 */
function csrfInput(): string
{
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify the CSRF token submitted in a POST request.
 * Terminates the request with HTTP 403 if the token is missing or does not match.
 *
 * Uses hash_equals() for timing-safe comparison to prevent timing attacks.
 */
function verifyCsrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    $stored    = $_SESSION['csrf_token'] ?? '';

    if (empty($stored) || !hash_equals($stored, $submitted)) {
        http_response_code(403);
        die('Invalid security token. Please go back and try again.');
    }
}