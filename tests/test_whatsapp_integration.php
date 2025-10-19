<?php
// Test file to verify WhatsApp integration functionality

echo "=== WhatsApp Integration Test ===\n\n";

// Test 1: Check if WhatsApp function exists in JavaScript
echo "1. Checking JavaScript WhatsApp function...\n";

$scriptContent = file_get_contents('assets/js/script.js');
if (strpos($scriptContent, 'orderViaWhatsApp') !== false) {
    echo "  ✓ orderViaWhatsApp function found in script.js\n";
} else {
    echo "  ✗ orderViaWhatsApp function NOT FOUND in script.js\n";
}

// Check index.html version
$indexContent = file_get_contents('index.html');
if (strpos($indexContent, 'orderViaWhatsApp') !== false) {
    echo "  ✓ orderViaWhatsApp function found in index.html\n";
} else {
    echo "  ✗ orderViaWhatsApp function NOT FOUND in index.html\n";
}

// Test 2: Test WhatsApp URL generation
echo "\n2. Testing WhatsApp URL generation...\n";

// Simulate the JavaScript function in PHP
function generateWhatsAppUrl($productId, $whatsappNumber, $productName, $price) {
    // Format price in IDR
    $formattedPrice = number_format($price, 0, ',', '.');
    $message = urlencode("Halo, saya ingin memesan {$productName} dengan harga Rp {$formattedPrice}. Apakah masih tersedia?");
    return "https://wa.me/{$whatsappNumber}?text={$message}";
}

// Test with sample data
$testData = [
    'id' => 1,
    'whatsappNumber' => '6281234567890',
    'name' => 'Test Product',
    'price' => 100000
];

$whatsappUrl = generateWhatsAppUrl(
    $testData['id'],
    $testData['whatsappNumber'],
    $testData['name'],
    $testData['price']
);

if (strpos($whatsappUrl, 'https://wa.me/') !== false && strpos($whatsappUrl, 'text=') !== false) {
    echo "  ✓ WhatsApp URL generated correctly\n";
    echo "  ✓ URL: $whatsappUrl\n";
} else {
    echo "  ✗ WhatsApp URL generation failed\n";
}

// Test 3: Test API endpoint for product details
echo "\n3. Testing API endpoint for product details...\n";

$productUrl = 'http://localhost/wachat/api/v1/products/1';
$response = @file_get_contents($productUrl);

if ($response !== false) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        $product = $data['data'];
        echo "  ✓ Product API endpoint works\n";
        
        // Check if WhatsApp number is present
        if (isset($product['whatsappNumber']) || isset($product['whatsapp_number'])) {
            echo "  ✓ WhatsApp number found in product data\n";
        } else {
            echo "  ⚠ WhatsApp number NOT FOUND in product data\n";
        }
    } else {
        echo "  ✗ Product API endpoint failed\n";
    }
} else {
    echo "  ✗ Product API endpoint not accessible\n";
}

// Test 4: Test database model for WhatsApp number
echo "\n4. Testing database model for WhatsApp data...\n";

require_once 'core/shared/config/database.php';
require_once 'modules/product/models/product.php';

$productModel = new Product($pdo);
$product = $productModel->getById(1);

if ($product) {
    echo "  ✓ Product retrieved from database\n";
    
    // Check for WhatsApp number
    if (isset($product['whatsappNumber']) && !empty($product['whatsappNumber'])) {
        echo "  ✓ WhatsApp number found in database product: " . $product['whatsappNumber'] . "\n";
    } else {
        echo "  ⚠ WhatsApp number NOT FOUND in database product\n";
        echo "  ℹ This is expected as the seed data doesn't include WhatsApp numbers\n";
    }
} else {
    echo "  ✗ Failed to retrieve product from database\n";
}

// Test 5: Test WhatsApp click logging
echo "\n5. Testing WhatsApp click logging...\n";

try {
    $result = $productModel->logWhatsAppClick(1);
    if ($result) {
        echo "  ✓ WhatsApp click logged successfully\n";
    } else {
        echo "  ⚠ WhatsApp click logging returned false\n";
    }
} catch (Exception $e) {
    echo "  ✗ WhatsApp click logging failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
?>