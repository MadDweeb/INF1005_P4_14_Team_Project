<?php
/*
 * views/pages/about.php
 *
 * About Us page - uses shared layout via output buffer pattern.
 *
 * TODO: Replace placeholder text with real company content.
 *
 * ACCESSIBILITY: Single <h1>, sections with <h2>, <address> for contact info.
 */

$pageTitle = 'About Us';
ob_start();
?>

<section class="about-section" aria-labelledby="about-heading">
    <h1 id="about-heading">About KeyForge</h1>

    <!-- TODO: Add company mission, story, and team member details here -->
    <p>About page coming soon.</p>
</section>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
