<?php
/*
 * views/pages/privacy.php
 *
 * Privacy policy page for KeyForge.
 */

$pageTitle = 'Privacy Policy';
$extraCss  = ['/css/legal.css'];

ob_start();
?>

<div class="legal-page" id="legal-top">
    <header class="legal-hero" aria-labelledby="privacy-title">
        <p class="legal-kicker">Legal</p>
        <h1 id="privacy-title">Privacy Policy</h1>
        <p class="legal-meta">Last updated: April 3, 2026</p>
    </header>

    <nav class="legal-toc" aria-label="Privacy policy sections">
        <h2>On this page</h2>
        <ul>
            <li><a href="#collection">Information we collect</a></li>
            <li><a href="#usage">How we use information</a></li>
            <li><a href="#sharing">How we share information</a></li>
            <li><a href="#security">Data security</a></li>
            <li><a href="#choices">Your choices</a></li>
            <li><a href="#contact">Contact us</a></li>
        </ul>
    </nav>

    <article class="legal-content" aria-labelledby="privacy-title">
        <section id="collection" aria-labelledby="collection-heading">
            <h2 id="collection-heading">Information we collect</h2>
            <p>
                We collect information you provide directly, such as your email address when you submit forms,
                create an account, or subscribe to updates.
            </p>
            <p>
                We may also collect basic technical information (for example, browser type and page visits) to help
                improve the site experience.
            </p>
        </section>

        <section id="usage" aria-labelledby="usage-heading">
            <h2 id="usage-heading">How we use information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Respond to your messages and support requests.</li>
                <li>Process account and order related actions in the demo store flow.</li>
                <li>Send updates when you subscribe to the newsletter form.</li>
                <li>Improve accessibility, performance, and reliability of this website.</li>
            </ul>
        </section>

        <section id="sharing" aria-labelledby="sharing-heading">
            <h2 id="sharing-heading">How we share information</h2>
            <p>
                We do not sell personal information. We only share data with service providers needed to run project
                features, such as email delivery tools.
            </p>
            <p>
                This site is a school demonstration project. Please avoid submitting sensitive personal or financial
                details.
            </p>
        </section>

        <section id="security" aria-labelledby="security-heading">
            <h2 id="security-heading">Data security</h2>
            <p>
                We use reasonable safeguards to protect submitted information, including server-side validation and
                secure request handling.
            </p>
            <p>
                No system is perfectly secure, so we cannot guarantee absolute security of information transmitted
                online.
            </p>
        </section>

        <section id="choices" aria-labelledby="choices-heading">
            <h2 id="choices-heading">Your choices</h2>
            <p>
                You may contact us to request correction or deletion of information you submitted through the project.
                Newsletter preferences can also be managed by contacting us directly.
            </p>
        </section>

        <section id="contact" aria-labelledby="contact-heading">
            <h2 id="contact-heading">Contact us</h2>
            <p>
                For privacy questions, reach us at
                <a href="mailto:hello@keyforge.example">hello@keyforge.example</a>
                or visit the <a href="/contact">Contact page</a>.
            </p>
        </section>
    </article>

    <p class="legal-backtop-wrap">
        <a class="legal-backtop" href="#legal-top">Back to top</a>
    </p>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
