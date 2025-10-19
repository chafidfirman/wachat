<?php
require_once 'core/shared/config/database.php';

if ($pdo) {
    echo "Database connection successful!\n";
    
    // Check admins
    try {
        $stmt = $pdo->query("SELECT * FROM admins");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Admins in database:\n";
        print_r($admins);
    } catch (PDOException $e) {
        echo "Error querying admins: " . $e->getMessage() . "\n";
    }
} else {
    echo "Database connection failed.\n";
}
?>