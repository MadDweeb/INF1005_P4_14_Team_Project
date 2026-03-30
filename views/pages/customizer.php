<?php
/*
 * views/pages/customizer.php
 * Interactive switch customizer - helps users find their perfect switch
 */

ob_start();
?>

<style>
.customizer-page {
    padding: 140px 5vw 80px;
    min-height: 100vh;
}

.customizer-header {
    text-align: center;
    margin-bottom: 60px;
}

.customizer-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
}

.customizer-header p {
    font-size: 1.2rem;
    opacity: 0.8;
    max-width: 600px;
    margin: 0 auto;
}

.customizer-container {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

/* Preference Panel */
.preference-panel {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 12px;
    position: sticky;
    top: 120px;
}

.preference-panel h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 30px;
}

.preference-group {
    margin-bottom: 35px;
}

.preference-group h3 {
    font-size: 1rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
    opacity: 0.7;
}

.preference-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.preference-btn {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid transparent;
    border-radius: 8px;
    color: inherit;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.preference-btn:hover {
    background: rgba(255, 255, 255, 0.15);
}

.preference-btn.active {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.slider-group {
    margin-bottom: 25px;
}

.slider-group label {
    display: block;
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.slider-value {
    float: right;
    color: var(--accent);
    font-weight: 900;
}

.slider {
    width: 100%;
    height: 8px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.1);
    outline: none;
    -webkit-appearance: none;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--accent);
    cursor: pointer;
    transition: all 0.3s;
}

.slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.slider::-moz-range-thumb {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--accent);
    cursor: pointer;
    border: none;
}

.reset-btn {
    width: 100%;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: inherit;
    font-weight: 900;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 20px;
}

.reset-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--accent);
}

/* Results Panel */
.results-panel {
    min-height: 400px;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.results-header h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
}

.results-count {
    font-size: 1.1rem;
    opacity: 0.7;
    font-weight: 700;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.result-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: block;
    border: 2px solid transparent;
}

.result-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
}

.result-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: rgba(255, 255, 255, 0.05);
}

.result-info {
    padding: 20px;
}

.result-type {
    display: inline-block;
    padding: 4px 10px;
    background: var(--accent);
    color: white;
    font-size: 0.7rem;
    font-weight: 900;
    text-transform: uppercase;
    border-radius: 10px;
    margin-bottom: 10px;
}

.result-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.3rem;
    font-weight: 900;
    margin-bottom: 8px;
}

.result-manufacturer {
    opacity: 0.7;
    font-size: 0.9rem;
    margin-bottom: 12px;
}

.result-specs {
    display: flex;
    gap: 15px;
    margin-bottom: 12px;
    font-size: 0.85rem;
}

.result-spec {
    opacity: 0.8;
}

.result-price {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--accent);
}

.no-results {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
}

.no-results h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    margin-bottom: 15px;
}

.no-results p {
    font-size: 1.1rem;
    opacity: 0.7;
}

.match-score {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.8);
    color: var(--accent);
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 900;
    font-size: 0.9rem;
}

.result-card {
    position: relative;
}

@media (max-width: 1024px) {
    .customizer-container {
        grid-template-columns: 1fr;
    }
    
    .preference-panel {
        position: static;
    }
}

@media (max-width: 768px) {
    .customizer-page {
        padding: 120px 5vw 60px;
    }
    
    .preference-panel {
        padding: 30px 20px;
    }
    
    .results-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="customizer-page">
    <div class="customizer-header">
        <h1>Switch Customizer</h1>
        <p>Answer a few questions to find your perfect mechanical keyboard switch</p>
    </div>

    <div class="customizer-container">
        <!-- Preferences Panel -->
        <div class="preference-panel">
            <h2>Your Preferences</h2>

            <!-- Switch Type -->
            <div class="preference-group">
                <h3>Switch Type</h3>
                <div class="preference-options">
                    <button class="preference-btn" data-filter="type" data-value="linear">Linear</button>
                    <button class="preference-btn" data-filter="type" data-value="tactile">Tactile</button>
                    <button class="preference-btn" data-filter="type" data-value="clicky">Clicky</button>
                </div>
            </div>

            <!-- Use Case -->
            <div class="preference-group">
                <h3>Primary Use</h3>
                <div class="preference-options">
                    <button class="preference-btn" data-filter="use" data-value="gaming">Gaming</button>
                    <button class="preference-btn" data-filter="use" data-value="typing">Typing</button>
                    <button class="preference-btn" data-filter="use" data-value="both">Both</button>
                </div>
            </div>

            <!-- Sound Level -->
            <div class="preference-group">
                <h3>Sound Level</h3>
                <div class="preference-options">
                    <button class="preference-btn" data-filter="sound" data-value="silent">Silent</button>
                    <button class="preference-btn" data-filter="sound" data-value="quiet">Quiet</button>
                    <button class="preference-btn" data-filter="sound" data-value="medium">Medium</button>
                    <button class="preference-btn" data-filter="sound" data-value="loud">Loud</button>
                </div>
            </div>

            <!-- Force Preference -->
            <div class="slider-group">
                <label>
                    Actuation Force
                    <span class="slider-value" id="forceValue">Any</span>
                </label>
                <input 
                    type="range" 
                    class="slider" 
                    id="forceSlider"
                    min="0" 
                    max="100" 
                    value="0"
                    step="5"
                >
                <small style="opacity: 0.6; display: block; margin-top: 8px;">
                    Lower = Lighter touch | Higher = Heavier press
                </small>
            </div>

            <!-- Price Range -->
            <div class="slider-group">
                <label>
                    Maximum Price
                    <span class="slider-value" id="priceValue">Any</span>
                </label>
                <input 
                    type="range" 
                    class="slider" 
                    id="priceSlider"
                    min="0" 
                    max="50" 
                    value="50"
                    step="5"
                >
            </div>

            <button class="reset-btn" id="resetBtn">Reset All Filters</button>
        </div>

        <!-- Results Panel -->
        <div class="results-panel">
            <div class="results-header">
                <h2>Recommended Switches</h2>
                <span class="results-count" id="resultsCount">6 matches</span>
            </div>
            <div class="results-grid" id="resultsGrid">
                <!-- Default products shown before JS loads -->
                <a href="/products/1" class="result-card">
                    <img src="/assets/images/switch1.png" alt="Cherry MX Red" class="result-image">
                    <div class="result-info">
                        <span class="result-type">linear</span>
                        <div class="result-name">Cherry MX Red</div>
                        <div class="result-manufacturer">Cherry</div>
                        <div class="result-specs">
                            <span class="result-spec">45.0gf</span>
                            <span class="result-spec">quiet</span>
                        </div>
                        <div class="result-price">$9.90</div>
                    </div>
                </a>
                <a href="/products/2" class="result-card">
                    <img src="/assets/images/switch2.png" alt="Cherry MX Brown" class="result-image">
                    <div class="result-info">
                        <span class="result-type">tactile</span>
                        <div class="result-name">Cherry MX Brown</div>
                        <div class="result-manufacturer">Cherry</div>
                        <div class="result-specs">
                            <span class="result-spec">55.0gf</span>
                            <span class="result-spec">quiet</span>
                        </div>
                        <div class="result-price">$9.90</div>
                    </div>
                </a>
                <a href="/products/3" class="result-card">
                    <img src="/assets/images/switch3.png" alt="Cherry MX Blue" class="result-image">
                    <div class="result-info">
                        <span class="result-type">clicky</span>
                        <div class="result-name">Cherry MX Blue</div>
                        <div class="result-manufacturer">Cherry</div>
                        <div class="result-specs">
                            <span class="result-spec">60.0gf</span>
                            <span class="result-spec">loud</span>
                        </div>
                        <div class="result-price">$9.90</div>
                    </div>
                </a>
                <a href="/products/4" class="result-card">
                    <img src="/assets/images/switch4.png" alt="Cherry Speed Silver" class="result-image">
                    <div class="result-info">
                        <span class="result-type">linear</span>
                        <div class="result-name">Cherry Speed Silver</div>
                        <div class="result-manufacturer">Cherry</div>
                        <div class="result-specs">
                            <span class="result-spec">45.0gf</span>
                            <span class="result-spec">quiet</span>
                        </div>
                        <div class="result-price">$12.90</div>
                    </div>
                </a>
                <a href="/products/5" class="result-card">
                    <img src="/assets/images/switch5.png" alt="Gateron Yellow" class="result-image">
                    <div class="result-info">
                        <span class="result-type">linear</span>
                        <div class="result-name">Gateron Yellow</div>
                        <div class="result-manufacturer">Gateron</div>
                        <div class="result-specs">
                            <span class="result-spec">35.0gf</span>
                            <span class="result-spec">silent</span>
                        </div>
                        <div class="result-price">$6.50</div>
                    </div>
                </a>
                <a href="/products/6" class="result-card">
                    <img src="/assets/images/switch6.png" alt="Topre 45g" class="result-image">
                    <div class="result-info">
                        <span class="result-type">tactile</span>
                        <div class="result-name">Topre 45g</div>
                        <div class="result-manufacturer">Topre</div>
                        <div class="result-specs">
                            <span class="result-spec">45.0gf</span>
                            <span class="result-spec">medium</span>
                        </div>
                        <div class="result-price">$35.00</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Sample product data - shows even without database connection
const allProducts = <?= !empty($products) ? json_encode($products) : json_encode([
    [
        'product_id' => 1,
        'name' => 'Cherry MX Red',
        'manufacturer' => 'Cherry',
        'switch_type' => 'linear',
        'actuation_force' => '45.0',
        'sound_profile' => 'quiet',
        'price' => '9.90',
        'product_image' => 'switch1.png'
    ],
    [
        'product_id' => 2,
        'name' => 'Cherry MX Brown',
        'manufacturer' => 'Cherry',
        'switch_type' => 'tactile',
        'actuation_force' => '55.0',
        'sound_profile' => 'quiet',
        'price' => '9.90',
        'product_image' => 'switch2.png'
    ],
    [
        'product_id' => 3,
        'name' => 'Cherry MX Blue',
        'manufacturer' => 'Cherry',
        'switch_type' => 'clicky',
        'actuation_force' => '60.0',
        'sound_profile' => 'loud',
        'price' => '9.90',
        'product_image' => 'switch3.png'
    ],
    [
        'product_id' => 4,
        'name' => 'Cherry Speed Silver',
        'manufacturer' => 'Cherry',
        'switch_type' => 'linear',
        'actuation_force' => '45.0',
        'sound_profile' => 'quiet',
        'price' => '12.90',
        'product_image' => 'switch4.png'
    ],
    [
        'product_id' => 5,
        'name' => 'Gateron Yellow',
        'manufacturer' => 'Gateron',
        'switch_type' => 'linear',
        'actuation_force' => '35.0',
        'sound_profile' => 'silent',
        'price' => '6.50',
        'product_image' => 'switch5.png'
    ],
    [
        'product_id' => 6,
        'name' => 'Topre 45g',
        'manufacturer' => 'Topre',
        'switch_type' => 'tactile',
        'actuation_force' => '45.0',
        'sound_profile' => 'medium',
        'price' => '35.00',
        'product_image' => 'switch6.png'
    ]
]) ?>;

let filters = {
    type: null,
    use: null,
    sound: null,
    maxForce: 0,
    maxPrice: 50
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateResults();
    
    // Preference buttons
    document.querySelectorAll('.preference-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            const value = this.dataset.value;
            
            // Toggle active state
            const siblings = this.parentElement.querySelectorAll('.preference-btn');
            siblings.forEach(s => s.classList.remove('active'));
            
            if (filters[filterType] === value) {
                // Deselect if clicking active button
                filters[filterType] = null;
            } else {
                this.classList.add('active');
                filters[filterType] = value;
            }
            
            updateResults();
        });
    });
    
    // Force slider
    const forceSlider = document.getElementById('forceSlider');
    const forceValue = document.getElementById('forceValue');
    forceSlider.addEventListener('input', function() {
        filters.maxForce = parseInt(this.value);
        forceValue.textContent = this.value == 0 ? 'Any' : this.value + 'gf';
        updateResults();
    });
    
    // Price slider
    const priceSlider = document.getElementById('priceSlider');
    const priceValue = document.getElementById('priceValue');
    priceSlider.addEventListener('input', function() {
        filters.maxPrice = parseInt(this.value);
        priceValue.textContent = this.value == 50 ? 'Any' : '$' + this.value;
        updateResults();
    });
    
    // Reset button
    document.getElementById('resetBtn').addEventListener('click', function() {
        filters = {
            type: null,
            use: null,
            sound: null,
            maxForce: 0,
            maxPrice: 50
        };
        
        document.querySelectorAll('.preference-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        forceSlider.value = 0;
        forceValue.textContent = 'Any';
        priceSlider.value = 50;
        priceValue.textContent = 'Any';
        
        updateResults();
    });
});

function updateResults() {
    let filtered = allProducts.filter(product => {
        // Type filter
        if (filters.type && product.switch_type !== filters.type) return false;
        
        // Sound filter
        if (filters.sound && product.sound_profile !== filters.sound) return false;
        
        // Force filter (if set)
        if (filters.maxForce > 0) {
            const force = parseFloat(product.actuation_force) || 0;
            if (force > filters.maxForce) return false;
        }
        
        // Price filter
        if (filters.maxPrice < 50) {
            const price = parseFloat(product.price) || 0;
            if (price > filters.maxPrice) return false;
        }
        
        return true;
    });
    
    // Calculate match scores based on use case
    filtered = filtered.map(product => {
        let score = 100;
        
        if (filters.use === 'gaming') {
            // Prefer linear switches with lighter force
            if (product.switch_type === 'linear') score += 20;
            if (parseFloat(product.actuation_force) < 50) score += 15;
        } else if (filters.use === 'typing') {
            // Prefer tactile/clicky with medium force
            if (product.switch_type === 'tactile' || product.switch_type === 'clicky') score += 20;
            if (parseFloat(product.actuation_force) >= 50) score += 10;
        }
        
        return { ...product, matchScore: Math.min(score, 100) };
    });
    
    // Sort by match score
    filtered.sort((a, b) => b.matchScore - a.matchScore);
    
    // Update count
    document.getElementById('resultsCount').textContent = 
        filtered.length + (filtered.length === 1 ? ' match' : ' matches');
    
    // Render results
    const grid = document.getElementById('resultsGrid');
    
    if (filtered.length === 0) {
        grid.innerHTML = `
            <div class="no-results">
                <h3>No matches found</h3>
                <p>Try adjusting your filters to see more results</p>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = filtered.map(product => `
        <a href="/products/${product.product_id}" class="result-card">
            ${product.matchScore > 100 ? `<div class="match-score">${product.matchScore}% Match</div>` : ''}
            <img 
                src="/assets/images/${product.product_image || 'placeholder.webp'}" 
                alt="${product.name}"
                class="result-image"
            >
            <div class="result-info">
                <span class="result-type">${product.switch_type}</span>
                <div class="result-name">${product.name}</div>
                <div class="result-manufacturer">${product.manufacturer}</div>
                <div class="result-specs">
                    ${product.actuation_force ? `<span class="result-spec">${product.actuation_force}gf</span>` : ''}
                    ${product.sound_profile ? `<span class="result-spec">${product.sound_profile}</span>` : ''}
                </div>
                <div class="result-price">$${parseFloat(product.price).toFixed(2)}</div>
            </div>
        </a>
    `).join('');
}
</script>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>