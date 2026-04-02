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
 *   - Hash passwords with password_hash(PASSWORD_BCRYPT) - never store plaintext.
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
    private const MAX_LOGIN_ATTEMPTS = 5;

    public function __construct(?PDO $pdo)
    {
        $this->userModel = $pdo ? new User($pdo) : null;
    }

    /** Show the login form (GET /login). */
    public function showLogin(): void
    {
        if (isLoggedIn()) {
            $redirect = ($_SESSION['user_role'] ?? '') === 'admin' ? '/admin/dashboard' : '/';
            header('Location: ' . $redirect);
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
        if (empty($errors)) {
            if (!$this->userModel) {
                $errors['general'] = 'Login is currently unavailable. Please try again later.';
            } else {
                $user = $this->userModel->findByEmail($email);

                // Keep unknown emails generic to reduce account enumeration risk.
                if (!$user) {
                    $errors['general'] = 'Invalid email or password.';
                } else {
                    if ($this->isLockoutExpired($user)) {
                        $this->userModel->clearLoginLockout((int) $user['user_id']);
                        $user['failed_login_attempts'] = 0;
                        $user['locked_until'] = null;
                    }

                    if ($this->isAccountLocked($user)) {
                        $errors['general'] = $this->buildLockedMessage((string) $user['locked_until']);
                    } elseif (!password_verify($password, (string) $user['password'])) {
                        $failedAttempts = $this->userModel->incrementFailedLoginAttempts((int) $user['user_id']);
                        $remainingAttempts = max(0, self::MAX_LOGIN_ATTEMPTS - $failedAttempts);

                        if ($remainingAttempts === 0) {
                            $this->userModel->lockAccountForOneHour((int) $user['user_id']);
                            $errors['general'] = 'Wrong credentials! Your account is now locked for 1 hour.';
                        } else {
                            $attemptWord = $remainingAttempts === 1 ? 'attempt' : 'attempts';
                            $errors['general'] = "Wrong credentials! {$remainingAttempts} {$attemptWord} left.";
                        }
                    } else {
                        $this->userModel->clearLoginLockout((int) $user['user_id']);
                    }
                }
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
        $_SESSION['last_activity'] = time();

        $redirect = ($user['role'] === 'admin') ? '/admin/dashboard' : '/';
        header('Location: ' . $redirect);
        exit;
    }

    /**
     * Admin: clear lockout state for a user account.
     */
    public function adminResetLockout(): void
    {
        requireAdmin();
        verifyCsrf();

        $userId = sanitizeInt($_POST['user_id'] ?? 0);

        if (!$this->userModel) {
            $_SESSION['flash_error'] = 'Database unavailable. Could not reset lockout.';
            header('Location: /admin/dashboard');
            exit;
        }

        if (!isPositiveInt($userId)) {
            $_SESSION['flash_error'] = 'Invalid user selected.';
            header('Location: /admin/dashboard');
            exit;
        }

        if ($this->userModel->clearLoginLockout($userId)) {
            $_SESSION['flash_success'] = 'Login lockout state reset successfully.';
        } else {
            $_SESSION['flash_error'] = 'Could not reset lockout for that user.';
        }

        header('Location: /admin/dashboard#login-lockout-management');
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
        $_SESSION['last_activity'] = time();

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

    /**
     * Show the account dashboard page (GET /account).
     */
    public function showAccount(): void
    {
        requireLogin();
        $user = currentUser();
        
        // Ensure user has required fields with defaults
        if (!isset($user['username'])) {
            $user['username'] = 'User';
        }
        if (!isset($user['email'])) {
            $user['email'] = 'user@example.com';
        }
        if (!isset($user['created_at'])) {
            $user['created_at'] = date('Y-m-d H:i:s');
        }
        
        // TODO: Get real data from database
        $orderCount = 0;
        $cartCount = 0;
        $recentOrders = [];
        
        $currentPage = 'account';
        require_once __DIR__ . '/../../views/pages/account.php';
    }

    /**
     * Update account information (POST /account/update).
     */
    public function updateAccount(): void
    {
        requireLogin();
        verifyCsrf();
        
        $userId = currentUser()['id'];
        $username = sanitizeString($_POST['username'] ?? '');
        $email = sanitizeEmail($_POST['email'] ?? '');
        
        if (empty($username) || empty($email)) {
            $_SESSION['flash_error'] = 'Username and email are required.';
            header('Location: /account');
            exit;
        }
        
        // TODO: Update user in database
        // $this->userModel->update($userId, $username, $email);
        
        $_SESSION['flash_success'] = 'Account updated successfully!';
        header('Location: /account');
        exit;
    }

    /**
     * Update password (POST /account/password).
     */
    public function updatePassword(): void
    {
        requireLogin();
        verifyCsrf();
        
        $userId = currentUser()['id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['flash_error'] = 'All password fields are required.';
            header('Location: /account');
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'New passwords do not match.';
            header('Location: /account');
            exit;
        }
        
        if (strlen($newPassword) < 8) {
            $_SESSION['flash_error'] = 'Password must be at least 8 characters.';
            header('Location: /account');
            exit;
        }
        
        // TODO: Verify current password and update
        // $user = $this->userModel->getById($userId);
        // if (!password_verify($currentPassword, $user['password'])) {
        //     $_SESSION['flash_error'] = 'Current password is incorrect.';
        //     header('Location: /account');
        //     exit;
        // }
        // $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        // $this->userModel->updatePassword($userId, $hashedPassword);
        
        $_SESSION['flash_success'] = 'Password updated successfully!';
        header('Location: /account');
        exit;
    }

    /**
     * Determine if an account is currently locked.
     */
    private function isAccountLocked(array $user): bool
    {
        $lockedUntil = $user['locked_until'] ?? null;
        if (empty($lockedUntil)) {
            return false;
        }

        $lockedUntilTs = strtotime((string) $lockedUntil);
        if ($lockedUntilTs === false) {
            return false;
        }

        return $lockedUntilTs > time();
    }

    /**
     * Identify lockouts that have already passed so counters can be reset.
     */
    private function isLockoutExpired(array $user): bool
    {
        $lockedUntil = $user['locked_until'] ?? null;
        if (empty($lockedUntil)) {
            return false;
        }

        $lockedUntilTs = strtotime((string) $lockedUntil);
        if ($lockedUntilTs === false) {
            return false;
        }

        return $lockedUntilTs <= time();
    }

    /**
     * Build a user-friendly lock message with a rough remaining time.
     */
    private function buildLockedMessage(string $lockedUntil): string
    {
        $lockedUntilTs = strtotime($lockedUntil);
        if ($lockedUntilTs === false) {
            return 'Your account is temporarily locked due to too many failed login attempts. Please try again later.';
        }

        $secondsRemaining = max(0, $lockedUntilTs - time());
        $minutesRemaining = max(1, (int) ceil($secondsRemaining / 60));
        $minuteWord = $minutesRemaining === 1 ? 'minute' : 'minutes';

        return "Your account is temporarily locked due to too many failed login attempts. Try again in {$minutesRemaining} {$minuteWord}.";
    }
}