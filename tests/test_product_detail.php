<?php
// Test file to verify product detail view functionality

// Include only the database configuration to avoid constant redefinition
require_once __DIR__ . '/../core/shared/config/database.php';
require_once __DIR__ . '/../modules/product/models/product.php';

// Test product model functionality
$productModel = new Product($pdo);

// Test getting a product by ID
$product = $productModel->getById(3);
echo "Product 3:\n";
print_r($product);

// Test getting related products - using category ID instead of name
if ($product) {
    echo "\nProduct category: " . $product['category'] . " (name) / " . $product['category_name'] . " (category_name)\n";
    
    // Try with category name
    $relatedProducts = $productModel->getRelated(3, $product['category']);
    echo "\nRelated products (by category name):\n";
    print_r($relatedProducts);
    
    // Try with category ID (from database)
    $categoryId = 1; // Clothing category
    $relatedProducts2 = $productModel->getRelated(3, $categoryId);
    echo "\nRelated products (by category ID):\n";
    print_r($relatedProducts2);
} else {
    echo "Product not found\n";
}
?>