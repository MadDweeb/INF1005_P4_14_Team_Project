/**
 * cart.js
 * Animations for shopping cart page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animate cart header
    const header = document.querySelector('.cart-header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-20px)';
        header.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }

    // Animate cart items with stagger
    const cartRows = document.querySelectorAll('.cart-table tbody tr');
    cartRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-30px)';
        row.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 200 + (index * 100));
    });

    // Animate cart summary
    const summary = document.querySelector('.cart-summary');
    if (summary) {
        summary.style.opacity = '0';
        summary.style.transform = 'translateX(30px)';
        summary.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            summary.style.opacity = '1';
            summary.style.transform = 'translateX(0)';
        }, 400);
    }

    // Quantity input animations
    const quantityInputs = document.querySelectorAll('.cart-item-quantity input');
    quantityInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.1)';
            this.style.borderColor = 'var(--accent)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });

        input.addEventListener('change', function() {
            const row = this.closest('tr');
            if (row) {
                row.style.animation = 'highlight 0.6s ease';
            }
        });
    });

    // Update button click animation
    const updateButtons = document.querySelectorAll('.update-btn');
    updateButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);

            // Keep text feedback lightweight so native form submission is not interrupted.
            const originalText = this.textContent;
            this.textContent = 'Updating...';
            setTimeout(() => {
                this.textContent = originalText;
            }, 1000);
        });
    });

    // Remove button with confirmation animation
    const removeButtons = document.querySelectorAll('.remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const row = this.closest('tr');
            
            if (row) {
                e.preventDefault();
                
                // Shake animation
                row.style.animation = 'shake 0.5s ease';
                
                setTimeout(() => {
                    if (confirm('Remove this item from cart?')) {
                        // Slide out animation
                        row.style.animation = 'slideOutLeft 0.5s ease';
                        
                        setTimeout(() => {
                            e.target.closest('form').submit();
                        }, 500);
                    } else {
                        row.style.animation = 'none';
                    }
                }, 500);
            }
        });
    });

    // Checkout button pulse
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        setTimeout(() => {
            checkoutBtn.style.animation = 'pulse 2s infinite';
        }, 1000);

        checkoutBtn.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
        });

        checkoutBtn.addEventListener('mouseleave', function() {
            this.style.animation = 'pulse 2s infinite';
        });
    }

    // Animate summary rows counting up
    const summaryRows = document.querySelectorAll('.summary-row .amount');
    summaryRows.forEach((amount, index) => {
        const text = amount.textContent;
        if (text.includes('$')) {
            const value = parseFloat(text.replace('$', '').replace(/,/g, ''));
            if (!isNaN(value)) {
                amount.textContent = '$0.00';

                setTimeout(() => {
                    animateValue(amount, 0, value, 800);
                }, 600 + (index * 200));
            }
        }
    });
});

// Helper function to animate number counting
function animateValue(element, start, end, duration) {
    const range = end - start;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const current = start + (range * easeOutQuart);
        
        element.textContent = '$' + current.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

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
    
    @keyframes highlight {
        0%, 100% {
            background: rgba(255, 255, 255, 0.03);
        }
        50% {
            background: rgba(215, 58, 58, 0.2);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    @keyframes slideOutLeft {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
