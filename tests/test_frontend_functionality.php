<?php
// Test file to verify frontend functionality

echo "=== Frontend Functionality Test ===\n\n";

// Test 1: Check if required JavaScript files exist
echo "1. Checking JavaScript files...\n";
$jsFiles = [
    'assets/js/script.js',
    'assets/js/public-script.js'
];

foreach ($jsFiles as $file) {
    if (file_exists($file)) {
        echo "  ✓ $file exists\n";
    } else {
        echo "  ✗ $file NOT FOUND\n";
    }
}

// Test 2: Check if required CSS files exist
echo "\n2. Checking CSS files...\n";
$cssFiles = [
    'assets/css/style.css',
    'assets/css/components.css',
    'assets/css/public-style.css'
];

foreach ($cssFiles as $file) {
    if (file_exists($file)) {
        echo "  ✓ $file exists\n";
    } else {
        echo "  ✗ $file NOT FOUND\n";
    }
}

// Test 3: Check if main components exist
echo "\n3. Checking main components...\n";
$components = [
    'core/shared/components/header.php',
    'core/shared/components/footer.php',
    'core/shared/components/navbar.php'
];

foreach ($components as $file) {
    if (file_exists($file)) {
        echo "  ✓ $file exists\n";
    } else {
        echo "  ✗ $file NOT FOUND\n";
    }
}

// Test 4: Check if main pages exist
echo "\n4. Checking main pages...\n";
$pages = [
    'index.html',
    'product.php',
    'about.html',
    'contact.html'
];

foreach ($pages as $file) {
    if (file_exists($file)) {
        echo "  ✓ $file exists\n";
    } else {
        echo "  ✗ $file NOT FOUND\n";
    }
}

// Test 5: Check if API endpoints are accessible
echo "\n5. Testing API endpoints...\n";
$apiEndpoints = [
    'http://localhost/wachat/api/v1/products',
    'http://localhost/wachat/api/v1/products/1'
];

foreach ($apiEndpoints as $endpoint) {
    $response = @file_get_contents($endpoint);
    if ($response !== false) {
        echo "  ✓ $endpoint accessible\n";
    } else {
        echo "  ✗ $endpoint NOT ACCESSIBLE\n";
    }
}

echo "\n=== Test Complete ===\n";
?>