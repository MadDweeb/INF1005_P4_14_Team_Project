/**
 * checkout.js
 * Animations and interactions for checkout page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animate checkout header
    const header = document.querySelector('.checkout-header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-20px)';
        header.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }

    // Animate form sections
    const formSection = document.querySelector('.checkout-form-section');
    if (formSection) {
        formSection.style.opacity = '0';
        formSection.style.transform = 'translateX(-30px)';
        formSection.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            formSection.style.opacity = '1';
            formSection.style.transform = 'translateX(0)';
        }, 200);
    }

    // Animate order summary
    const orderSummary = document.querySelector('.order-summary');
    if (orderSummary) {
        orderSummary.style.opacity = '0';
        orderSummary.style.transform = 'translateX(30px)';
        orderSummary.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            orderSummary.style.opacity = '1';
            orderSummary.style.transform = 'translateX(0)';
        }, 300);
    }

    // Stagger animate form groups
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, 400 + (index * 50));
    });

    // Input focus animations
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
            this.parentElement.style.transform = 'translateX(5px)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
            this.parentElement.style.transform = 'translateX(0)';
        });
    });

    // Submit button animation
    const submitBtn = document.querySelector('.submit-order-btn');
    if (submitBtn) {
        setTimeout(() => {
            submitBtn.style.animation = 'pulse 2s infinite';
        }, 2000);

        submitBtn.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
        });

        submitBtn.addEventListener('mouseleave', function() {
            this.style.animation = 'pulse 2s infinite';
        });

        submitBtn.addEventListener('click', function(e) {
            this.style.animation = 'none';
            this.style.transform = 'scale(0.95)';
            this.textContent = 'Processing...';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(215, 58, 58, 0.7);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(215, 58, 58, 0);
        }
    }
`;
document.head.appendChild(style);
