<?php
// Test the logging functions directly
require_once 'core/shared/helpers/debug_helper.php';

// Test all logging functions
logError("Test error message");
logNavigationError("Test Menu", "/test-page.html");
logQuery("SELECT * FROM products WHERE id = ?", [123]);
logFormSubmission("Test Form", ["name" => "Test User", "email" => "test@example.com"]);
logDeleteOperation("products", 456);
logAdminAccess("admin/dashboard.php", "admin_user");

echo "Log files created successfully!\n";
echo "Check the logs directory for the following files:\n";
echo "- error.log\n";
echo "- navigation.log\n";
echo "- query.log\n";
echo "- form.log\n";
echo "- delete.log\n";
echo "- admin.log\n";

// Test bug checklist
saveBugChecklist();
echo "- bug_checklist.json\n";

echo "\nYou can view these files directly or through a web browser.\n";
?>