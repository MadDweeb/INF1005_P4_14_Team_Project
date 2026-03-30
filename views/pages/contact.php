<?php
/*
 * views/pages/contact.php
 *
 * Contact page - uses shared layout via output buffer pattern.
 *
 * TODO: Implement contact form (name, email, message) with CSRF protection.
 * TODO: Add company address and email inside <address>.
 *
 * ACCESSIBILITY: Every form input must have a <label>. Use <address> for contact info.
 */

$pageTitle = 'Contact';
ob_start();
?>

<section class="contact-section" aria-labelledby="contact-heading">
    <h1 id="contact-heading">Contact Us</h1>

    <!-- TODO: Add a contact form and company contact details here -->
    <p>Contact page coming soon.</p>
</section>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
