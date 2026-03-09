<?php
/**
 * src/helpers/sanitize.php
 *
 * Input sanitization and validation helpers.
 *
 * IMPORTANT:
 *   - Sanitization prevents XSS in HTML output.
 *   - It does NOT replace PDO prepared statements for database safety.
 *   - Always use prepared statements for DB queries regardless of sanitization.
 *
 * Functions:
 *   escapeHtml($input)    → string  — safe for echoing into HTML
 *   sanitizeString($input)→ string  — stripped tags, trimmed
 *   sanitizeEmail($input) → string  — cleaned email address
 *   sanitizeInt($input)   → int     — cast to integer
 *   isPositiveInt($input) → bool    — validates a positive integer (e.g., product IDs)
 */

/**
 * Escape a string for safe output in HTML context.
 * Use this on EVERY piece of user-supplied content before echoing into HTML.
 * Prevents Cross-Site Scripting (XSS) attacks.
 *
 * @param  string $input  Raw user input.
 * @return string         HTML-safe string.
 */
function escapeHtml(string $input): string
{
    return htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Sanitize a plain text string.
 * Strips HTML tags and trims leading/trailing whitespace.
 * Suitable for names, search queries, and other plain-text fields.
 *
 * @param  string $input  Raw input.
 * @return string         Cleaned string.
 */
function sanitizeString(string $input): string
{
    return trim(strip_tags($input));
}

/**
 * Sanitize an email address.
 * Removes illegal characters but does NOT validate email format.
 * Follow up with filter_var($email, FILTER_VALIDATE_EMAIL) to validate.
 *
 * @param  string $input  Raw email input.
 * @return string         Cleaned email string.
 */
function sanitizeEmail(string $input): string
{
    return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
}

/**
 * Sanitize a value as an integer.
 * Suitable for product IDs, quantities, page numbers, etc.
 *
 * @param  mixed $input  Raw input (string or number).
 * @return int           Integer value.
 */
function sanitizeInt(mixed $input): int
{
    return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Validate that a value is a positive integer (≥ 1).
 * Use for URL parameters like /products/42 before passing to the model.
 *
 * @param  mixed $input  Value to check.
 * @return bool          True if the value is a positive integer.
 */
function isPositiveInt(mixed $input): bool
{
    return filter_var($input, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]) !== false;
}