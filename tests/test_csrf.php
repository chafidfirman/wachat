<?php
/**
 * CSRF Protection Test
 * This file tests the CSRF protection functionality
 */

require_once __DIR__ . '/../core/shared/helpers/csrf_helper.php';

// Start session for CSRF token storage
session_start();

echo "<h1>CSRF Protection Test</h1>\n";

// Test 1: Generate a CSRF token
echo "<h2>Test 1: Generate CSRF Token</h2>\n";
$token = generateCsrfToken();
echo "Generated token: " . htmlspecialchars($token) . "<br>\n";
echo "Token length: " . strlen($token) . " characters<br>\n";

// Test 2: Validate the correct token
echo "<h2>Test 2: Validate Correct Token</h2>\n";
$isValid = validateCsrfToken($token);
echo "Token validation result: " . ($isValid ? "VALID" : "INVALID") . "<br>\n";

// Test 3: Validate an incorrect token
echo "<h2>Test 3: Validate Incorrect Token</h2>\n";
$isValid = validateCsrfToken("invalid_token");
echo "Invalid token validation result: " . ($isValid ? "VALID" : "INVALID") . "<br>\n";

// Test 4: Generate CSRF field
echo "<h2>Test 4: Generate CSRF Field</h2>\n";
$field = csrfField();
echo "Generated field: " . htmlspecialchars($field) . "<br>\n";

// Test 5: Test requireValidCsrfToken with valid token
echo "<h2>Test 5: Test requireValidCsrfToken with Valid Token</h2>\n";
echo "Testing valid token... ";
ob_start();
requireValidCsrfToken($token);
$output = ob_get_clean();
if (empty($output)) {
    echo "PASSED - No error response for valid token<br>\n";
} else {
    echo "FAILED - Error response for valid token<br>\n";
    echo "Output: " . htmlspecialchars($output) . "<br>\n";
}

// Test 6: Test requireValidCsrfToken with invalid token
echo "<h2>Test 6: Test requireValidCsrfToken with Invalid Token</h2>\n";
echo "Testing invalid token... ";
ob_start();
requireValidCsrfToken("invalid_token");
$output = ob_get_clean();
if (!empty($output)) {
    echo "PASSED - Error response for invalid token<br>\n";
    // Decode JSON response
    $response = json_decode($output, true);
    if ($response && isset($response['error']['code']) && $response['error']['code'] == 403) {
        echo "Correct error code (403) returned<br>\n";
    } else {
        echo "Unexpected response format<br>\n";
    }
} else {
    echo "FAILED - No error response for invalid token<br>\n";
}

echo "<h2>Test Summary</h2>\n";
echo "All tests completed. Check results above.<br>\n";
?>