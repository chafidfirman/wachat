<?php
// Test file to verify admin AJAX login functionality with fresh session

// Destroy any existing session
session_start();
session_destroy();

// Start a new session
session_start();

// Simulate an AJAX login request
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'admin';
$_POST['password'] = 'password';

// Include the admin controller
require_once 'core/shared/config/database.php';
require_once 'modules/user/controllers/admin-controller.php';

// Capture output
ob_start();

// Create controller instance
$adminController = new AdminController($pdo);

// Test login method
$adminController->login();

// Get the output
$output = ob_get_clean();

echo "=== Admin AJAX Login Test ===\n";

// Check if we have a JSON response
if (!empty($output)) {
    $json = json_decode($output, true);
    if ($json && isset($json['success'])) {
        echo "Success: " . ($json['success'] ? 'Yes' : 'No') . "\n";
        echo "Message: " . ($json['message'] ?? 'No message') . "\n";
        
        // Check if session is set
        if (isset($_SESSION['admin_id'])) {
            echo "Session admin_id: " . $_SESSION['admin_id'] . "\n";
        }
        if (isset($_SESSION['admin_logged_in'])) {
            echo "Session admin_logged_in: " . ($_SESSION['admin_logged_in'] ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "Non-JSON response received:\n";
        echo $output . "\n";
    }
} else {
    echo "No output received from login method\n";
}

// Test with incorrect credentials
echo "\n--- Testing with incorrect credentials ---\n";

// Destroy session and start fresh
session_destroy();
session_start();

$_POST['password'] = 'wrongpassword';

// Capture output
ob_start();

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