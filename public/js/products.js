/**
 * products.js
 * Animations and interactions for products catalog page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll reveal for product cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Animate product cards on scroll
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Animate filter sections
    const filterSections = document.querySelectorAll('.filter-section');
    filterSections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateX(-20px)';
        section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateX(0)';
        }, index * 150);
    });

    // Smooth price range slider feedback
    const priceInputs = document.querySelectorAll('.price-inputs input');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.transform = 'scale(1.05)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    });

    // Add ripple effect to filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Animate page header
    const header = document.querySelector('.products-header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-20px)';
        header.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }

    // Smooth filter toggle animations
    const checkboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    label.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .filter-btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
