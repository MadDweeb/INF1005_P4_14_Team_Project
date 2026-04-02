<?php
/**
 * src/models/User.php
 *
 * User model - account creation, login verification, profile management.
 *
 * SECURITY NOTES:
 *   - Passwords MUST be hashed with password_hash($pass, PASSWORD_BCRYPT) before storing.
 *   - Use password_verify($plain, $hash) to verify - never compare plaintext strings.
 *   - Avoid SELECT * where possible; never return the password hash to view templates.
 *   - Always use prepared statements - user-supplied email/username are attack vectors.
 *
 * Attributes:
 *   user_id    - Auto-increment primary key
 *   username   - Public display name (unique)
 *   email      - Login identifier (unique)
 *   password   - Bcrypt hash (never store or log plaintext)
 *   role       - 'customer' (default) or 'admin'
 *   failed_login_attempts - Number of consecutive failed login attempts
 *   locked_until - Timestamp until login is blocked (null when unlocked)
 *   created_at - Registration timestamp
 *   updated_at - Last profile update timestamp
 */

class User
{
    private PDO    $pdo;
    private string $table = 'users';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Find a user by their email address.
     * Used during login to retrieve the stored bcrypt hash for verification.
     *
     * @param  string      $email  The email address to look up.
     * @return array|false         User row, or false if not found.
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Create a new user account.
     *
     * @param  array $data  Must include: username, email, password (already bcrypt-hashed).
     * @return int          The new user_id, or 0 on failure.
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (username, email, password)
             VALUES (:username, :email, :password)"
        );
        $stmt->execute([
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Retrieve a user by their primary key.
     * Use this to populate session data and profile pages.
     *
     * @param  int         $id  The user_id.
     * @return array|false      User row, or false if not found.
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT user_id, username, email, role, created_at
             FROM {$this->table}
             WHERE user_id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Update a user's profile.
     *
     * @param  int   $id    The user_id to update.
     * @param  array $data  Fields to update (username, email, or a new hashed password).
     * @return bool         True on success.
     */
    public function update(int $id, array $data): bool
    {
        $allowed = ['username', 'email', 'password'];
        $setClauses = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $setClauses[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE user_id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Increment failed login attempts for a user and return the new count.
     */
    public function incrementFailedLoginAttempts(int $userId): int
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table}
             SET failed_login_attempts = failed_login_attempts + 1
             WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $userId]);

        $countStmt = $this->pdo->prepare(
            "SELECT failed_login_attempts
             FROM {$this->table}
             WHERE user_id = :user_id
             LIMIT 1"
        );
        $countStmt->execute([':user_id' => $userId]);

        $result = $countStmt->fetch();
        return (int) ($result['failed_login_attempts'] ?? 0);
    }

    /**
     * Lock an account for one hour starting from current DB server time.
     */
    public function lockAccountForOneHour(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table}
             SET locked_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)
             WHERE user_id = :user_id"
        );

        return $stmt->execute([':user_id' => $userId]);
    }

    /**
     * Clear lockout state for a user.
     */
    public function clearLoginLockout(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table}
             SET failed_login_attempts = 0,
                 locked_until = NULL
             WHERE user_id = :user_id"
        );

        return $stmt->execute([':user_id' => $userId]);
    }

    /**
     * Return users and lockout status for admin dashboard tools.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getUsersWithLockoutStatus(): array
    {
        $stmt = $this->pdo->query(
            "SELECT user_id,
                    username,
                    email,
                    role,
                    failed_login_attempts,
                    locked_until,
                    CASE
                        WHEN locked_until IS NOT NULL AND locked_until > CURRENT_TIMESTAMP THEN 1
                        ELSE 0
                    END AS is_locked
             FROM {$this->table}
             ORDER BY is_locked DESC, failed_login_attempts DESC, username ASC"
        );

        return $stmt->fetchAll();
    }
}