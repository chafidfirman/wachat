<?php
$title = 'ChatCart Web - E-commerce via WhatsApp';
ob_start();
require_once __DIR__ . '/../../../core/shared/helpers/components_helper.php';
?>
<?php renderHero('Belanja Cepat, Chat Langsung via WhatsApp!', 'Jelajahi produk kami dan pesan langsung melalui WhatsApp', 'Mulai Belanja', '#products-section'); ?>

<!-- Products Section -->
<section class="products" id="products-section">
    <div class="container">
        <!-- Limited Stock Products Section -->
        <?php 
        $limitedProducts = array_filter($products, function($product) {
            return isset($product['stock']) && $product['stock'] > 0 && $product['stock'] <= 5;
        });
        renderProductGrid($limitedProducts, 'Produk Limited', 'limitedProducts'); ?>

        <!-- Best Seller Products Section -->
        <?php 
        // For best sellers, we'll use a simple approach - products with "Best" in name or medium stock (indicating popularity)
        $bestSellerProducts = array_filter($products, function($product) {
            return strpos(strtolower($product['name']), 'best') !== false || 
                   (isset($product['stock']) && $product['stock'] >= 10 && $product['stock'] <= 20);
        });
        $bestSellerProducts = array_slice($bestSellerProducts, 0, 6); // Limit to 6 best sellers
        renderProductGrid($bestSellerProducts, 'Produk Terlaris', 'bestSellerProducts'); ?>

        <!-- All Products Section -->
        <?php renderProductGrid($products, 'Semua Produk Kami', 'allProducts'); ?>
    </div>
</section>

<!-- Navigation Guide for Admin -->
<?php if (isset($_SESSION['admin_id'])): ?>
<div class="container mt-5">
    <div class="alert alert-info">
        <h5><i class="fas fa-info-circle"></i> Navigation Guide</h5>
        <p>You are logged in as an administrator. Use the navigation below to manage your store:</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="<?= site_url('admin/products') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-boxes"></i> Manage Products
            </a>
            <a href="<?= site_url('admin/categories') ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-tags"></i> Manage Categories
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../core/shared/layouts/main.php';
?>