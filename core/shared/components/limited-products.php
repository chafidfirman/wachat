<div class="section-limited">
    <h2>Produk Limited</h2>
    <div class="product-grid" id="limitedProducts">
        <?php if (empty($limitedProducts)): ?>
            <p>Tidak ada produk limited saat ini.</p>
        <?php else: ?>
            <?php foreach ($limitedProducts as $product): ?>
                <div class="product-card">
                    <div class="product-badge limited">
                        <?php echo $product['stockQuantity'] <= 3 ? 'SISA ' . $product['stockQuantity'] . ' PCS!' : 'LIMITED STOCK!'; ?>
                    </div>
                    <div class="product-image">
                        <?php 
                        $productImage = !empty($product['image']) ? $product['image'] : base_url('assets/img/product-default.jpg');
                        ?>
                        <img src="<?php echo htmlspecialchars($productImage); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='<?= base_url('assets/img/product-default.jpg') ?>'">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price">
                            <?php echo 'Rp ' . number_format($product['price'], 0, ',', '.'); ?>
                        </div>
                        <div class="product-meta">
                            <?php 
                            $stockStatus = '';
                            $stockClass = '';
                            if (!$product['inStock']) {
                                $stockStatus = 'Out of Stock';
                                $stockClass = 'out-of-stock';
                            } else if (isset($product['stockQuantity']) && $product['stockQuantity'] !== null) {
                                if ($product['stockQuantity'] <= 0) {
                                    $stockStatus = 'Out of Stock';
                                    $stockClass = 'out-of-stock';
                                } else if ($product['stockQuantity'] <= 5) {
                                    $stockStatus = 'Only ' . $product['stockQuantity'] . ' left!';
                                    $stockClass = 'low-stock';
                                } else {
                                    $stockStatus = $product['stockQuantity'] . ' in stock';
                                    $stockClass = 'in-stock';
                                }
                            } else {
                                $stockStatus = 'In Stock';
                                $stockClass = 'in-stock';
                            }
                            ?>
                            <span class="<?php echo $stockClass; ?>">
                                <?php echo $stockStatus; ?>
                            </span>
                        </div>
                        <div class="product-actions">
                            <a href="<?= site_url('product/' . $product['id']) ?>" class="action-btn detail-btn">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <?php if ($product['inStock'] && (!isset($product['stockQuantity']) || $product['stockQuantity'] > 0)): ?>
                                <a href="<?= site_url('whatsapp/' . $product['id']) ?>" class="action-btn whatsapp-btn">
                                    <i class="fab fa-whatsapp"></i> Chat & Beli
                                </a>
                            <?php else: ?>
                                <button class="action-btn whatsapp-btn out-of-stock-btn" disabled>
                                    <i class="fab fa-whatsapp"></i> Chat & Beli
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>