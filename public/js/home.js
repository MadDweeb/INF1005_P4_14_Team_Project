/* public/js/home.js */
document.addEventListener('DOMContentLoaded', function () {
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
                    item.className = 'carousel-item'; // reset classes
                    item.setAttribute('aria-hidden', 'true');

                    let diff = index - activeIndex;

                    // Support wrap-around offset calculation
                    if (diff > Math.floor(items.length / 2)) {
                        diff -= items.length;
                    } else if (diff < -Math.floor(items.length / 2)) {
                        diff += items.length;
                    }

                    if (diff === 0) {
                        item.classList.add('active');
                        item.setAttribute('aria-hidden', 'false');
                    } else if (diff === -1) {
                        item.classList.add('prev-1');
                    } else if (diff === 1) {
                        item.classList.add('next-1');
                    } else if (diff === -2) {
                        item.classList.add('prev-2');
                    } else if (diff === 2) {
                        item.classList.add('next-2');
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

            // Clicking side items makes them active without immediately navigating away
            items.forEach((item, index) => {
                item.addEventListener('click', (e) => {
                    if (!item.classList.contains('active')) {
                        e.preventDefault();
                        activeIndex = index;
                        updateCarousel();
                    }
                });
            });

            // Init
            updateCarousel();
        }
    }

    // -- Value Propositions Scroll Animation ------------------------------------
    const textRing = document.querySelector('.vp-spin-ring.text-ring');
    const imageRing = document.querySelector('.vp-spin-ring.vp-outer-icons');

    if (textRing && imageRing) {
        function updateRotation() {
            // If reduce motion is on, skip animation
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
            rootMargin: "-50% 0px -50% 0px", // Triggers precisely at the vertical center of the viewport
            threshold: 0
        };

        const themeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const theme = entry.target.getAttribute('data-theme');

                    // Natively strip all existing theme- classes via Regex safely
                    document.body.className = document.body.className.replace(/\btheme-\S+/g, '').trim();

                    // Append the incoming theme
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
});