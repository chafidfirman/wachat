<?php
// Test file to verify the new structure works

// Include the database configuration
require_once __DIR__ . '/core/shared/config/database.php';
require_once __DIR__ . '/modules/product/models/product.php';
require_once __DIR__ . '/modules/category/models/category.php';

// Test database connection
try {
    $productModel = new Product($pdo);
    $categoryModel = new Category($pdo);
    
    // Test fetching products
    $products = $productModel->getAll();
    echo "Database connection successful!\n";
    echo "Found " . count($products) . " products\n";
    
    // Test fetching categories
    $categories = $categoryModel->getAll();
    echo "Found " . count($categories) . " categories\n";
    
    echo "New structure is working correctly!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>