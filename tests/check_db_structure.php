<?php
// Check the actual database structure

require_once __DIR__ . '/../core/shared/config/database.php';

if ($pdo) {
    echo "Database connection successful!\n\n";
    
    // Check table structure
    $stmt = $pdo->prepare("DESCRIBE products");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Products table structure:\n";
    foreach ($columns as $column) {
        echo "  - " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "\nChecking for specific fields:\n";
    
    // Check for specific fields
    $fieldsToCheck = ['stock_quantity', 'whatsappNumber', 'is_active'];
    foreach ($fieldsToCheck as $field) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $field) {
                $found = true;
                break;
            }
        }
        echo "  - $field: " . ($found ? "FOUND" : "NOT FOUND") . "\n";
    }
    
    // Show first product data
    echo "\nSample product data:\n";
    $stmt = $pdo->prepare("SELECT * FROM products LIMIT 1");
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        foreach ($product as $key => $value) {
            echo "  - $key: $value\n";
        }
    }
} else {
    echo "Database connection failed!\n";
}
?>