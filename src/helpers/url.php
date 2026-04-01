<?php
/**
 * src/helpers/url.php
 *
 * URL helpers for generating stable internal links and form actions.
 *
 * In production, APP_URL can be set (for example: https://your-domain.duckdns.org)
 * so POST forms always submit to the canonical origin and avoid HTTP->HTTPS
 * redirects that may drop request methods on some setups.
 */

/**
 * Return the configured application origin if APP_URL exists.
 *
 * @return string|null
 */
function configuredAppOrigin(): ?string
{
    $configured = trim((string) ($_ENV['APP_URL'] ?? ''));
    if ($configured === '') {
        return null;
    }

    return rtrim($configured, '/');
}

/**
 * Build an absolute URL to an internal path.
 *
 * If APP_URL is configured, it is always used as origin.
 * Otherwise this falls back to the current request host/scheme.
 */
function appUrl(string $path = '/'): string
{
    $normalizedPath = '/' . ltrim($path, '/');

    $origin = configuredAppOrigin();
    if ($origin !== null) {
        return $origin . $normalizedPath;
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '') {
        return $normalizedPath;
    }

    $httpsFromServer = !empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
    $httpsFromProxy = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    $scheme = ($httpsFromServer || $httpsFromProxy) ? 'https' : 'http';

    return $scheme . '://' . $host . $normalizedPath;
}
