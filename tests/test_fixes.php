<?php
// Test script to verify the fixes

// Include configuration
require_once __DIR__ . '/../config.php';

echo "<h1>Fix Verification Tests</h1>";

// Test 1: Check if products.json has the correct structure
echo "<h2>Test 1: Product Data Structure</h2>";
$productsFile = __DIR__ . '/../data/products.json';
if (file_exists($productsFile)) {
    $json = file_get_contents($productsFile);
    $products = json_decode($json, true);
    
    if (is_array($products) && !empty($products)) {
        $product = $products[0];
        echo "✓ Products file exists and is valid JSON<br>";
        
        // Check required fields
        $requiredFields = ['id', 'name', 'price', 'category', 'image', 'inStock', 'stockQuantity', 'whatsappNumber'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($product[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (empty($missingFields)) {
            echo "✓ All required fields present in product data<br>";
        } else {
            echo "✗ Missing fields: " . implode(', ', $missingFields) . "<br>";
        }
        
        // Check image path format
        if (strpos($product['image'], 'assets/') === 0) {
            echo "✓ Image path uses correct 'assets/' prefix<br>";
        } else {
            echo "⚠ Image path may need 'assets/' prefix<br>";
        }
    } else {
        echo "✗ Products file is not valid JSON or is empty<br>";
    }
} else {
    echo "✗ Products file not found<br>";
}

// Test 2: Check if API endpoint returns correct data
echo "<h2>Test 2: API Endpoint Response</h2>";
$apiUrl = base_url('api/products.php');
echo "API URL: " . $apiUrl . "<br>";

// Simulate API call
$products = [];
$productsFile = __DIR__ . '/../data/products.json';
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
}

if (!empty($products)) {
    $firstProduct = $products[0];
    echo "✓ API would return " . count($products) . " products<br>";
    
    // Check required fields
    $requiredFields = ['id', 'name', 'price', 'category', 'image', 'inStock', 'stockQuantity', 'whatsappNumber'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($firstProduct[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (empty($missingFields)) {
        echo "✓ All required fields present in API response<br>";
    } else {
        echo "✗ Missing fields in API response: " . implode(', ', $missingFields) . "<br>";
    }
} else {
    echo "✗ API would return no products<br>";
}

// Test 3: Check JavaScript file exists
echo "<h2>Test 3: JavaScript File</h2>";
$jsFile = __DIR__ . '/../assets/js/script.js';
if (file_exists($jsFile)) {
    echo "✓ JavaScript file exists<br>";
    
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
        echo "✓ All required JavaScript functions present<br>";
    } else {
        echo "✗ Missing JavaScript functions: " . implode(', ', $missingFunctions) . "<br>";
    }
} else {
    echo "✗ JavaScript file not found<br>";
}

echo "<h2>Test Summary</h2>";
echo "All tests completed. Check for any errors or warnings above.<br>";
echo "<br><a href='" . base_url() . "'>Go to Homepage</a>";
?>