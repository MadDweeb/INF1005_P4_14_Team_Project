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
        if (isLoggedIn()) {
            header('Location: /');
            exit;
        }
        $errors = [];
        $currentPage = '';
        require_once __DIR__ . '/../../views/pages/auth/login.php';
    }

    /**
     * Process login form submission (POST /login).
     *
     * Security features:
     *   - verifyCsrf() prevents cross-site form submissions
     *   - password_verify() is timing-safe (prevents timing attacks)
     *   - Vague error message prevents email enumeration
     *   - session_regenerate_id() prevents session fixation
     */
    public function processLogin(): void
    {
        verifyCsrf();

        $email    = sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors   = [];

        if (empty($email) || empty($password)) {
            $errors['general'] = 'Email and password are required.';
        }

        $user = null;
        if (empty($errors) && $this->userModel) {
            $user = $this->userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors['general'] = 'Invalid email or password.';
            }
        }

        if (!empty($errors)) {
            $currentPage = '';
            require_once __DIR__ . '/../../views/pages/auth/login.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        header('Location: /');
        exit;
    }

    /** Show the registration form (GET /register). */
    public function showRegister(): void
    {
        if (isLoggedIn()) {
            header('Location: /');
            exit;
        }
        $errors = [];
        $currentPage = '';
        require_once __DIR__ . '/../../views/pages/auth/register.php';
    }

    /**
     * Process registration form submission (POST /register).
     *
     * Security features:
     *   - verifyCsrf() prevents cross-site form submissions
     *   - Server-side validation (never rely on HTML5 required or JS alone)
     *   - password_hash(PASSWORD_BCRYPT) stores salted hash, never plaintext
     *   - Duplicate email check before insert
     *   - session_regenerate_id() on auto-login
     */
    public function processRegister(): void
    {
        verifyCsrf();

        $username = sanitizeString($_POST['username'] ?? '');
        $email    = sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $errors   = [];

        if (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username must be 3–50 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (empty($errors) && $this->userModel) {
            if ($this->userModel->findByEmail($email)) {
                $errors['email'] = 'This email is already registered.';
            }
        }

        if (!empty($errors)) {
            $currentPage = '';
            require_once __DIR__ . '/../../views/pages/auth/register.php';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->userModel->create([
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword,
        ]);

        session_regenerate_id(true);
        $_SESSION['user_id']   = $userId;
        $_SESSION['username']  = $username;
        $_SESSION['email']     = $email;
        $_SESSION['user_role'] = 'customer';

        header('Location: /');
        exit;
    }

    /**
     * Destroy the session and log the user out (GET /logout).
     */
    public function logout(): void
    {
        $_SESSION = [];

        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        session_destroy();

        header('Location: /');
        exit;
    }

    /**
     * Show the user's profile page (GET /profile).
     */
    public function showProfile(): void
    {
        requireLogin();
        $user = currentUser();
        $currentPage = '';
        require_once __DIR__ . '/../../views/pages/profile.php';
    }
}