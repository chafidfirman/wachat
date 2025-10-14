<?php
/**
 * Helper functions for rendering view components
 */

// Include template helper
require_once __DIR__ . '/template_helper.php';

/**
 * Render a product card
 * 
 * @param array $product The product data
 * @return void
 */
function renderProductCard($product) {
    include __DIR__ . '/../views/components/product-card.php';
}

/**
 * Render a related product card
 * 
 * @param array $relatedProduct The related product data
 * @return void
 */
function renderRelatedProductCard($relatedProduct) {
    include __DIR__ . '/../views/components/related-product-card.php';
}

/**
 * Format price in IDR currency format
 * 
 * @param float $price The price to format
 * @return string Formatted price
 */
function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}
?>