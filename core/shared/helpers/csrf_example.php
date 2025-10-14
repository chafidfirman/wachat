<?php
/**
 * CSRF Protection Example
 * This file demonstrates how to use CSRF protection in forms
 */

require_once __DIR__ . '/csrf_helper.php';

// Example of generating a CSRF token for use in forms
function generateCsrfTokenExample() {
    return generateCsrfToken();
}

// Example of a secure form with CSRF protection
function renderSecureFormExample() {
    $csrfToken = generateCsrfToken();
    echo '<form method="POST" action="/api/v1/products">';
    echo csrfField(); // This generates: <input type="hidden" name="csrf_token" value="TOKEN">
    echo '<input type="text" name="name" placeholder="Product Name" required>';
    echo '<input type="number" name="price" placeholder="Price" step="0.01" required>';
    echo '<textarea name="description" placeholder="Description" required></textarea>';
    echo '<button type="submit">Create Product</button>';
    echo '</form>';
}

// Example of validating CSRF token in a controller
function validateCsrfTokenExample($token) {
    if (!validateCsrfToken($token)) {
        // Log the failed attempt
        error_log("CSRF validation failed at " . date('Y-m-d H:i:s'));
        
        // Return an error response
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid or missing CSRF token'
        ]);
        exit;
    }
    
    // CSRF token is valid, continue with the request
    return true;
}

// Example of using CSRF protection with AJAX requests
function renderAjaxFormExample() {
    $csrfToken = generateCsrfToken();
    echo '<script>';
    echo 'function createProduct() {';
    echo '  const data = {';
    echo '    name: document.getElementById("name").value,';
    echo '    price: document.getElementById("price").value,';
    echo '    description: document.getElementById("description").value,';
    echo '    csrf_token: "' . $csrfToken . '"'; // Include CSRF token in the request
    echo '  };';
    echo '';
    echo '  fetch("/api/v1/products", {';
    echo '    method: "POST",';
    echo '    headers: {';
    echo '      "Content-Type": "application/json",';
    echo '      "X-CSRF-Token": "' . $csrfToken . '"'; // Alternative: send token in header
    echo '    },';
    echo '    body: JSON.stringify(data)';
    echo '  })';
    echo '  .then(response => response.json())';
    echo '  .then(data => {';
    echo '    console.log("Success:", data);';
    echo '  })';
    echo '  .catch(error => {';
    echo '    console.error("Error:", error);';
    echo '  });';
    echo '}';
    echo '</script>';
}
?>