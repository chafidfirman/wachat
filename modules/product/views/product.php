<?php
$title = htmlspecialchars($product['name']) . ' - ChatCart Web';
ob_start();
require_once __DIR__ . '/../../../core/shared/helpers/components_helper.php';
require_once __DIR__ . '/../../../core/shared/helpers/view_helpers.php';
?>
<div class="container mt-4 product-detail-container">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url() ?>#products-section">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo !empty($product['image']) ? $product['image'] : base_url('assets/img/product-default.jpg'); ?>" 
                 class="img-fluid rounded product-detail-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-muted product-detail-category"><?php echo htmlspecialchars($product['category']); ?></p>
            <h3 class="text-success product-detail-price"><?php echo formatPrice($product['price']); ?></h3>
            
            <p class="product-detail-description"><?php echo htmlspecialchars($product['description']); ?></p>
            
            <div class="mb-3 product-stock-status">
                <?php if (($product['stockQuantity'] ?? 0) !== null && ($product['stockQuantity'] ?? 0) <= 5 && ($product['stockQuantity'] ?? 0) > 0): ?>
                    <span class="badge bg-warning">SISA <?php echo $product['stockQuantity']; ?> PCS!</span>
                <?php elseif ($product['inStock'] === true): ?>
                    <span class="badge bg-success">In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <?php if ($product['inStock'] === true && ($product['stockQuantity'] === null || ($product['stockQuantity'] ?? 0) > 0)): ?>
                <form action="<?= site_url('whatsapp/' . $product['id']) ?>" method="GET" class="product-order-form">
                    <!-- Quantity Selection -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo ($product['stockQuantity'] ?? 100); ?>" style="width: 100px;">
                    </div>
                    
                    <!-- Product Variations (if applicable) -->
                    <?php if (in_array($product['category'], ['Clothing', 'Beauty'])): ?>
                        <div class="mb-3">
                            <label for="variation" class="form-label">Select Variation:</label>
                            <select class="form-select" id="variation" name="variation">
                                <?php if ($product['category'] === 'Clothing'): ?>
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                <?php elseif ($product['category'] === 'Beauty'): ?>
                                    <option value="Rose">Rose</option>
                                    <option value="Lavender" selected>Lavender</option>
                                    <option value="Vanilla">Vanilla</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-success btn-lg whatsapp-order-btn">
                        <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                    </button>
                </form>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="fab fa-whatsapp"></i> Out of Stock
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Related Products Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="related-products-title">Produk Terkait</h3>
            <div class="row">
                <?php if (empty($relatedProducts)): ?>
                    <div class="col-12">
                        <p>No related products found.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <?php renderRelatedProductCard($related); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Admin Navigation (if logged in) -->
    <?php if (isset($_SESSION['admin_id'])): ?>
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="fas fa-tools"></i> Admin Options</h5>
                <p>You are logged in as an administrator. Use the links below to manage this product:</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-boxes"></i> Back to Product Management
                    </a>
                    <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>