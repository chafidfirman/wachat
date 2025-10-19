<?php
// Script to set up the database with standardized schema

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'chatcart');

try {
    // Connect to MySQL server without specifying database
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop database if exists and create new one
    $pdo->exec("DROP DATABASE IF EXISTS " . DB_NAME);
    $pdo->exec("CREATE DATABASE " . DB_NAME);
    echo "Database '" . DB_NAME . "' created successfully!\n";
    
    // Connect to the newly created database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read SQL file - using the unified schema
    $sql = file_get_contents(__DIR__ . '/db/schema/chatcart.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    echo "Standardized database schema imported successfully!\n";
    
    echo "Database setup completed with standardized schema!\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>