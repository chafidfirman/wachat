<?php
// Comprehensive Test Suite for ChatCart Web Application

echo "========================================\n";
echo "ChatCart Web - Comprehensive Test Suite\n";
echo "========================================\n\n";

$testResults = [];
$passedTests = 0;
$failedTests = 0;

// Test 1: Database Connection
echo "1. Testing Database Connection...\n";
try {
    require_once 'core/shared/config/database.php';
    if ($pdo) {
        $testResults[] = "✓ Database connection successful";
        $passedTests++;
        
        // Test simple query
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        $count = $stmt->fetchColumn();
        $testResults[] = "✓ Database query successful: $count products found";
        $passedTests++;
    } else {
        $testResults[] = "✗ Database connection failed";
        $failedTests++;
    }
} catch (Exception $e) {
    $testResults[] = "✗ Database connection test failed: " . $e->getMessage();
    $failedTests++;
}

// Test 2: API Endpoints
echo "\n2. Testing API Endpoints...\n";
try {
    // Test products endpoint
    $response = @file_get_contents('http://localhost/wachat/api/v1/products');
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            $testResults[] = "✓ API products endpoint accessible";
            $passedTests++;
        } else {
            $testResults[] = "✗ API products endpoint returned error";
            $failedTests++;
        }
    } else {
        $testResults[] = "✗ API products endpoint not accessible";
        $failedTests++;
    }
    
    // Test specific product endpoint
    $response = @file_get_contents('http://localhost/wachat/api/v1/products/1');
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            $testResults[] = "✓ API product detail endpoint accessible";
            $passedTests++;
        } else {
            $testResults[] = "✗ API product detail endpoint returned error";
            $failedTests++;
        }
    } else {
        $testResults[] = "✗ API product detail endpoint not accessible";
        $failedTests++;
    }
} catch (Exception $e) {
    $testResults[] = "✗ API endpoint test failed: " . $e->getMessage();
    $failedTests++;
}

// Test 3: Product Model Functionality
echo "\n3. Testing Product Model Functionality...\n";
try {
    require_once 'modules/product/models/product.php';
    $productModel = new Product($pdo);
    
    // Test getAll
    $products = $productModel->getAll();
    if (is_array($products) && count($products) > 0) {
        $testResults[] = "✓ Product getAll() successful: " . count($products) . " products";
        $passedTests++;
    } else {
        $testResults[] = "✗ Product getAll() failed";
        $failedTests++;
    }
    
    // Test getById
    $product = $productModel->getById(1);
    if ($product && isset($product['name'])) {
        $testResults[] = "✓ Product getById(1) successful: " . $product['name'];
        $passedTests++;
    } else {
        $testResults[] = "✗ Product getById(1) failed";
        $failedTests++;
    }
    
    // Test search
    $results = $productModel->search('Soap');
    if (is_array($results)) {
        $testResults[] = "✓ Product search('Soap') successful: " . count($results) . " results";
        $passedTests++;
    } else {
        $testResults[] = "✗ Product search('Soap') failed";
        $failedTests++;
    }
    
    // Test getByCategory
    $results = $productModel->getByCategory(1);
    if (is_array($results)) {
        $testResults[] = "✓ Product getByCategory(1) successful: " . count($results) . " results";
        $passedTests++;
    } else {
        $testResults[] = "✗ Product getByCategory(1) failed";
        $failedTests++;
    }
    
    // Test related products
    $related = $productModel->getRelated(3, 1);
    if (is_array($related)) {
        $testResults[] = "✓ Product getRelated(3, 1) successful: " . count($related) . " related products";
        $passedTests++;
    } else {
        $testResults[] = "✗ Product getRelated(3, 1) failed";
        $failedTests++;
    }
} catch (Exception $e) {
    $testResults[] = "✗ Product model test failed: " . $e->getMessage();
    $failedTests++;
}

// Test 4: Admin Functionality
echo "\n4. Testing Admin Functionality...\n";
try {
    require_once 'modules/user/models/admin.php';
    $adminModel = new Admin($pdo);
    
    // Test authentication
    $admin = $adminModel->authenticate('admin', 'password');
    if ($admin) {
        $testResults[] = "✓ Admin authentication successful";
        $passedTests++;
    } else {
        $testResults[] = "✗ Admin authentication failed";
        $failedTests++;
    }
    
    // Test get by ID
    $admin = $adminModel->getById(1);
    if ($admin && isset($admin['username'])) {
        $testResults[] = "✓ Admin getById(1) successful: " . $admin['username'];
        $passedTests++;
    } else {
        $testResults[] = "✗ Admin getById(1) failed";
        $failedTests++;
    }
} catch (Exception $e) {
    $testResults[] = "✗ Admin functionality test failed: " . $e->getMessage();
    $failedTests++;
}

// Test 5: File Structure
echo "\n5. Testing File Structure...\n";
$requiredFiles = [
    'index.html',
    'assets/js/script.js',
    'assets/js/public-script.js',
    'assets/css/style.css',
    'assets/css/components.css',
    'core/shared/components/header.php',
    'core/shared/components/footer.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        $testResults[] = "✓ Required file exists: $file";
        $passedTests++;
    } else {
        $testResults[] = "✗ Required file missing: $file";
        $failedTests++;
    }
}

// Test 6: JavaScript Functionality
echo "\n6. Testing JavaScript Functionality...\n";
$scriptContent = file_get_contents('assets/js/script.js');
$requiredFunctions = [
    'orderViaWhatsApp',
    'fetchProducts',
    'renderProducts',
    'filterProducts'
];

foreach ($requiredFunctions as $function) {
    if (strpos($scriptContent, $function) !== false) {
        $testResults[] = "✓ Required JavaScript function found: $function";
        $passedTests++;
    } else {
        $testResults[] = "✗ Required JavaScript function missing: $function";
        $failedTests++;
    }
}

// Test 7: UI Elements
echo "\n7. Testing UI Elements...\n";
$indexContent = file_get_contents('index.html');
$requiredElements = [
    'limitedProducts',
    'bestSellerProducts',
    'allProducts'
];

foreach ($requiredElements as $element) {
    if (strpos($indexContent, 'id="' . $element . '"') !== false) {
        $testResults[] = "✓ Required UI element found: $element";
        $passedTests++;
    } else {
        $testResults[] = "✗ Required UI element missing: $element";
        $failedTests++;
    }
}

// Summary
echo "\n========================================\n";
echo "Test Results Summary\n";
echo "========================================\n";
foreach ($testResults as $result) {
    echo $result . "\n";
}

echo "\n========================================\n";
echo "Final Summary\n";
echo "========================================\n";
echo "Passed Tests: $passedTests\n";
echo "Failed Tests: $failedTests\n";
echo "Total Tests: " . ($passedTests + $failedTests) . "\n";

if ($failedTests == 0) {
    echo "\n🎉 ALL TESTS PASSED! The application is working correctly.\n";
} else {
    echo "\n⚠️  SOME TESTS FAILED. Please review the issues above.\n";
}

echo "========================================\n";
?>