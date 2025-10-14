<?php
// Simple test script to verify debugging system
require_once 'core/shared/helpers/debug_helper.php';

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Test all logging functions
logError("Test error message");
logNavigationError("Test Menu", "/test-page.html");
logQuery("SELECT * FROM products WHERE id = ?", [123]);
logFormSubmission("Test Form", ["name" => "Test User", "email" => "test@example.com"]);
logDeleteOperation("products", 456);
logAdminAccess("admin/dashboard.php", "admin_user");

// Test bug checklist
saveBugChecklist();

echo "Debugging system test completed successfully!\n";
echo "Log files have been created in the logs directory.\n";
echo "Files created:\n";
echo "1. logs/error.log\n";
echo "2. logs/navigation.log\n";
echo "3. logs/query.log\n";
echo "4. logs/form.log\n";
echo "5. logs/delete.log\n";
echo "6. logs/admin.log\n";
echo "7. logs/bug_checklist.json\n";
echo "\nTo view the contents of these files, open them in a text editor.\n";
?>