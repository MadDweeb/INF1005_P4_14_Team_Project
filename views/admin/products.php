<?php
/*
 * views/admin/products.php
 * Admin product management page
 */

require_once __DIR__ . '/../../src/helpers/auth.php';
requireAdmin();

$currentAdminPage = 'products';
ob_start();
?>

<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .admin-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .add-product-btn {
        padding: 15px 30px;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .add-product-btn:hover {
        background: #c32a2a;
        transform: translateY(-2px);
    }

    .products-table-wrapper {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        overflow-x: auto;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .products-table th {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        text-align: left;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        white-space: nowrap;
    }

    .products-table td {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .product-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
    }

    .product-name-cell {
        font-weight: 900;
        max-width: 250px;
    }

    .type-badge {
        display: inline-block;
        padding: 5px 12px;
        background: var(--accent);
        color: white;
        font-size: 0.75rem;
        font-weight: 900;
        text-transform: uppercase;
        border-radius: 12px;
    }

    .stock-status {
        font-weight: 700;
    }

    .in-stock {
        color: #4ade80;
    }

    .low-stock {
        color: #fbbf24;
    }

    .out-of-stock {
        color: #f87171;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-edit,
    .btn-delete {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit {
        background: rgba(255, 255, 255, 0.1);
        color: inherit;
    }

    .btn-edit:hover {
        background: var(--accent);
        color: white;
    }

    .btn-delete {
        background: transparent;
        color: #f87171;
        border: 2px solid #f87171;
    }

    .btn-delete:hover {
        background: #f87171;
        color: white;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        padding: 20px;
        overflow-y: auto;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: var(--text-main);
        color: var(--bg-main);
        padding: 40px;
        border-radius: 12px;
        max-width: 700px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .modal-header h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 2rem;
        font-weight: 900;
        text-transform: uppercase;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
        transition: opacity 0.3s;
    }

    .close-modal:hover {
        opacity: 1;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid rgba(244, 240, 234, 0.3);
        background: rgba(244, 240, 234, 0.1);
        color: inherit;
        border-radius: 6px;
        font-family: inherit;
        font-size: 1rem;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
        font-size: 1.1rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-btn:hover {
        background: #c32a2a;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .modal-content {
            padding: 30px 20px;
        }
    }
</style>

<div class="admin-page">
    <div class="admin-header">
        <h1>Product Management</h1>
        <button class="add-product-btn" onclick="openAddModal()">+ Add New Product</button>
    </div>

    <div class="products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 60px;">
                            No products found. Add your first product!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="/assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.webp') ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                            </td>
                            <td class="product-name-cell">
                                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                <small style="opacity: 0.7;"><?= htmlspecialchars($product['manufacturer']) ?></small>
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
                                    <form method="POST" action="/admin/products/delete" style="display: inline;">
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
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Product</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
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