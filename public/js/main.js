/*
 * public/js/main.js
 * Base JavaScript for KeyForge.
 */

document.addEventListener('DOMContentLoaded', function () {
    // TODO: Implement cart interactions (add-to-cart buttons, quantity updates).
    // TODO: Implement product filtering / search on the catalogue page.
    // TODO: Implement client-side form validation (login, register, checkout).
});

document.addEventListener('DOMContentLoaded', () => {

    // -- Mobile Menu Toggle -------------------------------------------------------
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        const icon = menuToggle.querySelector('i');
        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            const isOpen = mainNav.classList.contains('active');
            icon.classList.toggle('fa-bars', !isOpen);
            icon.classList.toggle('fa-times', isOpen);
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    // -- Accessibility Widget -----------------------------------------------------
    const widget = document.getElementById('accessibilityWidget');
    const fabBtn = document.getElementById('accessibilityFab');
    const skipTrigger = document.getElementById('accessibilityWidgetTrigger');
    const closeBtn = document.getElementById('accessibilityWidgetClose');
    const resetBtn = document.getElementById('accessibilityReset');
    const langSelect = document.getElementById('accessibilityLang');

    if (!widget) return;

    // -- State --------------------------------------------------------------------
    // fontLevel: 0 = off, 1/2/3 = size levels
    // brightness/contrast: null | 'brighter' | 'dimmer' / 'higher' | 'lower'
    // colorFilter: null | 'grayscale' | 'red-green' | 'blue-yellow' | 'green-red'
    const state = {
        fontLevel: 0,
        biggerCursor: false,
        hideImages: false,
        readableFonts: false,
        invert: false,
        brightness: null,
        contrast: null,
        colorFilter: null,
        readingLine: false,
        highlightLinks: false,
    };

    const STORAGE_KEY = 'keyforge-accessibility';

    // -- Persist & restore --------------------------------------------------------
    function saveState() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    }

    function loadState() {
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
            if (saved) Object.assign(state, saved);
        } catch (_) { }
    }

    // -- Apply all state to the DOM -----------------------------------------------
    const html = document.documentElement;
    const body = document.body;

    // Reading line element (created once)
    const readingLineEl = document.createElement('div');
    readingLineEl.className = 'accessibility-reading-line';
    readingLineEl.setAttribute('aria-hidden', 'true');
    document.body.appendChild(readingLineEl);

    function applyState() {
        // Font size - increase on <html> so rem/em-based styles scale
        html.classList.remove('accessibility-font-1', 'accessibility-font-2', 'accessibility-font-3');
        if (state.fontLevel > 0) html.classList.add(`accessibility-font-${state.fontLevel}`);

        body.classList.toggle('accessibility-bigger-cursor', state.biggerCursor);
        body.classList.toggle('accessibility-hide-images', state.hideImages);
        body.classList.toggle('accessibility-readable-fonts', state.readableFonts);
        body.classList.toggle('accessibility-highlight-links', state.highlightLinks);

        // Reading line visibility
        readingLineEl.hidden = !state.readingLine;

        // CSS filters applied to <html> (doesn't break position:fixed inside body)
        const filters = [];
        if (state.invert) filters.push('invert(1)');
        if (state.brightness === 'brighter') filters.push('brightness(1.35)');
        if (state.brightness === 'dimmer') filters.push('brightness(0.65)');
        if (state.contrast === 'higher') filters.push('contrast(1.6)');
        if (state.contrast === 'lower') filters.push('contrast(0.6)');
        if (state.colorFilter === 'grayscale') filters.push('grayscale(1)');
        // Colour-blindness simulations (hue-rotate approximations)
        if (state.colorFilter === 'red-green') filters.push('sepia(0.4) hue-rotate(20deg)');
        if (state.colorFilter === 'blue-yellow') filters.push('sepia(0.4) hue-rotate(200deg)');
        if (state.colorFilter === 'green-red') filters.push('sepia(0.4) hue-rotate(90deg)');

        html.style.filter = filters.join(' ') || '';
    }

    // -- Sync aria-pressed on all widget buttons ----------------------------------
    function syncUI() {
        // Font size slider
        widget.querySelectorAll('[data-action="font-level"]').forEach(btn => {
            const level = parseInt(btn.dataset.level, 10);
            const isActive = state.fontLevel === level;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-checked', String(isActive));
        });
        // Toggle cards
        const map = {
            'bigger-cursor': state.biggerCursor,
            'hide-images': state.hideImages,
            'readable-fonts': state.readableFonts,
            'invert': state.invert,
            'reading-line': state.readingLine,
            'highlight-links': state.highlightLinks,
        };
        Object.entries(map).forEach(([action, active]) => {
            widget.querySelectorAll(`[data-action="${action}"]`).forEach(btn => {
                btn.setAttribute('aria-pressed', String(active));
            });
        });
        // Brightness
        widget.querySelectorAll('[data-action="brightness"]').forEach(btn => {
            btn.setAttribute('aria-pressed', String(btn.dataset.value === state.brightness));
        });
        // Contrast
        widget.querySelectorAll('[data-action="contrast"]').forEach(btn => {
            btn.setAttribute('aria-pressed', String(btn.dataset.value === state.contrast));
        });
        // Color filters
        widget.querySelectorAll('[data-action="color-filter"]').forEach(btn => {
            btn.setAttribute('aria-pressed', String(btn.dataset.filter === state.colorFilter));
        });
    }

    function update() {
        applyState();
        syncUI();
        saveState();
    }

    // -- Widget open/close --------------------------------------------------------
    function openWidget() {
        widget.hidden = false;
        [fabBtn, skipTrigger].forEach(b => b && b.setAttribute('aria-expanded', 'true'));
        closeBtn && closeBtn.focus();
    }

    function closeWidget(returnTo) {
        widget.classList.add('is-closing');

        widget.addEventListener('animationend', () => {
            widget.hidden = true;
            widget.classList.remove('is-closing'); 
        }, { once: true }); 

        [fabBtn, skipTrigger].forEach(b => b && b.setAttribute('aria-expanded', 'false'));
        (returnTo || fabBtn) && (returnTo || fabBtn).focus();
    }

    [fabBtn, skipTrigger].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                if (widget.hidden) {
                    openWidget();
                } else {
                    closeWidget(btn); 
                }
            });
        }
    });

    closeBtn && closeBtn.addEventListener('click', () => closeWidget(fabBtn));

    widget.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeWidget(fabBtn); return; }
        if (e.key !== 'Tab') return;
        const focusable = Array.from(
            widget.querySelectorAll('button:not([disabled]), select, [tabindex]:not([tabindex="-1"])')
        );
        const first = focusable[0], last = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault(); last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault(); first.focus();
        }
    });

    // -- Button handlers ----------------------------------------------------------
    widget.addEventListener('click', e => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;

        const action = btn.dataset.action;

        switch (action) {
            case 'font-level': {
                const level = parseInt(btn.dataset.level, 10);
                // Clicking the active level turns it off; otherwise set to that level
                state.fontLevel = (state.fontLevel === level) ? 0 : level;
                break;
            }
            case 'bigger-cursor':
                state.biggerCursor = !state.biggerCursor; break;
            case 'hide-images':
                state.hideImages = !state.hideImages; break;
            case 'readable-fonts':
                state.readableFonts = !state.readableFonts; break;
            case 'invert':
                state.invert = !state.invert; break;
            case 'brightness': {
                const val = btn.dataset.value;
                // Toggle off if already active; brightness options are mutually exclusive
                state.brightness = (state.brightness === val) ? null : val;
                break;
            }
            case 'contrast': {
                const val = btn.dataset.value;
                state.contrast = (state.contrast === val) ? null : val;
                break;
            }
            case 'color-filter': {
                const filter = btn.dataset.filter;
                // Radio behaviour - clicking active one deselects
                state.colorFilter = (state.colorFilter === filter) ? null : filter;
                break;
            }
            case 'reading-line':
                state.readingLine = !state.readingLine; break;
            case 'highlight-links':
                state.highlightLinks = !state.highlightLinks; break;
        }

        update();
    });

    // -- Reading line mouse tracking ----------------------------------------------
    document.addEventListener('mousemove', e => {
        if (!state.readingLine) return;
        readingLineEl.style.top = e.clientY + 'px';
    });

    // -- Reset --------------------------------------------------------------------
    resetBtn && resetBtn.addEventListener('click', () => {
        Object.assign(state, {
            fontLevel: 0, biggerCursor: false, hideImages: false,
            readableFonts: false, invert: false, brightness: null,
            contrast: null, colorFilter: null, readingLine: false,
            highlightLinks: false,
        });
        update();
    });

    // -- Language selector --------------------------------------------------------
    langSelect && langSelect.addEventListener('change', () => {
        const lang = langSelect.value;
        html.setAttribute('lang', lang);
        localStorage.setItem('keyforge-lang', lang);
        // Full translation requires server-side support.
        // This updates the lang attribute now (helps screen reader pronunciation).
    });

    // Restore saved language
    const savedLang = localStorage.getItem('keyforge-lang');
    if (savedLang && langSelect) {
        langSelect.value = savedLang;
        html.setAttribute('lang', savedLang);
    }

    // -- Boot: restore saved state ------------------------------------------------
    loadState();
    update();
});
