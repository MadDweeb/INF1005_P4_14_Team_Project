<?php
/*
 * views/partials/footer.php
 *
 * TODO: Implement the site-wide footer partial.
 *
 * Suggested content:
 *   - Quick navigation links
 *   - Company contact details using <address>
 *   - Copyright notice
 *
 * ACCESSIBILITY:
 *   - Use <footer> as a landmark element
 *   - Use <nav aria-label="Footer navigation"> for footer links
 *   - Use <address> for contact information
 */

// TODO: Implement footer here
?>

<footer id="main-footer" class="main-footer" role="contentinfo">
    <div class="footer-top">
        <section class="newsletter" aria-labelledby="newsletter-heading">
            <h2 id="newsletter-heading">STAY IN THE LOOP</h2>
            <form action="/subscribe" method="POST" class="subscribe-form" aria-labelledby="newsletter-heading">
                <label for="email-subscribe" class="sr-only">Email address</label>
                <input type="email" name="email" id="email-subscribe" placeholder="Your email" required
                    autocomplete="email">
                <button type="submit">SIGN UP</button>
            </form>
        </section>

        <ul class="social-links">
            <li>
                <a href="#" aria-label="Instagram">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                </a>
            </li>
            <li>
                <a href="#" aria-label="Facebook">
                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                </a>
            </li>
            <li>
                <a href="#" aria-label="X (Twitter)">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>

    <hr class="footer-divider" aria-hidden="true">

    <div class="footer-giant-logo" aria-hidden="true">
        KEYFORGE
    </div>

    <nav class="footer-nav" aria-label="Footer navigation">
        <ul class="nav-left">
            <li><a href="/products">SWITCHES</a></li>
            <li><a href="/customizer">CUSTOMIZE</a></li>
            <li><a href="/about">ABOUT US</a></li>
        </ul>
        <ul class="nav-right">
            <li><a href="/contact">CONTACT</a></li>
            <li><a href="/faq">FAQ</a></li>
        </ul>
    </nav>
    <div class="footer-legal">
        <small>
            &copy; <?= date('Y') ?> KeyForge. All rights reserved. |
            <a href="/privacy">Privacy Policy</a> |
            <a href="/terms">Terms & Conditions</a>
        </small>
        <address>
            <p>hello@keyforge.example</p>
        </address>
    </div>
</footer>