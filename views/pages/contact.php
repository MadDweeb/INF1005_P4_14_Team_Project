<?php
/*
 * views/pages/contact.php
 *
 * Public contact page with a CSRF-protected form.
 * Expected variables on POST failure:
 *   $errors   (array)
 *   $oldInput (array)
 */

$pageTitle = 'Contact';
$extraCss  = ['/css/contact.css'];

$errors = $errors ?? [];
$oldInput = $oldInput ?? [];

ob_start();
?>

<div class="contact-page">
    <section class="contact-hero" aria-labelledby="contact-heading">
        <p class="eyebrow">Need help?</p>
        <h1 id="contact-heading">Contact KeyForge</h1>
        <p>Send us a message about switch recommendations, order help, or anything else on your mind.</p>
    </section>

    <div class="contact-grid">
        <section class="contact-card contact-info" aria-labelledby="contact-info-heading">
            <h2 id="contact-info-heading">Reach us directly</h2>
            <p>We usually reply within 1–2 business days.</p>

            <address>
                <strong>KeyForge Support</strong><br>
                hello@keyforge.example<br>
                Singapore
            </address>

            <div class="contact-note">
                <p><strong>Email:</strong> <a href="mailto:hello@keyforge.example">hello@keyforge.example</a></p>
                <p><strong>Hours:</strong> Monday to Friday, 9:00 AM to 6:00 PM</p>
            </div>
        </section>

        <section class="contact-card contact-form-wrap" aria-labelledby="contact-form-heading">
            <h2 id="contact-form-heading">Send a message</h2>

            <?php if (!empty($errors['general'])): ?>
                <div class="form-alert form-alert-error" role="alert">
                    <?= escapeHtml($errors['general']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/contact" class="contact-form" novalidate>
                <?= csrfInput() ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= escapeHtml($oldInput['name'] ?? '') ?>"
                        autocomplete="name"
                        maxlength="100"
                        required
                        <?= !empty($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>
                    >
                    <?php if (!empty($errors['name'])): ?>
                        <p id="name-error" class="field-error" role="alert"><?= escapeHtml($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= escapeHtml($oldInput['email'] ?? '') ?>"
                        autocomplete="email"
                        maxlength="255"
                        required
                        <?= !empty($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>
                    >
                    <?php if (!empty($errors['email'])): ?>
                        <p id="email-error" class="field-error" role="alert"><?= escapeHtml($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="<?= escapeHtml($oldInput['subject'] ?? '') ?>"
                        maxlength="150"
                        required
                        <?= !empty($errors['subject']) ? 'aria-invalid="true" aria-describedby="subject-error"' : '' ?>
                    >
                    <?php if (!empty($errors['subject'])): ?>
                        <p id="subject-error" class="field-error" role="alert"><?= escapeHtml($errors['subject']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="7"
                        maxlength="5000"
                        required
                        <?= !empty($errors['message']) ? 'aria-invalid="true" aria-describedby="message-error"' : '' ?>><?= escapeHtml($oldInput['message'] ?? '') ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                        <p id="message-error" class="field-error" role="alert"><?= escapeHtml($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="contact-submit">Send message</button>
            </form>
        </section>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
