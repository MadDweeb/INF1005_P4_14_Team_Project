<?php
/*
 * views/admin/products.php
 * Admin product management page
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'products';
$adminProductsCssVersion = @filemtime(__DIR__ . '/../../public/css/admin-products.css') ?: time();
$extraCss = ['/css/admin-products.css?v=' . $adminProductsCssVersion];
ob_start();
?>

<div class="admin-page">
    <div class="admin-header">
        <h1>Product Management</h1>
        <button class="add-product-btn" onclick="openAddModal()">+ Add New Product</button>
    </div>

    <div class="products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Product</th>
                    <th scope="col">Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="products-empty-cell">
                            No products found. Add your first product!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="/assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.webp') ?>"
                                    alt="" class="product-thumb">
                            </td>
                            <td class="product-name-cell">
                                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                <small class="product-manufacturer"><?= htmlspecialchars($product['manufacturer']) ?></small>
                            </td>
                            <td>
                                <span class="type-badge"><?= htmlspecialchars($product['switch_type']) ?></span>
                            </td>
                            <td>
                                <strong>$<?= number_format($product['price'], 2) ?></strong>
                            </td>
                            <td>
                                <span class="stock-status <?=
                                    $product['stock_quantity'] == 0 ? 'out-of-stock' :
                                    ($product['stock_quantity'] < 20 ? 'low-stock' : 'in-stock')
                                    ?>">
                                    <?= $product['stock_quantity'] ?> units
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick='openEditModal(<?= json_encode($product) ?>)'>Edit</button>
                                    <form method="POST" action="/admin/products/delete" class="inline-delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" class="btn-delete"
                                            onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Product</h2>
            <button class="close-modal" onclick="closeModal()" aria-label="Close product form">&times;</button>
        </div>

        <form id="productForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="product_id" id="productId">

            <div class="form-group">
                <label for="name">Product Name *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="manufacturer">Manufacturer *</label>
                    <input type="text" id="manufacturer" name="manufacturer" required>
                </div>

                <div class="form-group">
                    <label for="switch_type">Switch Type *</label>
                    <select id="switch_type" name="switch_type" required>
                        <option value="">Select type...</option>
                        <option value="linear">Linear</option>
                        <option value="tactile">Tactile</option>
                        <option value="clicky">Clicky</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price ($) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity *</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="actuation_force">Actuation Force (gf)</label>
                    <input type="number" id="actuation_force" name="actuation_force" step="0.1">
                </div>

                <div class="form-group">
                    <label for="bottom_out_force">Bottom-Out Force (gf)</label>
                    <input type="number" id="bottom_out_force" name="bottom_out_force" step="0.1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="travel_distance">Travel Distance (mm)</label>
                    <input type="number" id="travel_distance" name="travel_distance" step="0.1">
                </div>

                <div class="form-group">
                    <label for="pre_travel_distance">Pre-Travel (mm)</label>
                    <input type="number" id="pre_travel_distance" name="pre_travel_distance" step="0.1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sound_profile">Sound Profile</label>
                    <select id="sound_profile" name="sound_profile">
                        <option value="">Select profile...</option>
                        <option value="silent">Silent</option>
                        <option value="quiet">Quiet</option>
                        <option value="medium">Medium</option>
                        <option value="loud">Loud</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="compatibility">Compatibility</label>
                    <input type="text" id="compatibility" name="compatibility" placeholder="e.g., MX-compatible">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp">
            </div>

            <button type="submit" class="submit-btn">Save Product</button>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Add New Product';
        document.getElementById('productForm').action = '/admin/products/create';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('productModal').classList.add('active');
    }

    function openEditModal(product) {
        document.getElementById('modalTitle').textContent = 'Edit Product';
        document.getElementById('productForm').action = '/admin/products/update';

        // Fill form with product data
        document.getElementById('productId').value = product.product_id;
        document.getElementById('name').value = product.name;
        document.getElementById('manufacturer').value = product.manufacturer;
        document.getElementById('switch_type').value = product.switch_type;
        document.getElementById('price').value = product.price;
        document.getElementById('stock_quantity').value = product.stock_quantity;
        document.getElementById('actuation_force').value = product.actuation_force || '';
        document.getElementById('bottom_out_force').value = product.bottom_out_force || '';
        document.getElementById('travel_distance').value = product.travel_distance || '';
        document.getElementById('pre_travel_distance').value = product.pre_travel_distance || '';
        document.getElementById('sound_profile').value = product.sound_profile || '';
        document.getElementById('compatibility').value = product.compatibility || '';
        document.getElementById('description').value = product.description || '';

        document.getElementById('productModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('productModal').classList.remove('active');
    }

    // Close modal on outside click
    document.getElementById('productModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

<?php
$pageContent = ob_get_clean();
$pageTitle    = 'Admin - Products';
require_once __DIR__ . '/../layout/admin.php';
?>