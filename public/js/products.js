/**
 * products.js
 * Animations and interactions for products catalog page
 */

document.addEventListener('DOMContentLoaded', function() {

    // ── Filter dropdown toggle ─────────────────────────────────────────────
    const toggleBtn = document.getElementById('filter-toggle');
    const dropdown  = document.getElementById('filter-dropdown');

    if (toggleBtn && dropdown) {
        toggleBtn.addEventListener('click', function () {
            const isOpen = dropdown.classList.toggle('open');
            toggleBtn.setAttribute('aria-expanded', isOpen);
            dropdown.setAttribute('aria-hidden', !isOpen);
            dropdown.inert = !isOpen;
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                toggleBtn.setAttribute('aria-expanded', 'false');
                dropdown.setAttribute('aria-hidden', 'true');
                dropdown.inert = true;
            }
        });
    }

    // ── Type pill checked state ────────────────────────────────────────────
    document.querySelectorAll('.type-pill input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', function () {
            this.closest('.type-pill').classList.toggle('checked', this.checked);
        });
    });

    // ── Smooth scroll reveal for product cards ────────────────────────────
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

    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // ── Ripple effect on filter buttons ───────────────────────────────────
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect   = this.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height);
            ripple.style.width  = ripple.style.height = size + 'px';
            ripple.style.left   = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top    = (e.clientY - rect.top  - size / 2) + 'px';
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // ── Animate page header ────────────────────────────────────────────────
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

    // ── Auto-submit when sort changes ─────────────────────────────────────
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
});

// ── Live AJAX Search ──────────────────────────────────────────────────────────
(function () {
    const searchInput = document.querySelector('.search-box');
    const grid        = document.querySelector('.products-grid');
    const noProducts  = document.querySelector('.no-products');
    const pagination  = document.querySelector('.pagination');

    if (!searchInput) return; // Not on the products page.

    // Keep a snapshot of the server-rendered grid so we can restore it
    // if the user clears the search box.
    const originalGrid        = grid        ? grid.outerHTML        : null;
    const originalNoProducts  = noProducts  ? noProducts.outerHTML  : null;
    const originalPagination  = pagination  ? pagination.outerHTML  : null;
    const productsMain        = document.querySelector('.products-main');

    let debounceTimer = null;
    let currentQuery  = searchInput.value.trim();

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();

        if (q === currentQuery) return;

        debounceTimer = setTimeout(() => runSearch(q), 300);
    });

    function runSearch(q) {
        currentQuery = q;

        // Empty query - restore the original server-rendered content.
        if (q === '') {
            restoreOriginal();
            return;
        }


        // Carry the current filter sidebar values along so type/price filters
        // remain active while the user types.
        const form   = document.getElementById('filter-form');
        const params = new URLSearchParams();
        params.set('q', q);
        if (form) {
            form.querySelectorAll('input[name="type[]"]:checked').forEach(cb => {
                params.append('type[]', cb.value);
            });
            const minEl = form.querySelector('input[name="price_min"]');
            const maxEl = form.querySelector('input[name="price_max"]');
            if (minEl && minEl.value) params.set('price_min', minEl.value);
            if (maxEl && maxEl.value) params.set('price_max', maxEl.value);
        }

        fetch('/api/search?' + params.toString())
            .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.json();
            })
            .then(data => {
                // Bail if a newer search has already fired.
                if (q !== currentQuery) return;
                renderResults(data);
            })
            .catch(() => {
                // On any error, fall back silently - the form submit still works.
                if (q !== currentQuery) return;
            });
    }

    function renderResults(data) {
        // Hide pagination - it belongs to the full server-rendered result set.
        const paginationEl = document.querySelector('.pagination');
        if (paginationEl) paginationEl.style.display = 'none';

        // Re-use or create the grid element.
        let gridEl = document.querySelector('.products-grid');

        if (data.products.length === 0) {
            if (gridEl) gridEl.remove();
            let noEl = document.querySelector('.no-products');
            if (!noEl) {
                noEl = document.createElement('div');
                noEl.className = 'no-products';
                productsMain.appendChild(noEl);
            }
            noEl.innerHTML = '<h2>No products found</h2><p>Try a different search term.</p>';
            return;
        }

        // Remove the empty-state element if it's showing.
        const noEl = document.querySelector('.no-products');
        if (noEl) noEl.remove();

        if (!gridEl) {
            gridEl = document.createElement('div');
            gridEl.className = 'products-grid';
            productsMain.appendChild(gridEl);
        }

        gridEl.innerHTML = data.products.map(p => buildCard(p)).join('');

        // Trigger the existing scroll-reveal animation on the new cards.
        gridEl.querySelectorAll('.product-card').forEach(card => {
            card.style.opacity   = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            requestAnimationFrame(() => {
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0)';
            });
        });
    }

    function buildCard(p) {
        const stockHtml = p.stock_quantity > 0
            ? `<div class="product-stock">${p.stock_quantity} in stock</div>`
            : `<div class="product-stock">Out of stock</div>`;

        return `
            <a href="/products/${p.product_id}" class="product-card">
                <img
                    src="/assets/images/${escHtml(p.product_image)}"
                    alt="${escHtml(p.name)}"
                    class="product-image"
                    loading="lazy"
                >
                <div class="product-info">
                    <div class="product-type">${escHtml(p.switch_type)}</div>
                    <h2 class="product-name">${escHtml(p.name)}</h2>
                    <div class="product-manufacturer">${escHtml(p.manufacturer)}</div>
                    <div class="product-price">$${parseFloat(p.price).toFixed(2)}</div>
                    ${stockHtml}
                </div>
            </a>`;
    }

    function restoreOriginal() {
        const paginationEl = document.querySelector('.pagination');
        if (paginationEl) paginationEl.style.display = '';

        if (originalGrid) {
            const existing = document.querySelector('.products-grid');
            if (existing) existing.outerHTML = originalGrid;
        }
        if (originalNoProducts) {
            const existing = document.querySelector('.no-products');
            if (existing) existing.outerHTML = originalNoProducts;
        }
    }

    // Minimal HTML escaper - avoids XSS from API data rendered via innerHTML.
    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
}());

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