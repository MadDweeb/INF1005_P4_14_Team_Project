<!-- Accessibility Widget -->
<div id="accessibilityWidget"
     class="accessibility-widget"
     role="dialog"
     aria-modal="true"
     aria-labelledby="accessibilityWidgetTitle">

    <!-- Header -->
    <div class="accessibility-widget__header">
        <h2 class="accessibility-widget__title" id="accessibilityWidgetTitle">
            <i class="fas fa-universal-access" aria-hidden="true"></i>
            Accessibility Options
        </h2>
        <div class="accessibility-widget__header-actions">
            <!-- <label for="accessibilityLang" class="accessibility-lang">
                <i class="fas fa-globe" aria-hidden="true"></i>
                <select id="accessibilityLang" aria-label="Change language">
                    <option value="en">EN</option>
                    <option value="zh">&#x4E2D;&#x6587;</option>
                    <option value="ms">BM</option>
                    <option value="ta">&#x0BA4;&#x0BAE;&#x0BBF;&#x0BB4;&#x0BCD;</option>
                </select>
            </label> -->
            <button class="accessibility-widget__close" aria-label="Close accessibility options" id="accessibilityWidgetClose">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Scrollable body -->
    <div class="accessibility-widget__body" data-lenis-prevent="true">

        <!-- Content -->
        <section class="accessibility-section" aria-labelledby="accessibilitySecContent">
            <h3 class="accessibility-section__title" id="accessibilitySecContent">Content</h3>
            <div class="accessibility-grid">

                <!-- Bigger Text - 3-level slider -->
                <div class="accessibility-card accessibility-card--full" role="group" aria-label="Bigger Text">
                    <p class="accessibility-card__group-label"><i class="fas fa-text-height" aria-hidden="true" style="margin-right:6px;color:var(--accent)"></i>Bigger Text</p>
                    <div class="accessibility-font-slider" role="radiogroup" aria-label="Text size level">
                        <button class="accessibility-font-slider__option" data-action="font-level" data-level="1" role="radio" aria-checked="false">
                            <span class="accessibility-font-slider__icon">A</span>
                            <span class="accessibility-font-slider__label">Level 1</span>
                        </button>
                        <button class="accessibility-font-slider__option" data-action="font-level" data-level="2" role="radio" aria-checked="false">
                            <span class="accessibility-font-slider__icon" style="font-size:20px">A</span>
                            <span class="accessibility-font-slider__label">Level 2</span>
                        </button>
                        <button class="accessibility-font-slider__option" data-action="font-level" data-level="3" role="radio" aria-checked="false">
                            <span class="accessibility-font-slider__icon" style="font-size:24px">A</span>
                            <span class="accessibility-font-slider__label">Level 3</span>
                        </button>
                    </div>
                </div>

                <button class="accessibility-card" data-action="bigger-cursor" aria-pressed="false">
                    <i class="fas fa-arrow-pointer" aria-hidden="true"></i>
                    <span>Bigger Cursor</span>
                </button>
                <button class="accessibility-card" data-action="hide-images" aria-pressed="false">
                    <i class="fas fa-eye-slash" aria-hidden="true"></i>
                    <span>Hide Images</span>
                </button>
                <button class="accessibility-card" data-action="readable-fonts" aria-pressed="false">
                    <i class="fas fa-font" aria-hidden="true"></i>
                    <span>Readable Fonts</span>
                </button>
                <button class="accessibility-card" data-action="reduce-motion" aria-pressed="false">
                    <i class="fas fa-pause-circle" aria-hidden="true"></i>
                    <span>Reduce Motion</span>
                </button>

            </div>
        </section>

        <!-- Colors -->
        <section class="accessibility-section" aria-labelledby="accessibilitySecColors">
            <h3 class="accessibility-section__title" id="accessibilitySecColors">Colors</h3>
            <div class="accessibility-grid">

                <button class="accessibility-card" data-action="invert" aria-pressed="false">
                    <i class="fas fa-circle-half-stroke" aria-hidden="true"></i>
                    <span>Invert Colors</span>
                </button>
                <button class="accessibility-card" data-action="brightness" data-value="brighter" aria-pressed="false">
                    <i class="fas fa-sun" aria-hidden="true"></i>
                    <span>Brightness</span>
                    <em class="accessibility-card__hint">Brighter</em>
                </button>
                <button class="accessibility-card" data-action="brightness" data-value="dimmer" aria-pressed="false">
                    <i class="fas fa-moon" aria-hidden="true"></i>
                    <span>Brightness</span>
                    <em class="accessibility-card__hint">Dimmer</em>
                </button>
                <button class="accessibility-card" data-action="contrast" data-value="higher" aria-pressed="false">
                    <i class="fas fa-adjust" aria-hidden="true"></i>
                    <span>Contrast</span>
                    <em class="accessibility-card__hint">Higher</em>
                </button>
                <button class="accessibility-card" data-action="contrast" data-value="lower" aria-pressed="false">
                    <i class="fas fa-circle" aria-hidden="true"></i>
                    <span>Contrast</span>
                    <em class="accessibility-card__hint">Lower</em>
                </button>

                <!-- Color Filters - full width -->
                <div class="accessibility-card accessibility-card--full" role="group" aria-labelledby="accessibilityColorFiltersLabel">
                    <p class="accessibility-card__group-label" id="accessibilityColorFiltersLabel">Color Filters</p>
                    <div class="accessibility-filter-row">
                        <button class="accessibility-filter-btn" data-action="color-filter" data-filter="grayscale"
                            aria-pressed="false">
                            <span class="accessibility-filter-dot accessibility-filter-dot--gray" aria-hidden="true"></span>
                            Grayscale
                        </button>
                        <button class="accessibility-filter-btn" data-action="color-filter" data-filter="red-green"
                            aria-pressed="false">
                            <span class="accessibility-filter-dot accessibility-filter-dot--red-green" aria-hidden="true"></span>
                            Red/Green
                        </button>
                    </div>
                    <div class="accessibility-filter-row">
                        <button class="accessibility-filter-btn" data-action="color-filter" data-filter="blue-yellow"
                            aria-pressed="false">
                            <span class="accessibility-filter-dot accessibility-filter-dot--blue-yellow" aria-hidden="true"></span>
                            Blue/Yellow
                        </button>
                        <button class="accessibility-filter-btn" data-action="color-filter" data-filter="green-red"
                            aria-pressed="false">
                            <span class="accessibility-filter-dot accessibility-filter-dot--green-red" aria-hidden="true"></span>
                            Green/Red
                        </button>
                    </div>
                </div>

            </div>
        </section>

        <!-- Navigation -->
        <section class="accessibility-section" aria-labelledby="accessibilitySecNav">
            <h3 class="accessibility-section__title" id="accessibilitySecNav">Navigation</h3>
            <div class="accessibility-grid">
                <button class="accessibility-card" data-action="reading-line" aria-pressed="false">
                    <i class="fas fa-grip-lines" aria-hidden="true"></i>
                    <span>Reading Line</span>
                </button>
                <button class="accessibility-card" data-action="highlight-links" aria-pressed="false">
                    <i class="fas fa-link" aria-hidden="true"></i>
                    <span>Highlight Links</span>
                </button>
            </div>
        </section>

    </div><!-- /.accessibility-widget__body -->

    <!-- Footer -->
    <div class="accessibility-widget__footer">
        <button class="accessibility-reset-btn" id="accessibilityReset">
            <i class="fas fa-rotate-left" aria-hidden="true"></i>
            Reset Settings
        </button>
    </div>

</div>

<!-- Reading line overlay (created by JS, defined here for clarity) -->

<!-- Floating persistent toggle (always-visible for mouse users) -->
<button class="accessibility-fab" aria-label="Open accessibility options" aria-haspopup="true" aria-expanded="false"
    aria-controls="accessibilityWidget" id="accessibilityFab">
    <i class="fas fa-universal-access" aria-hidden="true"></i>
</button>