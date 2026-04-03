<?php
/**
 * views/components/filter-sidebar.php
 *
 * Product filter sidebar for the catalogue page (/products).
 *
 * Submits as a GET form so filters are reflected in the URL and shareable.
 * Works without JavaScript - JS can enhance it with instant filtering later.
 *
 * ACCESSIBILITY NOTES:
 *   - Wrapped in <aside aria-label="Product filters"> - a named landmark.
 *   - Checkbox groups use <fieldset> + <legend> for semantic grouping.
 *   - All inputs have explicit <label> elements.
 *   - Price range inputs use separate labels, not placeholder-only labels.
 *
 * TODO: Enhance with JS for live filtering (debounced search, instant checkboxes).
 *       Use an aria-live region on the product grid to announce result count changes.
 */

$activeTypes = (array) ($_GET['type'] ?? []);
$priceMin = sanitizeInt($_GET['price_min'] ?? 0);
$priceMax = sanitizeInt($_GET['price_max'] ?? 500);
$activeSearch = htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<aside aria-label="Product filters">
    <form method="GET" action="/products" id="filterForm">

        <!-- ── Keyword Search ──────────────────────────────── -->
        <div class="mb-4">
            <label for="filterSearch" class="form-label fw-semibold">
                Search
            </label>
            <input type="search" class="form-control" id="filterSearch" name="q" value="<?= $activeSearch ?>"
                placeholder="e.g. Cherry MX Red">
        </div>

        <!-- ── Switch Type ────────────────────────────────── -->
        <fieldset class="mb-4">
            <legend class="fw-semibold mb-2 filter-legend-sm">
                Switch Type
            </legend>
            <?php
            $switchTypes = [
                'linear' => 'Linear',
                'tactile' => 'Tactile',
                'clicky' => 'Clicky',
            ];
            foreach ($switchTypes as $value => $label):
                $checked = in_array($value, $activeTypes, true) ? 'checked' : '';
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="type_<?= $value ?>" name="type[]"
                        value="<?= $value ?>" <?= $checked ?>>
                    <label class="form-check-label" for="type_<?= $value ?>">
                        <?= $label ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <!-- ── Price Range ────────────────────────────────── -->
        <fieldset class="mb-4">
            <legend class="fw-semibold mb-2 filter-legend-sm">
                Price (SGD)
            </legend>
            <div class="row g-2">
                <div class="col-6">
                    <label for="price_min" class="form-label small">Min</label>
                    <input type="number" class="form-control form-control-sm" id="price_min" name="price_min"
                        value="<?= $priceMin ?>" min="0" step="1">
                </div>
                <div class="col-6">
                    <label for="price_max" class="form-label small">Max</label>
                    <input type="number" class="form-control form-control-sm" id="price_max" name="price_max"
                        value="<?= $priceMax ?>" min="0" step="1">
                </div>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-dark w-100 mb-2">
            <i class="bi bi-funnel" aria-hidden="true"></i>
            Apply Filters
        </button>

        <?php if (!empty($activeTypes) || $activeSearch || $priceMin > 0 || $priceMax < 500): ?>
            <a href="/products" class="btn btn-outline-secondary w-100">
                Clear Filters
            </a>
        <?php endif; ?>

    </form>
</aside>