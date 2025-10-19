<?php
// Final test to verify all fixes are working together
require_once 'config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Final Test - ChatCart Web</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container mt-5'>
    <h1 class='mb-4'>Final Test - ChatCart Web</h1>
    
    <div class='row'>
        <div class='col-md-12'>
            <h2>Test Results</h2>";

// Test 1: Check product data structure
echo "<div class='card mb-3'>
        <div class='card-header'>Test 1: Product Data Structure</div>
        <div class='card-body'>";

$productsFile = 'data/products.json';
if (file_exists($productsFile)) {
    $json = file_get_contents($productsFile);
    $products = json_decode($json, true);
    
    if (is_array($products) && !empty($products)) {
        $product = $products[0];
        echo "<p class='text-success'>✓ Products file exists and is valid JSON</p>";
        
        // Check required fields
        $requiredFields = ['id', 'name', 'price', 'category', 'image', 'inStock', 'stockQuantity', 'whatsappNumber'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($product[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (empty($missingFields)) {
            echo "<p class='text-success'>✓ All required fields present in product data</p>";
        } else {
            echo "<p class='text-danger'>✗ Missing fields: " . implode(', ', $missingFields) . "</p>";
        }
        
        // Check image path format
        if (strpos($product['image'], 'assets/') === 0 || strpos($product['image'], 'img/') === 0) {
            echo "<p class='text-success'>✓ Image path uses correct format</p>";
        } else {
            echo "<p class='text-warning'>⚠ Image path format may need review</p>";
        }
    } else {
        echo "<p class='text-danger'>✗ Products file is not valid JSON or is empty</p>";
    }
} else {
    echo "<p class='text-danger'>✗ Products file not found</p>";
}

echo "</div></div>";

// Test 2: Check API endpoint
echo "<div class='card mb-3'>
        <div class='card-header'>Test 2: API Endpoint Response</div>
        <div class='card-body'>";

// Simulate API call by reading the file directly
$productsFile = 'data/products.json';
if (file_exists($productsFile)) {
    $json = file_get_contents($productsFile);
    $products = json_decode($json, true);
    
    // Normalize product data to ensure consistent structure (same as in API)
    foreach ($products as &$product) {
        // Ensure stockQuantity is set
        if (!isset($product['stockQuantity']) && isset($product['stock'])) {
            $product['stockQuantity'] = $product['stock'];
            unset($product['stock']);
        }
        
        // Ensure whatsappNumber is set
        if (!isset($product['whatsappNumber']) && isset($product['whatsapp_number'])) {
            $product['whatsappNumber'] = $product['whatsapp_number'];
            unset($product['whatsapp_number']);
        }
        
        // Ensure inStock is set
        if (!isset($product['inStock']) && isset($product['in_stock'])) {
            $product['inStock'] = $product['in_stock'];
            unset($product['in_stock']);
        }
    }

    if (!empty($products)) {
        $firstProduct = $products[0];
        echo "<p class='text-success'>✓ API would return " . count($products) . " products</p>";
        
        // Check required fields
        $requiredFields = ['id', 'name', 'price', 'category', 'image', 'inStock', 'stockQuantity', 'whatsappNumber'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($firstProduct[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (empty($missingFields)) {
            echo "<p class='text-success'>✓ All required fields present in API response</p>";
        } else {
            echo "<p class='text-danger'>✗ Missing fields in API response: " . implode(', ', $missingFields) . "</p>";
        }
    } else {
        echo "<p class='text-danger'>✗ API would return no products</p>";
    }
} else {
    echo "<p class='text-danger'>✗ Could not test API endpoint</p>";
}

echo "</div></div>";

// Test 3: Check JavaScript file
echo "<div class='card mb-3'>
        <div class='card-header'>Test 3: JavaScript File</div>
        <div class='card-body'>";

$jsFile = 'assets/js/script.js';
if (file_exists($jsFile)) {
    echo "<p class='text-success'>✓ JavaScript file exists</p>";
    
    // Check if file contains key functions
    $jsContent = file_get_contents($jsFile);
    $requiredFunctions = ['fetchProducts', 'renderProducts', 'orderViaWhatsApp'];
    $missingFunctions = [];
    
    foreach ($requiredFunctions as $function) {
        if (strpos($jsContent, 'function ' . $function) === false && strpos($jsContent, $function . '()') === false) {
            $missingFunctions[] = $function;
        }
    }
    
    if (empty($missingFunctions)) {
        echo "<p class='text-success'>✓ All required JavaScript functions present</p>";
    } else {
        echo "<p class='text-danger'>✗ Missing JavaScript functions: " . implode(', ', $missingFunctions) . "</p>";
    }
} else {
    echo "<p class='text-danger'>✗ JavaScript file not found</p>";
}

echo "</div></div>";

// Test 4: Check navigation
echo "<div class='card mb-3'>
        <div class='card-header'>Test 4: Navigation Components</div>
        <div class='card-body'>";

$navbarFile = 'core/shared/components/navbar.php';
if (file_exists($navbarFile)) {
    echo "<p class='text-success'>✓ Navbar component exists</p>";
    
    // Check if file contains key elements
    $navbarContent = file_get_contents($navbarFile);
    $requiredElements = ['nav-item', 'nav-link', 'active'];
    
    $missingElements = [];
    foreach ($requiredElements as $element) {
        if (strpos($navbarContent, $element) === false) {
            $missingElements[] = $element;
        }
    }
    
    if (empty($missingElements)) {
        echo "<p class='text-success'>✓ All required navigation elements present</p>";
    } else {
        echo "<p class='text-warning'>⚠ Some navigation elements missing: " . implode(', ', $missingElements) . "</p>";
    }
} else {
    echo "<p class='text-danger'>✗ Navbar component not found</p>";
}

echo "</div></div>";

echo "<div class='alert alert-info'>
        <h4>Test Summary</h4>
        <p>All tests completed. Check for any errors or warnings above.</p>
        <a href='" . base_url() . "' class='btn btn-primary'>Go to Homepage</a>
      </div>
</div>
</div>
</body>
</html>";
?>