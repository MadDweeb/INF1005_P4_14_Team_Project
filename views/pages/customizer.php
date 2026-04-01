<?php
/*
 * views/pages/customizer.php
 * Interactive switch builder with exploding assembly animation
 */

ob_start();
$extraCss = ['/css/customizer.css'];
$extraJs = ['/js/customizer.js'];
?>

<div class="customizer-page">
    <div class="customizer-header">
        <h1>Build Your Dream Switch</h1>
    </div>

    <div class="builder-main">
        <!-- Switch Assembly that explodes on load -->
        <div class="switch-assembly-container">
            <div class="assembly-viewer">
                <!-- Components start stacked, then explode to 45-degree positions -->
                <div class="exploded-switch">
                    <div class="switch-part-exploded top-housing-exploded" data-part="top_housing">
                        <img src="/assets/images/top_housing.webp" alt="Top Housing" class="exploded-image">
                        <button class="edit-exploded-btn" data-part="top_housing">
                            <span class="edit-icon">✏️</span>
                            <span class="edit-text">Edit</span>
                        </button>
                    </div>

                    <div class="switch-part-exploded stem-exploded" data-part="stem">
                        <img src="/assets/images/stem.webp" alt="Stem" class="exploded-image">
                        <button class="edit-exploded-btn" data-part="stem">
                            <span class="edit-icon">✏️</span>
                            <span class="edit-text">Edit</span>
                        </button>
                    </div>

                    <div class="switch-part-exploded spring-exploded" data-part="spring">
                        <img src="/assets/images/spring.webp" alt="Spring" class="exploded-image">
                        <button class="edit-exploded-btn" data-part="spring">
                            <span class="edit-icon">✏️</span>
                            <span class="edit-text">Edit</span>
                        </button>
                    </div>

                    <div class="switch-part-exploded bottom-housing-exploded" data-part="bottom_housing">
                        <img src="/assets/images/bottom_housing.webp" alt="Bottom Housing" class="exploded-image">
                        <button class="edit-exploded-btn" data-part="bottom_housing">
                            <span class="edit-icon">✏️</span>
                            <span class="edit-text">Edit</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options Panel -->
        <div class="options-panel" id="optionsPanel">
            <div class="options-header">
                <h3 id="optionsPanelTitle">Select Component</h3>
                <button class="close-options-btn" id="closeOptionsBtn">✕ Close</button>
            </div>
            <div class="options-grid" id="optionsGrid">
                <!-- Options populated by JavaScript -->
            </div>
        </div>

        <!-- Build Summary -->
        <div class="build-summary">
            <h2>Your Custom Switch</h2>
            
            <div class="build-price">
                <h3>Total Price</h3>
                <div class="price-display">
                    <span class="price-label">Per Switch:</span>
                    <span class="price-value" id="total-price">$0.00</span>
                </div>
                <p class="price-note">Minimum order: 10 switches</p>
            </div>
            
            <div class="build-characteristics">
                <h3>Predicted Characteristics</h3>
                <div class="characteristics-grid">
                    <div class="char-item">
                        <span class="char-label">Switch Type:</span>
                        <span class="char-value" id="char-type">-</span>
                    </div>
                    <div class="char-item">
                        <span class="char-label">Actuation Force:</span>
                        <span class="char-value" id="char-force">-</span>
                    </div>
                    <div class="char-item">
                        <span class="char-label">Sound Profile:</span>
                        <span class="char-value" id="char-sound">-</span>
                    </div>
                    <div class="char-item">
                        <span class="char-label">Feel:</span>
                        <span class="char-value" id="char-feel">-</span>
                    </div>
                </div>
            </div>

            <div class="build-actions">
                <button class="reset-build-btn" id="resetBuildBtn">
                    <span>🔄</span> Start Over
                </button>
                <button class="add-to-cart-btn disabled" id="addToCartBtn" disabled>
                    <span>🛒</span> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Component data with pricing (based on market research)
    const componentData = {
        top_housing: [
            { name: 'Polycarbonate Clear', material: 'PC', sound: 'bright', description: 'Clear RGB diffusion', price: 0.15, image: 'pc_clear_top.webp' },
            { name: 'Nylon Black', material: 'Nylon', sound: 'deep', description: 'Deeper sound profile', price: 0.12, image: 'nylon_black_top.webp' },
            { name: 'ABS Frosted', material: 'ABS', sound: 'crisp', description: 'Crisp, high-pitched', price: 0.10, image: 'abs_frosted_top.webp' },
            { name: 'Polycarbonate Smoky', material: 'PC', sound: 'balanced', description: 'Visual flair with clarity', price: 0.18, image: 'pc_smoky_top.webp' }
        ],
        stem: [
            { name: 'Linear', type: 'linear', feel: 'Smooth, no tactile bump', sound: 'quiet', best_for: 'Gaming', price: 0.20, image: 'linear_stem.webp' },
            { name: 'Tactile', type: 'tactile', feel: 'Noticeable bump', sound: 'medium', best_for: 'Typing', price: 0.22, image: 'tactile_stem.webp' },
            { name: 'Clicky', type: 'clicky', feel: 'Tactile with click', sound: 'loud', best_for: 'Typing feedback', price: 0.25, image: 'clicky_stem.webp' },
            { name: 'Silent Linear', type: 'linear', feel: 'Smooth with dampeners', sound: 'very quiet', best_for: 'Office/quiet', price: 0.28, image: 'silent_linear_stem.webp' }
        ],
        spring: [
            { name: '35g Ultra Light', force: 35, feel: 'feather-light', description: 'Minimal resistance', price: 0.05, image: 'spring_35g.webp' },
            { name: '45g Light', force: 45, feel: 'light', description: 'Easy to press', price: 0.05, image: 'spring_45g.webp' },
            { name: '55g Medium', force: 55, feel: 'balanced', description: 'Standard weight', price: 0.05, image: 'spring_55g.webp' },
            { name: '62g Medium-Heavy', force: 62, feel: 'firm', description: 'More resistance', price: 0.06, image: 'spring_62g.webp' },
            { name: '67g Heavy', force: 67, feel: 'heavy', description: 'Strong feedback', price: 0.06, image: 'spring_67g.webp' }
        ],
        bottom_housing: [
            { name: 'Polycarbonate Clear', material: 'PC', sound: 'bright', description: 'RGB showcase', price: 0.15, image: 'pc_clear_bottom.webp' },
            { name: 'Nylon Black', material: 'Nylon', sound: 'deep', description: 'Muted bottom-out', price: 0.12, image: 'nylon_black_bottom.webp' },
            { name: 'POM White', material: 'POM', sound: 'thocky', description: 'Deep, satisfying sound', price: 0.20, image: 'pom_white_bottom.webp' },
            { name: 'Polycarbonate Milky', material: 'PC', sound: 'balanced', description: 'Diffused RGB glow', price: 0.16, image: 'pc_milky_bottom.webp' }
        ]
    };

    let selections = {
        top_housing: null,
        stem: null,
        spring: null,
        bottom_housing: null
    };

    document.addEventListener('DOMContentLoaded', function() {
        setupComponentButtons();
        setupCloseButton();
        setupResetButton();
        setupAddToCartButton();
        playExplodeAnimation();
    });

    function playExplodeAnimation() {
        // Parts start stacked, then explode to 45-degree positions
        const parts = document.querySelectorAll('.switch-part-exploded');
        
        // Start all parts at center (stacked)
        parts.forEach(part => {
            part.style.transform = 'translate(0, 0) rotate(0deg)';
            part.style.opacity = '1';
        });

        // Wait a moment, then explode
        setTimeout(() => {
            parts.forEach(part => {
                part.style.transition = 'transform 1s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.5s ease';
            });

            // Explode to final positions with rotation
            setTimeout(() => {
                document.querySelector('.top-housing-exploded').style.transform = 'translate(-180px, -180px) rotate(45deg)';
                document.querySelector('.stem-exploded').style.transform = 'translate(180px, -180px) rotate(45deg)';
                document.querySelector('.spring-exploded').style.transform = 'translate(-180px, 180px) rotate(45deg)';
                document.querySelector('.bottom-housing-exploded').style.transform = 'translate(180px, 180px) rotate(45deg)';
            }, 100);
        }, 800);
    }

    function setupComponentButtons() {
        document.querySelectorAll('.edit-exploded-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const part = this.dataset.part;
                openOptions(part);
            });
        });

        document.querySelectorAll('.switch-part-exploded').forEach(part => {
            part.addEventListener('click', function() {
                const partName = this.dataset.part;
                openOptions(partName);
            });
        });
    }

    function setupCloseButton() {
        document.getElementById('closeOptionsBtn').addEventListener('click', closeOptions);
    }

    function openOptions(part) {
        const panel = document.getElementById('optionsPanel');
        const title = document.getElementById('optionsPanelTitle');
        const grid = document.getElementById('optionsGrid');
        
        document.querySelectorAll('.switch-part-exploded').forEach(p => {
            p.classList.remove('active-part');
        });
        document.querySelector(`.switch-part-exploded[data-part="${part}"]`).classList.add('active-part');
        
        const partNames = {
            top_housing: 'Top Housing Material',
            stem: 'Stem Type',
            spring: 'Spring Weight',
            bottom_housing: 'Bottom Housing Material'
        };
        title.textContent = partNames[part];
        
        const components = componentData[part];
        grid.innerHTML = components.map((comp, index) => {
            const isSelected = selections[part]?.name === comp.name;
            return `
                <div class="option-card ${isSelected ? 'selected' : ''}" data-index="${index}">
                    <div class="option-header">
                        <div class="option-name">${comp.name}</div>
                        <div class="option-price">$${comp.price.toFixed(2)}</div>
                        ${isSelected ? '<span class="selected-badge">✓ Selected</span>' : ''}
                    </div>
                    <div class="option-body">
                        ${part === 'spring' ? `
                            <div class="option-detail"><strong>Force:</strong> ${comp.force}gf</div>
                            <div class="option-detail"><strong>Feel:</strong> ${comp.feel}</div>
                            <div class="option-detail">${comp.description}</div>
                        ` : ''}
                        ${part === 'stem' ? `
                            <div class="option-detail"><strong>Type:</strong> ${comp.type}</div>
                            <div class="option-detail"><strong>Feel:</strong> ${comp.feel}</div>
                            <div class="option-detail"><strong>Best for:</strong> ${comp.best_for}</div>
                        ` : ''}
                        ${part.includes('housing') ? `
                            <div class="option-detail"><strong>Material:</strong> ${comp.material}</div>
                            <div class="option-detail"><strong>Sound:</strong> ${comp.sound}</div>
                            <div class="option-detail">${comp.description}</div>
                        ` : ''}
                    </div>
                    <button class="select-option-btn">
                        ${isSelected ? 'Selected' : 'Select'}
                    </button>
                </div>
            `;
        }).join('');
        
        grid.querySelectorAll('.option-card').forEach(card => {
            card.querySelector('.select-option-btn').addEventListener('click', function(e) {
                e.stopPropagation();
                const index = parseInt(card.dataset.index);
                selectComponent(part, components[index]);
            });
        });
        
        panel.classList.add('active');
        setTimeout(() => panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
    }

    function closeOptions() {
        document.getElementById('optionsPanel').classList.remove('active');
        document.querySelectorAll('.switch-part-exploded').forEach(p => {
            p.classList.remove('active-part');
        });
    }

    function selectComponent(part, component) {
        selections[part] = component;
        
        // Update the color/appearance of the selected part
        const partElement = document.querySelector(`.switch-part-exploded[data-part="${part}"]`);
        const imgElement = partElement.querySelector('.exploded-image');
        
        // If component has an image file, swap the image
        if (component.image) {
            imgElement.src = `/assets/images/${component.image}`;
            
            // Only apply color filters for missing images (Nylon Black)
            if (component.image.includes('nylon_black')) {
                // These images don't exist, so use the default image with dark filter
                imgElement.src = part.includes('top') ? '/assets/images/pc_clear_top.webp' : '/assets/images/pc_clear_bottom.webp';
                imgElement.style.filter = 'brightness(0.3) saturate(0)';
                imgElement.style.transition = 'filter 0.5s ease';
            } else {
                // Image exists, remove any filters
                imgElement.style.filter = '';
                imgElement.style.opacity = '1';
            }
        }
        
        updateCharacteristics();
        updateTotalPrice();
        
        // Pulse the selected part
        const currentTransform = partElement.style.transform;
        partElement.style.transform = currentTransform + ' scale(1.2)';
        setTimeout(() => {
            partElement.style.transform = currentTransform;
        }, 300);
        
        openOptions(part);
    }

    function updateCharacteristics() {
        if (selections.stem) {
            document.getElementById('char-type').textContent = selections.stem.type.charAt(0).toUpperCase() + selections.stem.type.slice(1);
            document.getElementById('char-feel').textContent = selections.stem.feel;
        }
        
        if (selections.spring) {
            document.getElementById('char-force').textContent = selections.spring.force + 'gf (' + selections.spring.feel + ')';
        }
        
        if (selections.top_housing && selections.bottom_housing) {
            document.getElementById('char-sound').textContent = selections.top_housing.sound + ' + ' + selections.bottom_housing.sound;
        }
    }

    function updateTotalPrice() {
        let total = 0;
        if (selections.top_housing) total += selections.top_housing.price;
        if (selections.stem) total += selections.stem.price;
        if (selections.spring) total += selections.spring.price;
        if (selections.bottom_housing) total += selections.bottom_housing.price;
        
        const priceElement = document.getElementById('total-price');
        if (priceElement) {
            priceElement.textContent = `$${total.toFixed(2)}`;
        }
        
        // Enable Add to Cart if all parts selected
        const addToCartBtn = document.getElementById('addToCartBtn');
        if (addToCartBtn) {
            if (selections.top_housing && selections.stem && selections.spring && selections.bottom_housing) {
                addToCartBtn.disabled = false;
                addToCartBtn.classList.add('enabled');
            } else {
                addToCartBtn.disabled = true;
                addToCartBtn.classList.remove('enabled');
            }
        }
    }

    function setupResetButton() {
        document.getElementById('resetBuildBtn').addEventListener('click', function() {
            if (confirm('Reset your entire build?')) {
                selections = { top_housing: null, stem: null, spring: null, bottom_housing: null };
                
                // Reset all component images and filters to default
                document.querySelector('.top-housing-exploded .exploded-image').src = '/assets/images/top_housing.webp';
                document.querySelector('.stem-exploded .exploded-image').src = '/assets/images/stem.webp';
                document.querySelector('.spring-exploded .exploded-image').src = '/assets/images/spring.webp';
                document.querySelector('.bottom-housing-exploded .exploded-image').src = '/assets/images/bottom_housing.webp';
                
                document.querySelectorAll('.exploded-image').forEach(img => {
                    img.style.filter = '';
                    img.style.opacity = '1';
                });
                
                document.querySelectorAll('.char-value').forEach(el => el.textContent = '-');
                document.getElementById('total-price').textContent = '$0.00';
                
                closeOptions();
                updateTotalPrice();
                playExplodeAnimation();
            }
        });
    }

    function setupAddToCartButton() {
        document.getElementById('addToCartBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const allSelected = Object.values(selections).every(s => s !== null);
            
            if (!allSelected) {
                alert('Please complete all component selections first!');
                return;
            }
            
            // Calculate total price
            const totalPrice = (
                selections.top_housing.price +
                selections.stem.price +
                selections.spring.price +
                selections.bottom_housing.price
            ).toFixed(2);
            
            // Create custom switch description for the cart
            const customBuildData = JSON.stringify({
                top_housing: selections.top_housing.name,
                stem: selections.stem.name,
                spring: selections.spring.name,
                bottom_housing: selections.bottom_housing.name,
                total_price: totalPrice
            });
            
            // Create a hidden form to submit to cart
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/cart/add';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= generateCsrfToken() ?>';
            form.appendChild(csrfInput);
            
            // Add product ID (use a special ID for custom switches, e.g., 999 or 0)
            const productIdInput = document.createElement('input');
            productIdInput.type = 'hidden';
            productIdInput.name = 'product_id';
            productIdInput.value = '0'; // 0 indicates custom build
            form.appendChild(productIdInput);
            
            // Add quantity (minimum 10 switches)
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = '10';
            form.appendChild(quantityInput);
            
            // Add custom build data
            const customDataInput = document.createElement('input');
            customDataInput.type = 'hidden';
            customDataInput.name = 'custom_build';
            customDataInput.value = customBuildData;
            form.appendChild(customDataInput);
            
            // Add custom price
            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'custom_price';
            priceInput.value = totalPrice;
            form.appendChild(priceInput);
            
            // Add redirect URL
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect';
            redirectInput.value = '/cart';
            form.appendChild(redirectInput);
            
            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        });
    }
</script>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>