<?php
// Test file to verify product detail view functionality

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/product/models/product.php';

// Test product model functionality
$productModel = new Product();

// Test getting a product by ID
$product = $productModel->getById(3);
echo "Product 3:\n";
print_r($product);

// Test getting related products
if ($product) {
    $relatedProducts = $productModel->getRelated(3, $product['category']);
    echo "\nRelated products:\n";
    print_r($relatedProducts);
} else {
    echo "Product not found\n";
}
?>