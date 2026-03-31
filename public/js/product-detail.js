/**
 * product-detail.js
 * Animations for product detail page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animate breadcrumb
    const breadcrumb = document.querySelector('.breadcrumb');
    if (breadcrumb) {
        breadcrumb.style.opacity = '0';
        breadcrumb.style.transform = 'translateY(-10px)';
        breadcrumb.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            breadcrumb.style.opacity = '1';
            breadcrumb.style.transform = 'translateY(0)';
        }, 100);
    }

    // Animate product image with parallax-like effect
    const productImage = document.querySelector('.product-main-image');
    if (productImage) {
        productImage.style.opacity = '0';
        productImage.style.transform = 'scale(0.95)';
        productImage.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            productImage.style.opacity = '1';
            productImage.style.transform = 'scale(1)';
        }, 200);

        // Image hover zoom effect
        productImage.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        productImage.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }

    // Stagger animate product details
    const detailsElements = [
        '.product-type-badge',
        '.product-title',
        '.product-manufacturer-detail',
        '.product-price-detail',
        '.product-stock-detail'
    ];

    detailsElements.forEach((selector, index) => {
        const element = document.querySelector(selector);
        if (element) {
            element.style.opacity = '0';
            element.style.transform = 'translateX(-20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateX(0)';
            }, 300 + (index * 100));
        }
    });

    // Animate specifications list
    const specItems = document.querySelectorAll('.spec-item');
    specItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-15px)';
        item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 600 + (index * 80));
    });

    // Animate add to cart section
    const addToCartForm = document.querySelector('.add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.style.opacity = '0';
        addToCartForm.style.transform = 'translateY(20px)';
        addToCartForm.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            addToCartForm.style.opacity = '1';
            addToCartForm.style.transform = 'translateY(0)';
        }, 1000);
    }

    // Quantity selector animations
    const quantityInput = document.querySelector('.quantity-selector input');
    if (quantityInput) {
        quantityInput.addEventListener('focus', function() {
            this.style.transform = 'scale(1.05)';
            this.style.borderColor = 'var(--accent)';
        });
        
        quantityInput.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    }

    // Add to cart button pulse animation
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    if (addToCartBtn && !addToCartBtn.disabled) {
        setTimeout(() => {
            addToCartBtn.style.animation = 'pulse 2s infinite';
        }, 1500);

        addToCartBtn.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
        });

        addToCartBtn.addEventListener('mouseleave', function() {
            this.style.animation = 'pulse 2s infinite';
        });

        addToCartBtn.addEventListener('click', function(e) {
            if (!this.disabled) {
                this.style.animation = 'none';
                this.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);

                // Create success feedback
                const feedback = document.createElement('div');
                feedback.textContent = '✓ Added to cart!';
                feedback.style.cssText = `
                    position: fixed;
                    top: 100px;
                    right: 20px;
                    background: var(--accent);
                    color: white;
                    padding: 15px 25px;
                    border-radius: 8px;
                    font-weight: 900;
                    z-index: 9999;
                    animation: slideInRight 0.4s ease, slideOutRight 0.4s ease 2s;
                `;
                document.body.appendChild(feedback);
                
                setTimeout(() => feedback.remove(), 2500);
            }
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
    
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
