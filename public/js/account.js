/*
 * public/js/account.js
 * Account page interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // === Personal Info Edit ===
    const editPersonalBtn = document.getElementById('editPersonalBtn');
    const cancelPersonalBtn = document.getElementById('cancelPersonalBtn');
    const personalInfo = document.getElementById('personalInfo');
    const personalForm = document.getElementById('personalForm');

    if (editPersonalBtn) {
        editPersonalBtn.addEventListener('click', function() {
            personalInfo.classList.add('hidden');
            personalForm.classList.remove('hidden');
            editPersonalBtn.style.display = 'none';
        });
    }

    if (cancelPersonalBtn) {
        cancelPersonalBtn.addEventListener('click', function() {
            personalForm.classList.add('hidden');
            personalInfo.classList.remove('hidden');
            editPersonalBtn.style.display = 'block';
        });
    }

    // === Shipping Address Edit ===
    const editShippingBtn = document.getElementById('editShippingBtn');
    const cancelShippingBtn = document.getElementById('cancelShippingBtn');
    const shippingInfo = document.getElementById('shippingInfo');
    const shippingForm = document.getElementById('shippingForm');

    if (editShippingBtn) {
        editShippingBtn.addEventListener('click', function() {
            shippingInfo.classList.add('hidden');
            shippingForm.classList.remove('hidden');
            editShippingBtn.style.display = 'none';
        });
    }

    if (cancelShippingBtn) {
        cancelShippingBtn.addEventListener('click', function() {
            shippingForm.classList.add('hidden');
            shippingInfo.classList.remove('hidden');
            editShippingBtn.style.display = 'block';
        });
    }

    // === Animate elements on load ===
    const sidebar = document.querySelector('.account-sidebar');
    const mainContent = document.querySelector('.account-main');

    if (sidebar) {
        sidebar.style.opacity = '0';
        sidebar.style.transform = 'translateX(-30px)';
        sidebar.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            sidebar.style.opacity = '1';
            sidebar.style.transform = 'translateX(0)';
        }, 100);
    }

    if (mainContent) {
        mainContent.style.opacity = '0';
        mainContent.style.transform = 'translateY(20px)';
        mainContent.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            mainContent.style.opacity = '1';
            mainContent.style.transform = 'translateY(0)';
        }, 200);
    }

    // Animate nav items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 300 + (index * 80));
    });

    // Animate content cards
    const contentCards = document.querySelectorAll('.content-card');
    contentCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 400 + (index * 150));
    });

    // Input focus animations
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
});