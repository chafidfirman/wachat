<?php
// Test script to verify our API endpoint

// Include the database configuration
require_once __DIR__ . '/core/shared/config/database.php';
require_once __DIR__ . '/modules/product/models/product.php';

try {
    $productModel = new Product($pdo);
    
    // Test fetching all products
    $products = $productModel->getAll();
    echo "API Test Results:\n";
    echo "==================\n";
    echo "Total products: " . count($products) . "\n\n";
    
    // Display product information
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . "\n";
        echo "Name: " . $product['name'] . "\n";
        echo "Price: Rp " . number_format($product['price'], 0, ',', '.') . "\n";
        echo "Category: " . $product['category_name'] . "\n";
        echo "------------------\n";
    }
    
    echo "API endpoint is working correctly!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>