<?php
session_start();

require_once 'core/shared/config/database.php';
require_once 'modules/user/models/admin.php';

// Test admin authentication
$adminModel = new Admin($pdo);

// Test with correct credentials
echo "Testing admin login with correct credentials...\n";
$admin = $adminModel->authenticate('admin', 'password');
if ($admin) {
    echo "Login successful!\n";
    print_r($admin);
} else {
    echo "Login failed!\n";
}

// Test with incorrect credentials
echo "\nTesting admin login with incorrect credentials...\n";
$admin = $adminModel->authenticate('admin', 'wrongpassword');
if ($admin) {
    echo "Login successful!\n";
    print_r($admin);
} else {
    echo "Login failed as expected!\n";
}
?>