<?php
/*
 * views/layout/main.php
 *
 * TODO: Implement the shared HTML layout (master template).
 *
 * This file should define the full HTML document shell used by all pages.
 * Page-specific content is passed via $pageTitle and $pageContent variables
 * (using the output buffer pattern).
 *
 * Usage pattern in a view:
 *   $pageTitle = 'My Page';
 *   ob_start();
 *   // ... render page HTML ...
 *   $pageContent = ob_get_clean();
 *   require_once __DIR__ . '/../layout/main.php';
 *
 * ACCESSIBILITY CHECKLIST:
 *   - lang="en" on <html>
 *   - Unique, descriptive <title> per page
 *   - Skip-to-main link as the first focusable element
 *   - <main id="main-content"> matching the skip link href
 *   - <header>, <nav>, <main>, <footer> landmark elements
 */
?>

<!-- TODO: Implement layout here -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?>Keyboard Switch Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Montserrat:wght@900&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css">
    <?php if (!empty($extraCss)): ?>
        <?php foreach ((array)$extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
    <script src="/js/main.js" defer></script>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array)$extraJs as $jsFile): ?>
            <script src="<?= htmlspecialchars($jsFile) ?>" defer></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <!-- skip links -->
    <?php include __DIR__ . '/../partials/skip-links.php'; ?>
    <!-- header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main id="main-content" tabindex="-1" role="main">
        <?= $pageContent ?>
    </main>
    <!-- footer -->
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <!-- Accessibility Widget -->
    <?php include __DIR__ . '/../partials/accessibility.php'; ?>
</body>
</html>
