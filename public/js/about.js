/**
 * about.js
 * Animations for about page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animate hero section
    const hero = document.querySelector('.about-hero');
    if (hero) {
        const h1 = hero.querySelector('h1');
        const p = hero.querySelector('p');
        
        if (h1) {
            h1.style.opacity = '0';
            h1.style.transform = 'translateY(-30px)';
            h1.style.transition = 'opacity 1s ease, transform 1s ease';
            
            setTimeout(() => {
                h1.style.opacity = '1';
                h1.style.transform = 'translateY(0)';
            }, 100);
        }
        
        if (p) {
            p.style.opacity = '0';
            p.style.transform = 'translateY(20px)';
            p.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            
            setTimeout(() => {
                p.style.opacity = '1';
                p.style.transform = 'translateY(0)';
            }, 400);
        }
    }

    // Scroll-triggered animations
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Animate story section
    const storyContent = document.querySelector('.story-content');
    if (storyContent) {
        storyContent.style.opacity = '0';
        storyContent.style.transform = 'translateY(30px)';
        storyContent.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        observer.observe(storyContent);
    }

    // Animate value cards with stagger
    const valueCards = document.querySelectorAll('.value-card');
    valueCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(40px) scale(0.95)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) scale(1)';
                    }, index * 150);
                    cardObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        cardObserver.observe(card);

        // Add hover animation
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.03)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Animate stats with counting
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const text = statNumber.textContent;
                
                // Extract number from text
                const match = text.match(/(\d+)/);
                if (match) {
                    const targetValue = parseInt(match[1]);
                    animateCounter(statNumber, 0, targetValue, 2000, text);
                }
                
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    statNumbers.forEach(stat => {
        stat.style.opacity = '0';
        stat.style.transform = 'scale(0.5)';
        stat.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        statsObserver.observe(stat);
    });

    // Animate stat labels
    const statLabels = document.querySelectorAll('.stat-label');
    statLabels.forEach((label, index) => {
        label.style.opacity = '0';
        label.style.transform = 'translateY(20px)';
        label.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        const labelObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100 + 400);
                    labelObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        labelObserver.observe(label);
    });

    // Animate CTA section
    const ctaSection = document.querySelector('.cta-section');
    if (ctaSection) {
        const ctaH2 = ctaSection.querySelector('h2');
        const ctaP = ctaSection.querySelector('p');
        const ctaBtn = ctaSection.querySelector('.cta-button');
        
        const ctaObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (ctaH2) {
                        ctaH2.style.opacity = '0';
                        ctaH2.style.transform = 'translateY(30px)';
                        ctaH2.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                        setTimeout(() => {
                            ctaH2.style.opacity = '1';
                            ctaH2.style.transform = 'translateY(0)';
                        }, 100);
                    }
                    
                    if (ctaP) {
                        ctaP.style.opacity = '0';
                        ctaP.style.transform = 'translateY(20px)';
                        ctaP.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                        setTimeout(() => {
                            ctaP.style.opacity = '1';
                            ctaP.style.transform = 'translateY(0)';
                        }, 300);
                    }
                    
                    if (ctaBtn) {
                        ctaBtn.style.opacity = '0';
                        ctaBtn.style.transform = 'scale(0.8)';
                        ctaBtn.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        setTimeout(() => {
                            ctaBtn.style.opacity = '1';
                            ctaBtn.style.transform = 'scale(1)';
                            ctaBtn.style.animation = 'pulse 2s infinite';
                        }, 600);
                    }
                    
                    ctaObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        ctaObserver.observe(ctaSection);
    }

    // CTA button interactions
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
        });
        
        ctaButton.addEventListener('mouseleave', function() {
            this.style.animation = 'pulse 2s infinite';
        });
    }
});

// Counter animation function
function animateCounter(element, start, end, duration, originalText) {
    element.style.opacity = '1';
    element.style.transform = 'scale(1)';
    
    const startTime = performance.now();
    const suffix = originalText.replace(/\d+/, '');
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Ease out cubic
        const easeProgress = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(start + (end - start) * easeProgress);
        
        element.textContent = current + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = originalText;
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
            box-shadow: 0 0 0 15px rgba(215, 58, 58, 0);
        }
    }
    
    .value-card {
        transition: all 0.4s ease;
    }
`;
document.head.appendChild(style);
