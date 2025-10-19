<div class="card h-100 product-card">
    <img src="<?php 
        // Include image helper
        require_once __DIR__ . '/../../helpers/image_helper.php';
        
        // Handle image path - normalize it properly using centralized function
        $image = '';
        if (!empty($product['images'])) {
            $images = json_decode($product['images'], true);
            if (is_array($images) && isset($images['image'])) {
                $image = $images['image'];
            }
        } else if (!empty($product['image'])) {
            $image = $product['image'];
        }
        
        // Normalize the image path using centralized function
        echo getImageUrl($image);
    ?>" 
         class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
        <p class="card-text product-description">
            <?php echo htmlspecialchars(substr($product['description'], 0, 60)) . '...'; ?>
        </p>
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-success fw-bold product-price">
                    Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                </span>
                <?php 
                // Handle stock data normalization
                $stock = $product['stockQuantity'] ?? $product['stock'] ?? null;
                $inStock = $product['inStock'] ?? $product['in_stock'] ?? true;
                
                if ($stock !== null && $stock <= 5 && $stock > 0): ?>
                    <span class="badge bg-warning">Only <?php echo $stock; ?> left!</span>
                <?php elseif ($inStock && ($stock === null || $stock > 0)): ?>
                    <span class="badge bg-success">In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            <div class="mt-2 d-flex justify-content-between">
                <a href="<?= site_url('product/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary detail-btn">
                    <i class="fas fa-eye"></i> Detail
                </a>
                <?php if ($inStock && ($stock === null || $stock > 0)): ?>
                    <a href="<?= site_url('whatsapp/' . $product['id']) ?>" class="btn btn-sm btn-success whatsapp-btn" target="_blank">
                        <i class="fab fa-whatsapp"></i> Order
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled>
                        <i class="fab fa-whatsapp"></i> Out of Stock
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>