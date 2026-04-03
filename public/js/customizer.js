/**
 * customizer.js
 * Diagonal cascade explosion animation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animate header
    const header = document.querySelector('.customizer-header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-20px)';
        header.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 100);
    }

    // Animate assembly container
    const container = document.querySelector('.switch-assembly-container');
    if (container) {
        container.style.opacity = '0';
        container.style.transform = 'scale(0.9)';
        container.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            container.style.opacity = '1';
            container.style.transform = 'scale(1)';
        }, 300);
    }

    // Trigger explosion animation
    playDiagonalExplode();

    // Animate summary
    const summary = document.querySelector('.build-summary');
    if (summary) {
        summary.style.opacity = '0';
        summary.style.transform = 'translateY(30px)';
        summary.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            summary.style.opacity = '1';
            summary.style.transform = 'translateY(0)';
        }, 2200);
    }

    // Animate indicators
    const indicators = document.querySelectorAll('.indicator-item');
    indicators.forEach((ind, index) => {
        ind.style.opacity = '0';
        ind.style.transform = 'translateY(20px)';
        ind.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            ind.style.opacity = '1';
            ind.style.transform = 'translateY(0)';
        }, 2000 + (index * 100));
    });

    // Enhanced hover effects
    const parts = document.querySelectorAll('.switch-part-exploded');
    parts.forEach(part => {
        part.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active-part')) {
                this.style.filter = 'drop-shadow(0 15px 40px rgba(215, 58, 58, 0.4))';
            }
        });
        
        part.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active-part')) {
                this.style.filter = '';
            }
        });
    });

    // Option cards animation
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.target.classList.contains('active')) {
                const cards = mutation.target.querySelectorAll('.option-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease, background 0.3s ease, border-color 0.3s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 80);
                });
            }
        });
    });

    const optionsPanel = document.getElementById('optionsPanel');
    if (optionsPanel) {
        observer.observe(optionsPanel, { attributes: true, attributeFilter: ['class'] });
    }

    // Selection particles
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-option-btn')) {
            const card = e.target.closest('.option-card');
            card.style.transform = 'scale(1.05)';
            setTimeout(() => card.style.transform = 'translateY(0)', 300);
            createSuccessParticles(e.target);
        }
    });

    // Button animations
    const editBtns = document.querySelectorAll('.edit-exploded-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.style.transform = 'translate(-50%, -50%) scale(0.9)';
            setTimeout(() => this.style.transform = 'translate(-50%, -50%) scale(1)', 150);
        });
    });

    const closeBtn = document.getElementById('closeOptionsBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            this.style.transform = 'rotate(90deg) scale(0.9)';
            setTimeout(() => this.style.transform = '', 300);
        });
    }




    const saveBtn = document.getElementById('saveBuildBtn');
    if (saveBtn) {
        setTimeout(() => saveBtn.style.animation = 'pulse 2s infinite', 3000);
        saveBtn.addEventListener('mouseenter', () => saveBtn.style.animation = 'none');
        saveBtn.addEventListener('mouseleave', () => saveBtn.style.animation = 'pulse 2s infinite');
    }
});

function playDiagonalExplode() {
    const topHousing = document.querySelector('.top-housing-exploded');
    const stem = document.querySelector('.stem-exploded');
    const spring = document.querySelector('.spring-exploded');
    const bottomHousing = document.querySelector('.bottom-housing-exploded');
    
    // Start all at center (stacked)
    [topHousing, stem, spring, bottomHousing].forEach(part => {
        part.style.transform = 'translate(0, 0) rotate(0deg)';
        part.style.opacity = '1';
    });
    
    // Wait, then explode with -48deg rotation
    setTimeout(() => {
        [topHousing, stem, spring, bottomHousing].forEach(part => {
            part.style.transition = 'transform 1.2s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.6s ease';
        });
        
        setTimeout(() => {
            // Diagonal cascade at -48deg
            topHousing.style.transform = 'translate(-360px, -180px) rotate(-48deg)';
            stem.style.transform = 'translate(-120px, -60px) rotate(-48deg)';
            spring.style.transform = 'translate(120px, 60px) rotate(-48deg)';
            bottomHousing.style.transform = 'translate(360px, 180px) rotate(-48deg)';
        }, 100);
    }, 800);
}

function createSuccessParticles(element) {
    const rect = element.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;
    
    for (let i = 0; i < 12; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 12px;
            height: 12px;
            background: #4ade80;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            left: ${centerX}px;
            top: ${centerY}px;
        `;
        
        document.body.appendChild(particle);
        
        const angle = (Math.PI * 2 * i) / 12;
        const distance = 80 + Math.random() * 60;
        const tx = Math.cos(angle) * distance;
        const ty = Math.sin(angle) * distance;
        
        particle.animate([
            { transform: 'translate(0, 0) scale(1)', opacity: 1 },
            { transform: `translate(${tx}px, ${ty}px) scale(0)`, opacity: 0 }
        ], {
            duration: 1000,
            easing: 'cubic-bezier(0.4, 0.0, 0.2, 1)'
        }).onfinish = () => particle.remove();
    }
}

// Add pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(215, 58, 58, 0.7); }
        50% { box-shadow: 0 0 0 15px rgba(215, 58, 58, 0); }
    }
`;
document.head.appendChild(style);