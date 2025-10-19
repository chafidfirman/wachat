<?php
// Debug file to check product data structure

require_once 'core/shared/config/database.php';
require_once 'modules/product/models/product.php';

$productModel = new Product($pdo);

// Get a specific product
$product = $productModel->getById(3);
echo "Product 3 data:\n";
print_r($product);

echo "\nAll products:\n";
$allProducts = $productModel->getAll();
foreach ($allProducts as $p) {
    echo "ID: {$p['id']}, Name: {$p['name']}, Category: {$p['category']} (ID: " . ($p['category_id'] ?? 'N/A') . ")\n";
}
?>