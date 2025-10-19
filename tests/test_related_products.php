<?php
// Test file to verify related products functionality

echo "=== Related Products Functionality Test ===\n\n";

// Test 1: Check database for products in same category
echo "1. Checking database for products in same category...\n";

require_once 'core/shared/config/database.php';
require_once 'modules/product/models/product.php';

$productModel = new Product($pdo);

// Get all products
$allProducts = $productModel->getAll();
echo "  ✓ Total products in database: " . count($allProducts) . "\n";

// Group products by category
$categoryProducts = [];
foreach ($allProducts as $product) {
    $categoryId = $product['category_id'] ?? $product['category'];
    if (!isset($categoryProducts[$categoryId])) {
        $categoryProducts[$categoryId] = [];
    }
    $categoryProducts[$categoryId][] = $product;
}

// Show category distribution
echo "  ✓ Products by category:\n";
foreach ($categoryProducts as $categoryId => $products) {
    echo "    Category $categoryId: " . count($products) . " products\n";
}

// Test 2: Test related products for a specific product
echo "\n2. Testing related products functionality...\n";

// Test with product ID 3 (Handwoven Scarf) which should have related products
$productId = 3;
$product = $productModel->getById($productId);

if ($product) {
    echo "  ✓ Product found: " . $product['name'] . " (Category: " . $product['category_id'] . ")\n";
    
    // Get related products
    $relatedProducts = $productModel->getRelated($productId, $product['category_id']);
    echo "  ✓ Found " . count($relatedProducts) . " related products\n";
    
    if (count($relatedProducts) > 0) {
        foreach ($relatedProducts as $related) {
            echo "    - " . $related['name'] . "\n";
        }
    }
} else {
    echo "  ✗ Product with ID $productId not found\n";
}

// Test 3: Test related products with different product
echo "\n3. Testing related products for another product...\n";

// Test with product ID 5 (Silk Scarf)
$productId = 5;
$product = $productModel->getById($productId);

if ($product) {
    echo "  ✓ Product found: " . $product['name'] . " (Category: " . $product['category_id'] . ")\n";
    
    // Get related products
    $relatedProducts = $productModel->getRelated($productId, $product['category_id']);
    echo "  ✓ Found " . count($relatedProducts) . " related products\n";
    
    if (count($relatedProducts) > 0) {
        foreach ($relatedProducts as $related) {
            echo "    - " . $related['name'] . "\n";
        }
    }
} else {
    echo "  ✗ Product with ID $productId not found\n";
}

// Test 4: Test edge cases
echo "\n4. Testing edge cases...\n";

// Test with non-existent product ID
$relatedProducts = $productModel->getRelated(999, 1);
echo "  ✓ Related products for non-existent product: " . count($relatedProducts) . " items\n";

// Test with non-existent category
$relatedProducts = $productModel->getRelated(1, 999);
echo "  ✓ Related products for non-existent category: " . count($relatedProducts) . " items\n";

echo "\n=== Test Complete ===\n";
?>