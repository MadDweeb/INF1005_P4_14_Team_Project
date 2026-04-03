<?php
// Pull one-time flash messages from session and clear them after reading.
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$mainCssVersion = @filemtime(__DIR__ . '/../../public/css/main.css') ?: time();
$adminCssVersion = @filemtime(__DIR__ . '/../../public/css/admin.css') ?: time();

/*
 * views/layout/admin.php
 *
 * Admin master template. Mirrors main.php but uses the admin header instead
 * of the public-facing site header.
 *
 * Usage pattern in an admin view:
 *   $pageTitle        = 'Admin - Products';
 *   $currentAdminPage = 'products';   // used to highlight the active nav link
 *   ob_start();
 *   // ... render page HTML ...
 *   $pageContent = ob_get_clean();
 *   require_once __DIR__ . '/../layout/admin.php';
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' | ' : '' ?>KeyForge Admin</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon-32x32.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Montserrat:wght@900&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css?v=<?= $mainCssVersion ?>">
    <link rel="stylesheet" href="/css/admin.css?v=<?= $adminCssVersion ?>">
    <?php if (!empty($extraCss)): ?>
        <?php foreach ((array) $extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array) $extraJs as $jsFile): ?>
            <script src="<?= htmlspecialchars($jsFile) ?>" defer></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="admin-body">
    <?php include __DIR__ . '/../partials/skip-links.php'; ?>

    <?php include __DIR__ . '/../partials/admin-header.php'; ?>

    <main id="main-content" tabindex="-1" class="admin-main">
        <?php if (!empty($flashSuccess) || !empty($flashError)): ?>
            <div class="flash-stack" aria-live="polite">
                <?php if (!empty($flashSuccess)): ?>
                    <div class="flash-banner flash-banner-success" role="status">
                        <?= htmlspecialchars((string) $flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($flashError)): ?>
                    <div class="flash-banner flash-banner-error" role="alert">
                        <?= htmlspecialchars((string) $flashError, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?= $pageContent ?>
    </main>

    <footer id="main-footer" tabindex="-1" class="admin-footer" style="background:#111111;">
        <small style="color:#ffffff;font-weight:700;">&copy; <?= date('Y') ?> KeyForge &#45; Admin Panel</small>
    </footer>

    <script>
        // Admin mobile menu toggle
        const adminToggle = document.querySelector('.admin-mobile-toggle');
        const adminNav    = document.getElementById('admin-nav');
        if (adminToggle && adminNav) {
            adminToggle.addEventListener('click', () => {
                const isOpen = adminNav.classList.toggle('open');
                adminToggle.setAttribute('aria-expanded', isOpen);
                adminToggle.setAttribute('aria-label', isOpen ? 'Close admin menu' : 'Open admin menu');
            });
        }
    </script>
</body>

</html>
