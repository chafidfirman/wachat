<?php
// Test debugging system
require_once 'config.php';

// Test error logging
logError("This is a test error message");

// Test navigation error logging
logNavigationError("Test Menu", "/nonexistent-page.html");

// Test query logging
logQuery("SELECT * FROM products WHERE id = ?", [1]);

// Test form submission logging
logFormSubmission("Test Form", ["field1" => "value1", "field2" => "value2"]);

// Test delete operation logging
logDeleteOperation("Product", 5);

// Test admin access logging
logAdminAccess("dashboard.php", "admin");

// Test bug checklist generation
saveBugChecklist();

echo "<h1>Debugging System Test</h1>";
echo "<p>Debug logs have been written to the logs directory.</p>";
echo "<p><a href='logs/error.log'>View Error Log</a></p>";
echo "<p><a href='logs/navigation.log'>View Navigation Log</a></p>";
echo "<p><a href='logs/query.log'>View Query Log</a></p>";
echo "<p><a href='logs/form.log'>View Form Log</a></p>";
echo "<p><a href='logs/delete.log'>View Delete Log</a></p>";
echo "<p><a href='logs/admin.log'>View Admin Log</a></p>";
echo "<p><a href='logs/bug_checklist.json'>View Bug Checklist</a></p>";

// Test error generation
echo "<h2>Testing Error Handling</h2>";
echo "<p>Click the button below to generate a test error:</p>";
echo "<button onclick='generateError()'>Generate Error</button>";

// Test debug overlay
echo "<h2>Testing Debug Overlay</h2>";
echo "<p>Add ?debug=true to the URL to see the debug overlay.</p>";

?>

<script>
function generateError() {
    // This will cause a JavaScript error
    undefinedFunction();
}
</script>