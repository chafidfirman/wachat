<?php
// Test database connection

require_once 'core/shared/config/database.php';

if ($pdo) {
    echo "Database connection successful!\n";
    
    // Test a simple query
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        $count = $stmt->fetchColumn();
        echo "Found {$count} products in the database.\n";
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage() . "\n";
        
        // Try to check if tables exist
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Existing tables: " . implode(', ', $tables) . "\n";
        } catch (PDOException $e2) {
            echo "Failed to list tables: " . $e2->getMessage() . "\n";
        }
    }
} else {
    echo "Database connection failed.\n";
    echo "Please make sure XAMPP MySQL is running and the database is set up.\n";
    echo "You can run 'db/setup.php' to initialize the database.\n";
}
?>