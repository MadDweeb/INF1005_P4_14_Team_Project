<?php
/*
 * views/pages/404.php
 *
 * 404 - Page Not Found.
 * Uses the shared layout so the full site chrome (header, footer, nav) renders correctly.
 * http_response_code(404) is set by the router before this file is included.
 */

$pageTitle = '404 – Page Not Found';

$notFoundCssPath = __DIR__ . '/../../public/css/404.css';
$notFoundCssUrl  = '/css/404.css';

if (file_exists($notFoundCssPath)) {
    $notFoundCssUrl .= '?v=' . filemtime($notFoundCssPath);
}

$extraCss = [$notFoundCssUrl];

ob_start();
?>

<div class="not-found-page">
    <div class="not-found-inner">
        <span class="not-found-code" aria-hidden="true">404</span>

        <h1 class="not-found-title">Page Not Found</h1>

        <p class="not-found-message">
            The page you're looking for doesn't exist or may have been moved.
        </p>

        <div class="not-found-actions">
            <a href="/" class="btn-primary-link">Go Home</a>
            <a href="/products" class="btn-secondary-link">Browse Switches</a>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>
