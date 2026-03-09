<?php
/**
 * src/models/User.php
 *
 * User model — account creation, login verification, profile management.
 *
 * SECURITY NOTES:
 *   - Passwords MUST be hashed with password_hash($pass, PASSWORD_BCRYPT) before storing.
 *   - Use password_verify($plain, $hash) to verify — never compare plaintext strings.
 *   - Avoid SELECT * where possible; never return the password hash to view templates.
 *   - Always use prepared statements — user-supplied email/username are attack vectors.
 *
 * Attributes:
 *   user_id    — Auto-increment primary key
 *   username   — Public display name (unique)
 *   email      — Login identifier (unique)
 *   password   — Bcrypt hash (never store or log plaintext)
 *   role       — 'customer' (default) or 'admin'
 *   created_at — Registration timestamp
 *   updated_at — Last profile update timestamp
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
     *
     * TODO: In the controller, check that the email is not already registered
     *       before calling this method (to provide a friendly duplicate error).
     */
    public function create(array $data): int
    {
        // TODO: INSERT prepared statement.
        // Reminder: $data['password'] must already be a bcrypt hash.
        //   $data['password'] = password_hash($rawPassword, PASSWORD_BCRYPT);
        return 0;
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
     *
     * TODO: If updating password, re-hash the new value before passing it here.
     * TODO: If updating email, check uniqueness first.
     */
    public function update(int $id, array $data): bool
    {
        // TODO: Build a dynamic SET clause.
        return false;
    }
}