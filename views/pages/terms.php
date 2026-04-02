<?php
/*
 * views/pages/terms.php
 *
 * Terms and conditions page for KeyForge.
 */

$pageTitle = 'Terms and Conditions';
$extraCss  = ['/css/legal.css'];

ob_start();
?>

<div class="legal-page" id="legal-top">
    <header class="legal-hero" aria-labelledby="terms-title">
        <p class="legal-kicker">Legal</p>
        <h1 id="terms-title">Terms and Conditions</h1>
        <p class="legal-meta">Last updated: April 3, 2026</p>
    </header>

    <nav class="legal-toc" aria-label="Terms and conditions sections">
        <h2>On this page</h2>
        <ul>
            <li><a href="#acceptance">Acceptance of terms</a></li>
            <li><a href="#usage">Permitted use</a></li>
            <li><a href="#accounts">Accounts and submissions</a></li>
            <li><a href="#orders">Orders and payments</a></li>
            <li><a href="#liability">Limitation of liability</a></li>
            <li><a href="#changes">Changes to these terms</a></li>
            <li><a href="#contact">Contact details</a></li>
        </ul>
    </nav>

    <article class="legal-content" aria-labelledby="terms-title">
        <section id="acceptance" aria-labelledby="acceptance-heading">
            <h2 id="acceptance-heading">Acceptance of terms</h2>
            <p>
                By accessing or using this website, you agree to these Terms and Conditions. If you do not agree,
                please discontinue use of the website.
            </p>
        </section>

        <section id="usage" aria-labelledby="usage-heading">
            <h2 id="usage-heading">Permitted use</h2>
            <p>You agree to use this website lawfully and respectfully.</p>
            <ul>
                <li>Do not attempt to disrupt website availability or security.</li>
                <li>Do not submit harmful, abusive, or misleading content through forms.</li>
                <li>Do not misuse any project data, scripts, or assets.</li>
            </ul>
        </section>

        <section id="accounts" aria-labelledby="accounts-heading">
            <h2 id="accounts-heading">Accounts and submissions</h2>
            <p>
                You are responsible for the accuracy of details submitted through account, contact, checkout, and
                newsletter forms. You are also responsible for activity performed under your account.
            </p>
        </section>

        <section id="orders" aria-labelledby="orders-heading">
            <h2 id="orders-heading">Orders and payments</h2>
            <p>
                Product listings, stock, and pricing are shown for demonstration within this school project and may be
                changed without notice.
            </p>
            <p>
                No guarantee is made that every feature reflects a live commercial workflow.
            </p>
        </section>

        <section id="liability" aria-labelledby="liability-heading">
            <h2 id="liability-heading">Limitation of liability</h2>
            <p>
                This website is provided on an "as is" basis for educational demonstration. To the fullest extent
                permitted by law, we are not liable for any indirect or consequential loss arising from use of this
                site.
            </p>
        </section>

        <section id="changes" aria-labelledby="changes-heading">
            <h2 id="changes-heading">Changes to these terms</h2>
            <p>
                We may revise these terms over time. Updates will be reflected on this page with a revised
                "Last updated" date.
            </p>
        </section>

        <section id="contact" aria-labelledby="contact-heading">
            <h2 id="contact-heading">Contact details</h2>
            <p>
                If you have questions about these terms, contact us at
                <a href="mailto:hello@keyforge.example">hello@keyforge.example</a>
                or use the <a href="/contact">Contact page</a>.
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
