<?php
// Test file to verify admin AJAX login functionality

session_start();

// Simulate an AJAX login request
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'admin';
$_POST['password'] = 'password';

// Capture output
ob_start();

// Include the admin controller
require_once 'core/shared/config/database.php';
require_once 'modules/user/controllers/admin-controller.php';

// Create controller instance
$adminController = new AdminController($pdo);

// Test login method
$adminController->login();

// Get the output
$output = ob_get_clean();

// Check if we have a JSON response
if (!empty($output)) {
    $json = json_decode($output, true);
    if ($json && isset($json['success'])) {
        echo "AJAX Login Test:\n";
        echo "Success: " . ($json['success'] ? 'Yes' : 'No') . "\n";
        echo "Message: " . ($json['message'] ?? 'No message') . "\n";
    } else {
        echo "Non-JSON response received:\n";
        echo $output . "\n";
    }
} else {
    echo "No output received from login method\n";
}

// Test with incorrect credentials
echo "\n--- Testing with incorrect credentials ---\n";
$_POST['password'] = 'wrongpassword';

// Capture output
ob_start();

// Test login method
$adminController->login();

// Get the output
$output = ob_get_clean();

// Check if we have a JSON response
if (!empty($output)) {
    $json = json_decode($output, true);
    if ($json && isset($json['success'])) {
        echo "AJAX Login Test (Wrong Password):\n";
        echo "Success: " . ($json['success'] ? 'Yes' : 'No') . "\n";
        echo "Message: " . ($json['message'] ?? 'No message') . "\n";
    } else {
        echo "Non-JSON response received:\n";
        echo $output . "\n";
    }
} else {
    echo "No output received from login method\n";
}
?>