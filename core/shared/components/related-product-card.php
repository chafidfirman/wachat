<div class="card h-100 related-product-card">
    <img src="<?php echo !empty($relatedProduct['image']) ? $relatedProduct['image'] : base_url('assets/img/product-default.jpg'); ?>" 
         class="card-img-top related-product-image" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
    <div class="card-body d-flex flex-column">
        <h6 class="card-title related-product-title"><?php echo htmlspecialchars($relatedProduct['name']); ?></h6>
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold related-product-price">
                    Rp <?php echo number_format($relatedProduct['price'], 0, ',', '.'); ?>
                </span>
            </div>
            <a href="<?= site_url('product/' . $relatedProduct['id']) ?>" class="btn btn-sm btn-outline-primary mt-2 detail-btn">
                <i class="fas fa-eye"></i> Detail
            </a>
        </div>
    </div>
</div>