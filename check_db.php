<?php
require_once 'core/shared/config/database.php';

if ($pdo) {
    echo "Database connection successful!\n";
    
    // Check products
    try {
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Products in database:\n";
        print_r($products);
    } catch (PDOException $e) {
        echo "Error querying products: " . $e->getMessage() . "\n";
    }
    
    // Check categories
    try {
        $stmt = $pdo->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nCategories in database:\n";
        print_r($categories);
    } catch (PDOException $e) {
        echo "Error querying categories: " . $e->getMessage() . "\n";
    }
} else {
    echo "Database connection failed.\n";
}
?>