<?php
// Test file to directly call the product method
require_once __DIR__ . '/../core/shared/config/database.php';
require_once __DIR__ . '/../modules/product/controllers/main-controller.php';

// Create controller instance
$mainController = new MainController($pdo);

// Call the product method with a test ID
$mainController->product(5);
?>