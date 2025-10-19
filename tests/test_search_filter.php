<?php
// Test file to verify product search and filtering functionality

echo "=== Product Search and Filtering Test ===\n\n";

// Test 1: Test API search endpoint
echo "1. Testing API search endpoint...\n";

// Test search by keyword
$searchTerm = 'Soap';
$searchUrl = 'http://localhost/wachat/api/v1/products?search=' . urlencode($searchTerm);

$response = @file_get_contents($searchUrl);
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "  ✓ Search API with keyword '$searchTerm' works\n";
        echo "  ✓ Found " . count($data['data']) . " products\n";
    } else {
        echo "  ✗ Search API with keyword '$searchTerm' failed\n";
    }
} else {
    echo "  ✗ Search API with keyword '$searchTerm' not accessible\n";
}

// Test 2: Test API category filter
echo "\n2. Testing API category filter...\n";

// Test filter by category ID
$categoryUrl = 'http://localhost/wachat/api/v1/products?category=3'; // Beauty category

$response = @file_get_contents($categoryUrl);
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "  ✓ Category filter API works\n";
        echo "  ✓ Found " . count($data['data']) . " products in category\n";
    } else {
        echo "  ✗ Category filter API failed\n";
    }
} else {
    echo "  ✗ Category filter API not accessible\n";
}

// Test 3: Test database search functionality
echo "\n3. Testing database search functionality...\n";

require_once 'core/shared/config/database.php';
require_once 'modules/product/models/product.php';

$productModel = new Product($pdo);

// Test search by keyword
try {
    $results = $productModel->search('Soap');
    echo "  ✓ Database search for 'Soap' works\n";
    echo "  ✓ Found " . count($results) . " products\n";
} catch (Exception $e) {
    echo "  ✗ Database search for 'Soap' failed: " . $e->getMessage() . "\n";
}

// Test 4: Test database category filter
echo "\n4. Testing database category filter...\n";

try {
    $results = $productModel->getByCategory(3); // Beauty category
    echo "  ✓ Database category filter works\n";
    echo "  ✓ Found " . count($results) . " products in category\n";
} catch (Exception $e) {
    echo "  ✗ Database category filter failed: " . $e->getMessage() . "\n";
}

// Test 5: Test combined search and filter
echo "\n5. Testing combined search and filter...\n";

try {
    // First get all products
    $allProducts = $productModel->getAll();
    echo "  ✓ Retrieved all products: " . count($allProducts) . " items\n";
    
    // Test filtering in PHP (as done in MainController)
    $keyword = 'Soap';
    $categoryId = 3;
    
    // Filter by keyword first
    $keywordFiltered = array_filter($allProducts, function($product) use ($keyword) {
        return stripos($product['name'], $keyword) !== false || stripos($product['description'], $keyword) !== false;
    });
    
    echo "  ✓ Keyword filter found " . count($keywordFiltered) . " products\n";
    
    // Then filter by category
    $combinedFiltered = array_filter($keywordFiltered, function($product) use ($categoryId) {
        return isset($product['category_id']) && $product['category_id'] == $categoryId;
    });
    
    echo "  ✓ Combined filter found " . count($combinedFiltered) . " products\n";
    
} catch (Exception $e) {
    echo "  ✗ Combined search and filter failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
?>