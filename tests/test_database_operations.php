<?php
// Test file to verify database operations and error handling

echo "=== Database Operations and Error Handling Test ===\n\n";

// Test 1: Test database connection
echo "1. Testing database connection...\n";

require_once 'core/shared/config/database.php';

if ($pdo) {
    echo "  ✓ Database connection successful\n";
    
    // Test simple query
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        $count = $stmt->fetchColumn();
        echo "  ✓ Database query successful: $count products found\n";
    } catch (Exception $e) {
        echo "  ✗ Database query failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "  ✗ Database connection failed\n";
}

// Test 2: Test database model operations
echo "\n2. Testing database model operations...\n";

require_once 'modules/product/models/product.php';

$productModel = new Product($pdo);

// Test getAll
try {
    $products = $productModel->getAll();
    echo "  ✓ getAll() successful: " . count($products) . " products retrieved\n";
} catch (Exception $e) {
    echo "  ✗ getAll() failed: " . $e->getMessage() . "\n";
}

// Test getById
try {
    $product = $productModel->getById(1);
    if ($product) {
        echo "  ✓ getById(1) successful: " . $product['name'] . "\n";
    } else {
        echo "  ⚠ getById(1) returned null\n";
    }
} catch (Exception $e) {
    echo "  ✗ getById(1) failed: " . $e->getMessage() . "\n";
}

// Test search
try {
    $results = $productModel->search('Soap');
    echo "  ✓ search('Soap') successful: " . count($results) . " products found\n";
} catch (Exception $e) {
    echo "  ✗ search('Soap') failed: " . $e->getMessage() . "\n";
}

// Test getByCategory
try {
    $results = $productModel->getByCategory(1);
    echo "  ✓ getByCategory(1) successful: " . count($results) . " products found\n";
} catch (Exception $e) {
    echo "  ✗ getByCategory(1) failed: " . $e->getMessage() . "\n";
}

// Test 3: Test error handling with invalid operations
echo "\n3. Testing error handling...\n";

// Test with invalid product ID
try {
    $product = $productModel->getById(999999);
    if ($product === null) {
        echo "  ✓ getById with invalid ID correctly returned null\n";
    } else {
        echo "  ⚠ getById with invalid ID returned unexpected result\n";
    }
} catch (Exception $e) {
    echo "  ✓ getById with invalid ID properly threw exception: " . $e->getMessage() . "\n";
}

// Test with invalid category ID
try {
    $results = $productModel->getByCategory(999999);
    echo "  ✓ getByCategory with invalid ID successful: " . count($results) . " products found\n";
} catch (Exception $e) {
    echo "  ✓ getByCategory with invalid ID properly threw exception: " . $e->getMessage() . "\n";
}

// Test 4: Test database fallback functionality
echo "\n4. Testing database fallback functionality...\n";

// This is harder to test without actually breaking the database connection
// But we can check if the fallback logic exists
$productFile = 'modules/product/models/product.php';
$content = file_get_contents($productFile);

if (strpos($content, 'getAllFromJson') !== false) {
    echo "  ✓ JSON fallback methods exist in Product model\n";
} else {
    echo "  ✗ JSON fallback methods NOT FOUND in Product model\n";
}

// Test 5: Test database logging
echo "\n5. Testing database logging...\n";

// Test error logging function
$logDir = 'logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Test logging functions from debug_helper.php
require_once 'core/shared/helpers/debug_helper.php';

// Test logError
logError("Test error message");
if (file_exists($logDir . '/error.log')) {
    echo "  ✓ Error logging works\n";
} else {
    echo "  ✗ Error logging failed\n";
}

// Test logQuery
logQuery("SELECT * FROM products WHERE id = ?", [1]);
if (file_exists($logDir . '/query.log')) {
    echo "  ✓ Query logging works\n";
} else {
    echo "  ✗ Query logging failed\n";
}

echo "\n=== Test Complete ===\n";
?>