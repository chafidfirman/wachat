<div class="card h-100 product-card">
    <img src="<?php echo !empty($product['images']) ? json_decode($product['images'], true)['image'] : base_url('assets/img/product-default.jpg'); ?>" 
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
                $stock = $product['stock'] ?? 0;
                if ($stock !== null && $stock <= 5 && $stock > 0): ?>
                    <span class="badge bg-warning">Only <?php echo $stock; ?> left!</span>
                <?php elseif ($stock === null || $stock > 0): ?>
                    <span class="badge bg-success">In Stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            <div class="mt-2 d-flex justify-content-between">
                <a href="<?= site_url('product/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary detail-btn">
                    <i class="fas fa-eye"></i> Detail
                </a>
                <?php if (($product['stock'] ?? 0) === null || ($product['stock'] ?? 0) > 0): ?>
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