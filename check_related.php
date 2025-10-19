<?php
require_once 'core/shared/config/database.php';

if ($pdo) {
    echo "Database connection successful!\n";
    
    // Check if there are other products in the same category (Clothing - category_id = 1)
    try {
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.is_active = 1 AND p.id != ?");
        $stmt->execute([1, 3]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Other products in Clothing category (excluding product 3):\n";
        print_r($products);
        
        if (empty($products)) {
            echo "No other products found in the Clothing category.\n";
        }
    } catch (PDOException $e) {
        echo "Error querying related products: " . $e->getMessage() . "\n";
    }
    
    // Check all products and their categories
    try {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.category_id, p.id");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nAll products with categories:\n";
        foreach ($products as $product) {
            echo "ID: {$product['id']}, Name: {$product['name']}, Category: {$product['category_name']} (ID: {$product['category_id']})\n";
        }
    } catch (PDOException $e) {
        echo "Error querying all products: " . $e->getMessage() . "\n";
    }
} else {
    echo "Database connection failed.\n";
}
?>