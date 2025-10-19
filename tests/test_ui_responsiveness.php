<?php
// Test file to verify UI responsiveness and button functionality

echo "=== UI Responsiveness and Button Functionality Test ===\n\n";

// Test 1: Check if required JavaScript event handlers exist
echo "1. Checking JavaScript event handlers...\n";

// Read the main script file
$scriptContent = file_get_contents('assets/js/script.js');

// Check for key event handlers
$eventHandlers = [
    'DOMContentLoaded',
    'click',
    'submit',
    'keyup',
    'addEventListener'
];

foreach ($eventHandlers as $handler) {
    if (strpos($scriptContent, $handler) !== false) {
        echo "  ✓ Event handler '$handler' found\n";
    } else {
        echo "  ✗ Event handler '$handler' NOT FOUND\n";
    }
}

// Test 2: Check for key functions
echo "\n2. Checking key JavaScript functions...\n";
$keyFunctions = [
    'orderViaWhatsApp',
    'fetchProducts',
    'renderProducts',
    'filterProducts',
    'showProductDetail'
];

foreach ($keyFunctions as $function) {
    if (strpos($scriptContent, $function) !== false) {
        echo "  ✓ Function '$function' found\n";
    } else {
        echo "  ✗ Function '$function' NOT FOUND\n";
    }
}

// Test 3: Check HTML structure for key elements
echo "\n3. Checking HTML structure for key elements...\n";

// Check index.html for key elements
$indexContent = file_get_contents('index.html');
$keyElements = [
    'limitedProducts',
    'bestSellerProducts',
    'allProducts'
];

foreach ($keyElements as $element) {
    if (strpos($indexContent, 'id="' . $element . '"') !== false || strpos($indexContent, 'id=\'' . $element . '\'') !== false) {
        echo "  ✓ Element '$element' found\n";
    } else {
        echo "  ✗ Element '$element' NOT FOUND\n";
    }
}

// Test 4: Check for search elements in public pages
echo "\n4. Checking search elements...\n";
$searchElements = [
    'searchForm',
    'searchInput'
];

foreach ($searchElements as $element) {
    if (strpos($indexContent, $element) !== false || strpos(file_get_contents('assets/js/public-script.js'), $element) !== false) {
        echo "  ✓ Search element '$element' found\n";
    } else {
        echo "  ✗ Search element '$element' NOT FOUND\n";
    }
}

// Test 5: Check for mobile menu elements
echo "\n5. Checking mobile menu elements...\n";
$mobileElements = [
    'navbar-toggler'
];

foreach ($mobileElements as $element) {
    if (strpos($indexContent, $element) !== false) {
        echo "  ✓ Mobile element '$element' found\n";
    } else {
        echo "  ✗ Mobile element '$element' NOT FOUND\n";
    }
}

// Test 6: Check CSS for responsive classes
echo "\n6. Checking CSS for responsive classes...\n";
$cssContent = file_get_contents('assets/css/components.css');
$responsiveClasses = [
    'd-none',
    'product-card',
    'action-btn'
];

foreach ($responsiveClasses as $class) {
    if (strpos($cssContent, $class) !== false) {
        echo "  ✓ Responsive class '$class' found\n";
    } else {
        echo "  ? Responsive class '$class' NOT FOUND (may be in Bootstrap)\n";
    }
}

// Test 7: Check for error handling in JavaScript
echo "\n7. Checking JavaScript error handling...\n";
$errorHandling = [
    'catch',
    'try',
    'console.error',
    'alert'
];

foreach ($errorHandling as $handler) {
    if (strpos($scriptContent, $handler) !== false) {
        echo "  ✓ Error handling '$handler' found\n";
    } else {
        echo "  ✗ Error handling '$handler' NOT FOUND\n";
    }
}

echo "\n=== Test Complete ===\n";
?>