/*
 * public/js/main.js
 * Base JavaScript for KeyForge.
 */

document.addEventListener('DOMContentLoaded', function () {
    // -- Smooth Scrolling (Lenis) -------------------------------------------------
    let lenis = null;
    let lenisRafId = null;

    function startLenis() {
        if (typeof Lenis === 'undefined' || lenis) return;

        lenis = new Lenis({ lerp: 0.08, smoothWheel: true, syncTouch: false });

        function raf(time) {
            lenis.raf(time);
            lenisRafId = requestAnimationFrame(raf);
        }
        lenisRafId = requestAnimationFrame(raf);
    }

    function stopLenis() {
        if (!lenis) return;

        lenis.destroy();
        lenis = null;

        if (lenisRafId) {
            cancelAnimationFrame(lenisRafId);
            lenisRafId = null;
        }
    }

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
        reduceMotion: false,
    };

    const STORAGE_KEY = 'keyforge-accessibility';

    function saveState() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    }

    function loadState() {
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
            if (saved) {
                Object.assign(state, saved);
            } else {
                state.reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            }
        } catch (_) { }
    }

    const html = document.documentElement;
    const body = document.body;

    const readingLineEl = document.createElement('div');
    readingLineEl.className = 'accessibility-reading-line';
    readingLineEl.setAttribute('aria-hidden', 'true');
    document.body.appendChild(readingLineEl);

    function applyState() {
        html.classList.remove('accessibility-font-1', 'accessibility-font-2', 'accessibility-font-3');
        if (state.fontLevel > 0) html.classList.add(`accessibility-font-${state.fontLevel}`);
        body.classList.toggle('accessibility-bigger-cursor', state.biggerCursor);
        body.classList.toggle('accessibility-hide-images', state.hideImages);
        body.classList.toggle('accessibility-readable-fonts', state.readableFonts);
        body.classList.toggle('accessibility-highlight-links', state.highlightLinks);
        body.classList.toggle('accessibility-reduce-motion', state.reduceMotion);

        if (state.reduceMotion) {
            stopLenis();
        } else {
            startLenis();
        }

        readingLineEl.hidden = !state.readingLine;
        const filters = [];
        if (state.invert) filters.push('invert(1)');
        if (state.brightness === 'brighter') filters.push('brightness(1.35)');
        if (state.brightness === 'dimmer') filters.push('brightness(0.65)');
        if (state.contrast === 'higher') filters.push('contrast(1.6)');
        if (state.contrast === 'lower') filters.push('contrast(0.6)');
        if (state.colorFilter === 'grayscale') filters.push('grayscale(1)');
        if (state.colorFilter === 'red-green') filters.push('sepia(0.4) hue-rotate(20deg)');
        if (state.colorFilter === 'blue-yellow') filters.push('sepia(0.4) hue-rotate(200deg)');
        if (state.colorFilter === 'green-red') filters.push('sepia(0.4) hue-rotate(90deg)');

        html.style.filter = filters.join(' ') || '';
    }

    // -- Sync aria-pressed on all widget buttons ----------------------------------
    function syncUI() {
        widget.querySelectorAll('[data-action="font-level"]').forEach(btn => {
            const level = parseInt(btn.dataset.level, 10);
            const isActive = state.fontLevel === level;
            const isTabTarget = (state.fontLevel === 0 && level === 1) || isActive;

            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-checked', String(isActive));
            btn.setAttribute('tabindex', isTabTarget ? '0' : '-1');
        });
        if (state.fontLevel === 0) {
            const first = widget.querySelector('[data-action="font-level"][data-level="1"]');
            if (first) first.setAttribute('tabindex', '0');
        }
        const map = {
            'bigger-cursor': state.biggerCursor,
            'hide-images': state.hideImages,
            'readable-fonts': state.readableFonts,
            'invert': state.invert,
            'reading-line': state.readingLine,
            'highlight-links': state.highlightLinks,
            'reduce-motion': state.reduceMotion,
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
        // Reduce motion
        widget.querySelectorAll('[data-action="reduce-motion"]').forEach(btn => {
            btn.setAttribute('aria-pressed', String(state.reduceMotion));
        });
    }

    function update() {
        applyState();
        syncUI();
        saveState();
    }

    // -- Widget open/close --------------------------------------------------------
    function openWidget() {
        widget.classList.remove('is-closing');
        widget.classList.add('is-open');
        [fabBtn, skipTrigger].forEach(b => b && b.setAttribute('aria-expanded', 'true'));
        closeBtn && closeBtn.focus();
    }

    function closeWidget(returnTo) {
        widget.classList.remove('is-open');
        widget.classList.add('is-closing');

        widget.addEventListener('animationend', () => {
            widget.classList.remove('is-closing');
        }, { once: true });

        [fabBtn, skipTrigger].forEach(b => b && b.setAttribute('aria-expanded', 'false'));
        (returnTo || fabBtn) && (returnTo || fabBtn).focus();
    }

    [fabBtn, skipTrigger].forEach(btn => {
        if (!btn) return;
        btn.addEventListener('click', () => {
            widget.classList.contains('is-open') ? closeWidget(btn) : openWidget();
        });
    });

    closeBtn && closeBtn.addEventListener('click', () => closeWidget(fabBtn));

    widget.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeWidget(fabBtn); return; }
        if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(e.key)) {
            const target = e.target.closest('[role="radio"]');
            if (target) {
                const group = target.closest('[role="radiogroup"]');
                const radios = Array.from(group.querySelectorAll('[role="radio"]'));
                const index = radios.indexOf(target);
                let nextIdx;

                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    nextIdx = (index + 1) % radios.length;
                } else {
                    nextIdx = (index - 1 + radios.length) % radios.length;
                }

                e.preventDefault();
                radios[nextIdx].focus();
                radios[nextIdx].click();
                return;
            }
        }

        if (e.key !== 'Tab') return;
        const focusable = Array.from(
            widget.querySelectorAll('button:not([disabled]), select, [tabindex]:not([tabindex="-1"])')
        ).filter(el => el.offsetParent !== null);

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
                state.colorFilter = (state.colorFilter === filter) ? null : filter;
                break;
            }
            case 'reading-line':
                state.readingLine = !state.readingLine; break;
            case 'highlight-links':
                state.highlightLinks = !state.highlightLinks; break;
            case 'reduce-motion':
                state.reduceMotion = !state.reduceMotion; break;
        }

        update();
    });

    document.addEventListener('mousemove', e => {
        if (!state.readingLine) return;
        readingLineEl.style.top = e.clientY + 'px';
    });

    resetBtn && resetBtn.addEventListener('click', () => {
        Object.assign(state, {
            fontLevel: 0, biggerCursor: false, hideImages: false,
            readableFonts: false, invert: false, brightness: null,
            contrast: null, colorFilter: null, readingLine: false,
            highlightLinks: false, reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        });
        update();
    });
    loadState();
    update();
});