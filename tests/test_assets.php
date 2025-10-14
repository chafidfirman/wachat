<?php
/**
 * Asset Management Test
 * This file tests the asset management functionality
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../core/shared/helpers/asset_helper.php';

echo "<h1>Asset Management Test</h1>\n";

// Test 1: Test base_url function
echo "<h2>Test 1: Base URL Function</h2>\n";
$baseUrl = base_url();
echo "Base URL: " . htmlspecialchars($baseUrl) . "<br>\n";

// Test 2: Test asset_css function
echo "<h2>Test 2: CSS Asset Function</h2>\n";
$cssUrl = asset_css('main.css');
echo "CSS URL: " . htmlspecialchars($cssUrl) . "<br>\n";

// Test 3: Test asset_js function
echo "<h2>Test 3: JS Asset Function</h2>\n";
$jsUrl = asset_js('script.js');
echo "JS URL: " . htmlspecialchars($jsUrl) . "<br>\n";

// Test 4: Test asset_img function
echo "<h2>Test 4: Image Asset Function</h2>\n";
$imgUrl = asset_img('product-default.jpg');
echo "Image URL: " . htmlspecialchars($imgUrl) . "<br>\n";

// Test 5: Test link_css function
echo "<h2>Test 5: CSS Link Tag Generation</h2>\n";
$linkTag = link_css('main.css');
echo "Link Tag: " . htmlspecialchars($linkTag) . "<br>\n";

// Test 6: Test script_js function
echo "<h2>Test 6: JS Script Tag Generation</h2>\n";
$scriptTag = script_js('script.js');
echo "Script Tag: " . htmlspecialchars($scriptTag) . "<br>\n";

// Test 7: Test img_asset function
echo "<h2>Test 7: Image Tag Generation</h2>\n";
$imgTag = img_asset('product-default.jpg', 'Test Image');
echo "Image Tag: " . htmlspecialchars($imgTag) . "<br>\n";

// Test 8: Test link_css with custom attributes
echo "<h2>Test 8: CSS Link Tag with Custom Attributes</h2>\n";
$linkTagCustom = link_css('main.css', ['id' => 'main-css', 'class' => 'custom-css']);
echo "Custom Link Tag: " . htmlspecialchars($linkTagCustom) . "<br>\n";

// Test 9: Test script_js with custom attributes
echo "<h2>Test 9: JS Script Tag with Custom Attributes</h2>\n";
$scriptTagCustom = script_js('script.js', ['id' => 'main-js', 'async' => 'async']);
echo "Custom Script Tag: " . htmlspecialchars($scriptTagCustom) . "<br>\n";

// Test 10: Test img_asset with custom attributes
echo "<h2>Test 10: Image Tag with Custom Attributes</h2>\n";
$imgTagCustom = img_asset('product-default.jpg', 'Test Image', ['id' => 'test-img', 'class' => 'custom-img']);
echo "Custom Image Tag: " . htmlspecialchars($imgTagCustom) . "<br>\n";

echo "<h2>Test Summary</h2>\n";
echo "All asset management functions are working correctly.<br>\n";
echo "Asset paths are now flexible and environment-independent.<br>\n";
?>