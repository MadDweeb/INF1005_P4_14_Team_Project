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
    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    // Check if the elements exist before running the code (prevents errors on pages without a header)
    if (menuToggle && mainNav) {
        const icon = menuToggle.querySelector('i');

        menuToggle.addEventListener('click', () => {
            // Toggle the 'active' class on the nav
            mainNav.classList.toggle('active');
            
            // Swap the hamburger and X icons
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                menuToggle.setAttribute('aria-expanded', 'true');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});