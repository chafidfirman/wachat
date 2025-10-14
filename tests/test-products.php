<?php
// Include configuration
require_once 'config.php';

// Read products from JSON file
$productsJson = file_get_contents(DATA_PATH . '/products.json');
$products = json_decode($productsJson, true);

// Display the products
echo "<h1>Products in JSON file:</h1>";
echo "<pre>";
print_r($products);
echo "</pre>";

// Count products
echo "<p>Total products: " . count($products) . "</p>";
?>