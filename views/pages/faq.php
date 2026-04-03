<?php

$pageTitle = 'FAQ';
$bodyClass = 'theme-dark';
ob_start();
$extraCss = ['/css/faq.css'];
$extraJs = ['/js/faq.js'];
?>

<div class="faq-page">
    <header class="faq-header" aria-labelledby="faq-heading">
        <h1 id="faq-heading">Frequently Asked Questions</h1>
        <p>Curious about personalizing your typing experience? We've got you covered.</p>
    </header>

    <div class="faq-container">
        <section class="faq-item" aria-labelledby="faq-question-1">
            <h3>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-1" id="faq-question-1">
                    How do I know which switch is right for me?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h3>
            <article id="faq-answer-1" class="faq-answer" role="region" aria-labelledby="faq-question-1">
                <p>We recommend checking out our detailed specifications for each switch. <strong>Linear
                        switches</strong>
                    are smooth, <strong>Tactile</strong> have a "bump", and <strong>Clicky</strong> make an audible
                    sound.
                    You can also use our interactive <a href="/customizer"
                        style="color: var(--accent); text-decoration: underline; font-weight: 700;">Customizer</a> to
                    get your own customized switch!</p>
            </article>
        </section>

        <section class="faq-item" aria-labelledby="faq-question-2">
            <h3>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-2" id="faq-question-2">
                    Can I mix and match different switches on one keyboard?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h3>
            <article id="faq-answer-2" class="faq-answer" role="region" aria-labelledby="faq-question-2">
                <p>Absolutely! Many enthusiasts use different switches for their spacebar or modifier keys. Since we
                    sell
                    switches in packs, you can customize your layout exactly how you want it.</p>
            </article>
        </section>

        <section class="faq-item" aria-labelledby="faq-question-3">
            <h3>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-3" id="faq-question-3">
                    What is the typical delivery time for orders?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h3>
            <article id="faq-answer-3" class="faq-answer" role="region" aria-labelledby="faq-question-3">
                <p>Orders are typically processed within 1-2 business days. Standard shipping usually takes 3-5 business
                    days for domestic orders, depending on your location.</p>
            </article>
        </section>

        <section class="faq-item" aria-labelledby="faq-question-4">
            <h3>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-4" id="faq-question-4">
                    Do you ship internationally?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h3>
            <article id="faq-answer-4" class="faq-answer" role="region" aria-labelledby="faq-question-4">
                <p>Yes, we ship to most countries worldwide! International shipping times vary depending on the
                    destination
                    and local customs processing, typically ranging from 7-21 business days.</p>
            </article>
        </section>

        <section class="faq-item" aria-labelledby="faq-question-5">
            <h3>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-5" id="faq-question-5">
                    Are your switches compatible with my mechanical keyboard?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h3>
            <article id="faq-answer-5" class="faq-answer" role="region" aria-labelledby="faq-question-5">
                <p>Our switches are standard MX-compatible and fit most mechanical keyboards that feature hot-swap
                    sockets
                    or support custom soldering. If you're unsure about your specific model, feel free to contact our
                    support team!</p>
            </article>
        </section>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>