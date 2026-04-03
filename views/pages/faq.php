<?php

$pageTitle = 'FAQ';
$bodyClass = 'theme-dark';
ob_start();
$extraCss = ['/css/faq.css'];
$extraJs = ['/js/faq.js'];
?>

<div class="faq-page">
    <section class="faq-header" aria-labelledby="faq-heading">
        <h1 id="faq-heading">Frequently Asked Questions</h1>
        <p>Curious about personalizing your typing experience? We've got you covered.</p>
    </section>

    <div class="faq-container">
        <div class="faq-item">
            <h2>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-1" id="faq-question-1">
                    How do I know which switch is right for me?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="faq-answer-1" class="faq-answer">
                <p>We recommend checking out our detailed specifications for each switch. <strong>Linear
                        switches</strong>
                    are smooth, <strong>Tactile</strong> have a "bump", and <strong>Clicky</strong> make an audible
                    sound.
                    You can also use our interactive <a href="/customizer"
                        style="color: var(--accent); text-decoration: underline; font-weight: 700;">Customizer</a> to
                    get your own customized switch!</p>
            </div>
        </div>

        <div class="faq-item">
            <h2>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-2" id="faq-question-2">
                    Can I mix and match different switches on one keyboard?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="faq-answer-2" class="faq-answer">
                <p>Absolutely! Many enthusiasts use different switches for their spacebar or modifier keys. Since we
                    sell
                    switches in packs, you can customize your layout exactly how you want it.</p>
            </div>
        </div>

        <div class="faq-item">
            <h2>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-3" id="faq-question-3">
                    What is the typical delivery time for orders?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="faq-answer-3" class="faq-answer">
                <p>Orders are typically processed within 1-2 business days. Standard shipping usually takes 3-5 business
                    days for domestic orders, depending on your location.</p>
            </div>
        </div>

        <div class="faq-item">
            <h2>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-4" id="faq-question-4">
                    Do you ship internationally?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="faq-answer-4" class="faq-answer">
                <p>Yes, we ship to most countries worldwide! International shipping times vary depending on the
                    destination
                    and local customs processing, typically ranging from 7-21 business days.</p>
            </div>
        </div>

        <div class="faq-item">
            <h2>
                <button type="button" aria-expanded="false" aria-controls="faq-answer-5" id="faq-question-5">
                    Are your switches compatible with my mechanical keyboard?
                    <i class="fas fa-plus faq-icon-fa" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="faq-answer-5" class="faq-answer">
                <p>Our switches are standard MX-compatible and fit most mechanical keyboards that feature hot-swap
                    sockets
                    or support custom soldering. If you're unsure about your specific model, feel free to contact our
                    support team!</p>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>