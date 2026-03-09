<?php
/**
 * src/controllers/UserController.php
 *
 * Handles user registration, login, logout, and profile pages.
 *
 * Routed from public/index.php:
 *   GET  /login      → showLogin()
 *   POST /login      → processLogin()
 *   GET  /register   → showRegister()
 *   POST /register   → processRegister()
 *   GET  /logout     → logout()
 *   GET  /profile    → showProfile()  (requires login)
 *
 * NOTE: $pdo may be null if the database is not yet configured.
 *       POST handlers bail out early with a friendly message when DB is unavailable.
 *
 * SECURITY REMINDERS:
 *   - Validate CSRF token on every POST handler.
 *   - Sanitize and validate all inputs before using them.
 *   - Hash passwords with password_hash(PASSWORD_BCRYPT) — never store plaintext.
 *   - Call session_regenerate_id(true) after a successful login.
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/sanitize.php';

class UserController
{
    private ?User $userModel;

    public function __construct(?PDO $pdo)
    {
        $this->userModel = $pdo ? new User($pdo) : null;
    }

    /** Show the login form (GET /login). */
    public function showLogin(): void
    {
        /*
         * TODO:
         * - If isLoggedIn(), redirect to / and exit
         * - Set $currentPage = ''
         * - require_once views/pages/auth/login.php
         */
    }

    /**
     * Process login form submission (POST /login).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Sanitize and validate email and password fields.
     * TODO: 3. Find user by email: $this->userModel->findByEmail($email)
     * TODO: 4. Verify password: password_verify($rawPassword, $user['password'])
     * TODO: 5. On success: session_regenerate_id(true), set $_SESSION keys, redirect.
     * TODO: 6. On failure: set $errors['general'] and re-render the login form.
     */
    public function processLogin(): void
    {
        /*
         * TODO: Implement login logic (see docblock above).
         */
    }

    /** Show the registration form (GET /register). */
    public function showRegister(): void
    {
        /*
         * TODO:
         * - If isLoggedIn(), redirect to / and exit
         * - Set $currentPage = ''
         * - require_once views/pages/auth/register.php
         */
    }

    /**
     * Process registration form submission (POST /register).
     *
     * TODO: 1. verifyCsrf()
     * TODO: 2. Validate all fields.
     * TODO: 3. Check email is not already registered.
     * TODO: 4. Hash password and call $this->userModel->create().
     * TODO: 5. Auto-login and redirect.
     */
    public function processRegister(): void
    {
        /*
         * TODO: Implement registration logic (see docblock above).
         */
    }

    /**
     * Destroy the session and log the user out (GET /logout).
     *
     * TODO:
     * - Clear $_SESSION
     * - Delete the session cookie (session_get_cookie_params / setcookie)
     * - session_destroy()
     * - Redirect to /
     */
    public function logout(): void
    {
        /*
         * TODO: Implement logout logic (see docblock above).
         */
    }

    /**
     * Show the user's profile page (GET /profile).
     * TODO: Create views/pages/profile.php.
     * TODO: Allow users to update username, email, and password.
     */
    public function showProfile(): void
    {
        /*
         * TODO:
         * - requireLogin()
         * - $user = currentUser()
         * - require_once views/pages/profile.php
         */
    }
}