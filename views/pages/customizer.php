<?php
/*
 * views/pages/customizer.php
 * Interactive switch builder with exploding assembly animation
 */

$pageTitle = 'Switch Builder';
$currentPage = 'customizer';
$extraCss = ['/css/customizer.css'];
$extraJs = ['/js/customizer.js'];
ob_start();
?>

<div class="customizer-page">
    <div class="customizer-header">
        <h1>Build Your Dream Switch</h1>
        <p>Click any component to choose your material, stem type, and spring weight.</p>
    </div>

    <div class="builder-main">

        <!-- ── Responsive split layout wrapper ─────────────────────────────── -->
        <div class="builder-layout" id="builderLayout">

            <!-- Left: assembly (desktop = explosion, mobile = vertical list) -->
            <div class="switch-assembly-container">

                <!-- Desktop exploded view -->
                <div class="assembly-viewer">
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

                <!-- Mobile vertical parts list -->
                <div class="mobile-parts-list" role="list" aria-label="Switch components">
                    <div class="mobile-part" data-part="top_housing" role="listitem" tabindex="0" aria-label="Edit Top Housing">
                        <img src="/assets/images/top_housing.webp" alt="" class="mobile-part-img">
                        <div class="mobile-part-text">
                            <div class="mobile-part-name">Top Housing</div>
                            <div class="mobile-part-status" id="mob-top_housing">Not selected</div>
                        </div>
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                    <div class="mobile-part" data-part="stem" role="listitem" tabindex="0" aria-label="Edit Stem">
                        <img src="/assets/images/stem.webp" alt="" class="mobile-part-img">
                        <div class="mobile-part-text">
                            <div class="mobile-part-name">Stem</div>
                            <div class="mobile-part-status" id="mob-stem">Not selected</div>
                        </div>
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                    <div class="mobile-part" data-part="spring" role="listitem" tabindex="0" aria-label="Edit Spring">
                        <img src="/assets/images/spring.webp" alt="" class="mobile-part-img">
                        <div class="mobile-part-text">
                            <div class="mobile-part-name">Spring</div>
                            <div class="mobile-part-status" id="mob-spring">Not selected</div>
                        </div>
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                    <div class="mobile-part" data-part="bottom_housing" role="listitem" tabindex="0" aria-label="Edit Bottom Housing">
                        <img src="/assets/images/bottom_housing.webp" alt="" class="mobile-part-img">
                        <div class="mobile-part-text">
                            <div class="mobile-part-name">Bottom Housing</div>
                            <div class="mobile-part-status" id="mob-bottom_housing">Not selected</div>
                        </div>
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </div>
                </div>

            </div><!-- /.switch-assembly-container -->

            <!-- Right: options panel (slides in on mobile, drops below on desktop) -->
            <div class="options-panel" id="optionsPanel">
                <div class="options-header">
                    <h2 id="optionsPanelTitle">Select Component</h2>
                    <button class="close-options-btn" id="closeOptionsBtn">✕ Close</button>
                </div>
                <div class="options-grid" id="optionsGrid">
                    <!-- Options populated by JavaScript -->
                </div>
            </div>

        </div><!-- /.builder-layout -->

        <!-- Component Progress Indicators (desktop only) -->
        <div class="component-indicators" id="componentIndicators">
            <div class="indicator-item" id="ind-top_housing">
                <div class="indicator-label">Top Housing</div>
                <div class="indicator-value" id="ind-val-top_housing">Not Selected</div>
            </div>
            <div class="indicator-item" id="ind-stem">
                <div class="indicator-label">Stem</div>
                <div class="indicator-value" id="ind-val-stem">Not Selected</div>
            </div>
            <div class="indicator-item" id="ind-spring">
                <div class="indicator-label">Spring</div>
                <div class="indicator-value" id="ind-val-spring">Not Selected</div>
            </div>
            <div class="indicator-item" id="ind-bottom_housing">
                <div class="indicator-label">Bottom Housing</div>
                <div class="indicator-value" id="ind-val-bottom_housing">Not Selected</div>
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
                <div class="price-display price-display-total" id="total-price-row" style="display:none">
                    <span class="price-label">Total (<span id="qty-display">10</span>×):</span>
                    <span class="price-value price-total-value" id="total-price-all">$0.00</span>
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

            <div class="quantity-selector">
                <label for="build-quantity">Quantity</label>
                <div class="quantity-controls">
                    <button type="button" class="qty-btn" id="qty-minus" aria-label="Decrease quantity">−</button>
                    <input type="number" id="build-quantity" value="10" min="10" step="1" aria-label="Number of switches">
                    <button type="button" class="qty-btn" id="qty-plus" aria-label="Increase quantity">+</button>
                </div>
                <p class="qty-note">Min. 10 switches</p>
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

    function isMobileLayout() {
        return window.innerWidth <= 900;
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupComponentButtons();
        setupMobilePartButtons();
        setupCloseButton();
        setupResetButton();
        setupAddToCartButton();
        setupQuantityControls();
        if (!isMobileLayout()) {
            playExplodeAnimation();
        }
    });

    function playExplodeAnimation() {
        if (isMobileLayout()) return;
        const parts = document.querySelectorAll('.switch-part-exploded');
        parts.forEach(part => {
            part.style.transform = 'translate(0, 0) rotate(0deg)';
            part.style.opacity = '1';
        });
        setTimeout(() => {
            parts.forEach(part => {
                part.style.transition = 'transform 1s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.5s ease';
            });
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
                openOptions(this.dataset.part);
            });
        });
        document.querySelectorAll('.switch-part-exploded').forEach(part => {
            part.addEventListener('click', function() {
                openOptions(this.dataset.part);
            });
        });
    }

    function setupMobilePartButtons() {
        document.querySelectorAll('.mobile-part').forEach(tile => {
            const activate = () => openOptions(tile.dataset.part);
            tile.addEventListener('click', activate);
            tile.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); } });
        });
    }

    function setupCloseButton() {
        document.getElementById('closeOptionsBtn').addEventListener('click', closeOptions);
    }

    function openOptions(part) {
        const panel  = document.getElementById('optionsPanel');
        const title  = document.getElementById('optionsPanelTitle');
        const grid   = document.getElementById('optionsGrid');
        const layout = document.getElementById('builderLayout');

        // Highlight active desktop part
        document.querySelectorAll('.switch-part-exploded').forEach(p => p.classList.remove('active-part'));
        document.querySelector(`.switch-part-exploded[data-part="${part}"]`)?.classList.add('active-part');

        // Highlight active mobile tile
        document.querySelectorAll('.mobile-part').forEach(p => p.classList.remove('active-part'));
        document.querySelector(`.mobile-part[data-part="${part}"]`)?.classList.add('active-part');

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
                    <button class="select-option-btn">${isSelected ? 'Selected' : 'Select'}</button>
                </div>
            `;
        }).join('');

        grid.querySelectorAll('.option-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', function() {
                selectComponent(part, components[parseInt(card.dataset.index)]);
            });
        });

        panel.classList.add('active');
        layout.classList.add('panel-open');

        // On desktop: scroll to panel; on mobile: it's side-by-side so no scroll needed
        if (!isMobileLayout()) {
            setTimeout(() => panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
        }
    }

    function closeOptions() {
        document.getElementById('optionsPanel').classList.remove('active');
        document.getElementById('builderLayout').classList.remove('panel-open');
        document.querySelectorAll('.switch-part-exploded').forEach(p => p.classList.remove('active-part'));
        document.querySelectorAll('.mobile-part').forEach(p => p.classList.remove('active-part'));
    }

    function selectComponent(part, component) {
        selections[part] = component;

        const partElement = document.querySelector(`.switch-part-exploded[data-part="${part}"]`);
        if (partElement) {
            const imgElement = partElement.querySelector('.exploded-image');
            if (component.image) {
                if (component.image.includes('nylon_black')) {
                    imgElement.src = part.includes('top') ? '/assets/images/pc_clear_top.webp' : '/assets/images/pc_clear_bottom.webp';
                    imgElement.style.filter = 'brightness(0.3) saturate(0)';
                    imgElement.style.transition = 'filter 0.5s ease';
                } else {
                    imgElement.src = `/assets/images/${component.image}`;
                    imgElement.style.filter = '';
                    imgElement.style.opacity = '1';
                }
            }

            // Pulse the selected part (desktop only)
            if (!isMobileLayout()) {
                const currentTransform = partElement.style.transform;
                partElement.style.transform = currentTransform + ' scale(1.2)';
                setTimeout(() => { partElement.style.transform = currentTransform; }, 300);
            }
        }

        // Also update mobile part image
        const mobImg = document.querySelector(`.mobile-part[data-part="${part}"] .mobile-part-img`);
        if (mobImg && component.image && !component.image.includes('nylon_black')) {
            mobImg.src = `/assets/images/${component.image}`;
        }

        updateCharacteristics();
        updateTotalPrice();
        updateIndicators();
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
        if (selections.stem)        total += selections.stem.price;
        if (selections.spring)      total += selections.spring.price;
        if (selections.bottom_housing) total += selections.bottom_housing.price;

        document.getElementById('total-price').textContent = `$${total.toFixed(2)}`;

        const qty       = Math.max(10, parseInt(document.getElementById('build-quantity')?.value || 10));
        const totalAllEl = document.getElementById('total-price-all');
        const qtyDisplay = document.getElementById('qty-display');
        const totalRow   = document.getElementById('total-price-row');
        if (totalAllEl) totalAllEl.textContent = `$${(total * qty).toFixed(2)}`;
        if (qtyDisplay)  qtyDisplay.textContent = qty;
        if (totalRow)    totalRow.style.display = total > 0 ? 'flex' : 'none';

        const addToCartBtn = document.getElementById('addToCartBtn');
        if (addToCartBtn) {
            const allDone = selections.top_housing && selections.stem && selections.spring && selections.bottom_housing;
            addToCartBtn.disabled = !allDone;
            addToCartBtn.classList.toggle('enabled', !!allDone);
        }
    }

    function updateIndicators() {
        const parts = ['top_housing', 'stem', 'spring', 'bottom_housing'];
        parts.forEach(part => {
            // Desktop indicators
            const item = document.getElementById(`ind-${part}`);
            const val  = document.getElementById(`ind-val-${part}`);
            if (item && val) {
                if (selections[part]) {
                    item.classList.add('selected');
                    val.textContent = selections[part].name;
                } else {
                    item.classList.remove('selected');
                    val.textContent = 'Not Selected';
                }
            }
            // Mobile tile status
            const mobTile   = document.querySelector(`.mobile-part[data-part="${part}"]`);
            const mobStatus = document.getElementById(`mob-${part}`);
            if (mobTile && mobStatus) {
                if (selections[part]) {
                    mobTile.classList.add('part-done');
                    mobStatus.textContent = selections[part].name;
                } else {
                    mobTile.classList.remove('part-done');
                    mobStatus.textContent = 'Not selected';
                }
            }
        });
    }

    function setupResetButton() {
        document.getElementById('resetBuildBtn').addEventListener('click', function() {
            if (confirm('Reset your entire build?')) {
                selections = { top_housing: null, stem: null, spring: null, bottom_housing: null };

                document.querySelector('.top-housing-exploded .exploded-image').src = '/assets/images/top_housing.webp';
                document.querySelector('.stem-exploded .exploded-image').src = '/assets/images/stem.webp';
                document.querySelector('.spring-exploded .exploded-image').src = '/assets/images/spring.webp';
                document.querySelector('.bottom-housing-exploded .exploded-image').src = '/assets/images/bottom_housing.webp';
                document.querySelectorAll('.exploded-image').forEach(img => { img.style.filter = ''; img.style.opacity = '1'; });

                // Reset mobile tile images
                document.querySelector('.mobile-part[data-part="top_housing"] .mobile-part-img').src = '/assets/images/top_housing.webp';
                document.querySelector('.mobile-part[data-part="stem"] .mobile-part-img').src = '/assets/images/stem.webp';
                document.querySelector('.mobile-part[data-part="spring"] .mobile-part-img').src = '/assets/images/spring.webp';
                document.querySelector('.mobile-part[data-part="bottom_housing"] .mobile-part-img').src = '/assets/images/bottom_housing.webp';

                document.querySelectorAll('.char-value').forEach(el => el.textContent = '-');
                document.getElementById('total-price').textContent = '$0.00';

                closeOptions();
                updateIndicators();
                updateTotalPrice();
                if (!isMobileLayout()) playExplodeAnimation();
            }
        });
    }

    function setupQuantityControls() {
        const qtyInput = document.getElementById('build-quantity');
        if (!qtyInput) return;
        document.getElementById('qty-minus').addEventListener('click', () => {
            const v = parseInt(qtyInput.value);
            if (v > 10) { qtyInput.value = v - 1; updateTotalPrice(); }
        });
        document.getElementById('qty-plus').addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateTotalPrice();
        });
        qtyInput.addEventListener('change', () => {
            if (parseInt(qtyInput.value) < 10) qtyInput.value = 10;
            updateTotalPrice();
        });
    }

    function setupAddToCartButton() {
        document.getElementById('addToCartBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if (!Object.values(selections).every(s => s !== null)) {
                alert('Please complete all component selections first!');
                return;
            }
            const totalPrice = (
                selections.top_housing.price + selections.stem.price +
                selections.spring.price + selections.bottom_housing.price
            ).toFixed(2);
            const customBuildData = JSON.stringify({
                top_housing: selections.top_housing.name, stem: selections.stem.name,
                spring: selections.spring.name, bottom_housing: selections.bottom_housing.name,
                total_price: totalPrice
            });
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= htmlspecialchars(appUrl('/cart/add'), ENT_QUOTES, 'UTF-8') ?>';
            form.style.display = 'none';
            const fields = [
                ['csrf_token', '<?= generateCsrfToken() ?>'],
                ['product_id', '0'],
                ['quantity', String(Math.max(10, parseInt(document.getElementById('build-quantity')?.value || 10)))],
                ['custom_build', customBuildData],
                ['custom_price', totalPrice],
                ['redirect', '/cart']
            ];
            fields.forEach(([name, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = name; input.value = value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        });
    }
</script>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>
