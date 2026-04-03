// -- Featured Carousel Logic ----------------------------------------------------
const carouselTrack = document.getElementById('featuredCarouselTrack');
if (carouselTrack) {
    const items = Array.from(carouselTrack.querySelectorAll('.carousel-item'));
    const btnPrev = document.querySelector('.carousel-nav.left');
    const btnNext = document.querySelector('.carousel-nav.right');

    if (items.length > 0) {
        let activeIndex = Math.floor(items.length / 2);

        function updateCarousel() {
            items.forEach((item, index) => {
                item.className = 'carousel-item';
                let diff = index - activeIndex;

                if (diff > Math.floor(items.length / 2)) {
                    diff -= items.length;
                } else if (diff < -Math.floor(items.length / 2)) {
                    diff += items.length;
                }

                if (diff === 0) {
                    item.classList.add('active');
                    item.setAttribute('aria-hidden', 'false');
                    item.querySelectorAll('a').forEach(a => a.setAttribute('tabindex', '0'));
                } else {
                    item.setAttribute('aria-hidden', 'true');
                    item.querySelectorAll('a').forEach(a => a.setAttribute('tabindex', '-1'));

                    if (diff === -1) {
                        item.classList.add('prev-1');
                    } else if (diff === 1) {
                        item.classList.add('next-1');
                    } else if (diff === -2) {
                        item.classList.add('prev-2');
                    } else if (diff === 2) {
                        item.classList.add('next-2');
                    }
                }
            });
        }

        btnPrev?.addEventListener('click', () => {
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            updateCarousel();
        });

        btnNext?.addEventListener('click', () => {
            activeIndex = (activeIndex + 1) % items.length;
            updateCarousel();
        });

        items.forEach((item, index) => {
            item.addEventListener('click', (e) => {
                if (!item.classList.contains('active')) {
                    e.preventDefault();
                    activeIndex = index;
                    updateCarousel();
                }
            });
        });

        updateCarousel();
    }
}

// -- Value Propositions Scroll Animation ------------------------------------
const textRing = document.querySelector('.vp-spin-ring.text-ring');
const imageRing = document.querySelector('.vp-spin-ring.vp-outer-icons');

if (textRing && imageRing) {
    function updateRotation() {
        if (document.body.classList.contains('accessibility-reduce-motion')) return;

        const scrollY = window.scrollY;
        const rotationText = scrollY * 0.15;
        const rotationOuter = scrollY * -0.10;

        textRing.style.transform = `rotate(${rotationText}deg)`;
        imageRing.style.transform = `rotate(${rotationOuter}deg)`;
    }

    window.addEventListener('scroll', updateRotation, { passive: true });
    updateRotation();
}

// -- Scalable Global Theme Intersection Observer ----------------------------
const themeSections = document.querySelectorAll('[data-theme]');
if (themeSections.length > 0) {
    const observerOptions = {
        root: null,
        rootMargin: "-50% 0px -50% 0px",
        threshold: 0
    };

    const themeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const theme = entry.target.getAttribute('data-theme');
                document.body.className = document.body.className.replace(/\btheme-\S+/g, '').trim();
                if (theme) {
                    document.body.classList.add(`theme-${theme}`);
                }
            }
        });
    }, observerOptions);

    themeSections.forEach(section => {
        themeObserver.observe(section);
    });
}

// -- Home Customizing Scroll Assembly --------------------------------------------
const homeCustomWrapper = document.querySelector('.home-custom-wrapper');
const homeCustomContent = document.querySelector('.home-custom-content');

if (homeCustomWrapper) {
    const parts = [
        { el: document.querySelector('.h-part-top-housing'), startX: -50 }, // vw
        { el: document.querySelector('.h-part-stem'), startX: 50 },
        { el: document.querySelector('.h-part-spring'), startX: -50 },
        { el: document.querySelector('.h-part-bottom'), startX: 50 }
    ];

    function updateAssembly() {
        if (document.body.classList.contains('accessibility-reduce-motion')) {
            parts.forEach(p => {
                if (p.el) {
                    p.el.style.transform = `translateX(0)`;
                    p.el.style.opacity = 1;
                }
            });
            if (homeCustomContent) homeCustomContent.classList.add('h-show-content');
            return;
        }

        const rect = homeCustomWrapper.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        const startPoint = windowHeight * 0.8;
        const endPoint = 0;
        const scrollRange = startPoint - endPoint;

        let progress = (startPoint - rect.top) / scrollRange;

        progress = Math.max(0, Math.min(1, progress));

        const ease = progress;

        parts.forEach((p, index) => {
            if (!p.el) return;
            const currentOffset = p.startX * (1 - ease);
            const opacity = Math.min(1, progress * 4);
            p.el.style.transform = `translateX(${currentOffset}vw)`;
            p.el.style.opacity = opacity;
        });

        if (homeCustomContent) {
            if (progress > 0.9) {
                homeCustomContent.classList.add('h-show-content');
            } else {
                homeCustomContent.classList.remove('h-show-content');
            }
        }
    }

    window.addEventListener('scroll', updateAssembly, { passive: true });
    window.addEventListener('resize', updateAssembly);
    updateAssembly();
}