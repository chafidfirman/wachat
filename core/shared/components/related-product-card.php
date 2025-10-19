<div class="card h-100 related-product-card">
    <img src="<?php 
        // Include image helper
        require_once __DIR__ . '/../../helpers/image_helper.php';
        
        // Handle image path - normalize it properly using centralized function
        $image = '';
        if (!empty($relatedProduct['images'])) {
            $images = json_decode($relatedProduct['images'], true);
            if (is_array($images) && isset($images['image'])) {
                $image = $images['image'];
            }
        } else if (!empty($relatedProduct['image'])) {
            $image = $relatedProduct['image'];
        }
        
        // Normalize the image path using centralized function
        echo getImageUrl($image);
    ?>" 
         class="card-img-top related-product-image" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
    <div class="card-body d-flex flex-column">
        <h6 class="card-title related-product-title"><?php echo htmlspecialchars($relatedProduct['name']); ?></h6>
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-success fw-bold related-product-price">
                    Rp <?php echo number_format($relatedProduct['price'], 0, ',', '.'); ?>
                </span>
                <?php 
                // Handle stock data normalization
                $stock = $relatedProduct['stockQuantity'] ?? $relatedProduct['stock'] ?? null;
                $inStock = $relatedProduct['inStock'] ?? $relatedProduct['in_stock'] ?? true;
                
                if ($stock !== null && $stock <= 5 && $stock > 0): ?>
                    <span class="badge bg-warning">Only <?php echo $stock; ?> left!</span>
                <?php elseif ($inStock && ($stock === null || $stock > 0)): ?>
                    <span class="badge bg-success">In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            <div class="mt-2 d-flex justify-content-between">
                <?php if ($inStock && ($stock === null || $stock > 0)): ?>
                    <a href="<?= site_url('whatsapp/' . $relatedProduct['id']) ?>" class="btn btn-sm btn-success whatsapp-btn" target="_blank">
                        <i class="fab fa-whatsapp"></i> Order
                    </a>
                <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled>
                        <i class="fab fa-whatsapp"></i> Out of Stock
                    </button>
                <?php endif; ?>
                <a href="<?= site_url('product/' . $relatedProduct['id']) ?>" class="btn btn-sm btn-outline-primary detail-btn">
                    <i class="fas fa-eye"></i> Detail
                </a>
            </div>
        </div>
    </div>
</div>