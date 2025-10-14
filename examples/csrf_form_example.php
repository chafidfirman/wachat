<?php
/**
 * CSRF Form Example
 * This file demonstrates how to create a secure form with CSRF protection
 */

// Start session for CSRF token storage
session_start();

// Include the CSRF helper
require_once __DIR__ . '/../core/shared/helpers/csrf_helper.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die('CSRF token validation failed!');
    }
    
    // Process form data (in a real application, you would sanitize and validate all inputs)
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // In a real application, you would save this data to the database
    echo "<h2>Form Submitted Successfully!</h2>";
    echo "<p>Name: " . htmlspecialchars($name) . "</p>";
    echo "<p>Price: " . htmlspecialchars($price) . "</p>";
    echo "<p>Description: " . htmlspecialchars($description) . "</p>";
    echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Submit another form</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSRF Protection Form Example</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Secure Product Form</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- CSRF Token Field -->
                            <?= csrfField() ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">How CSRF Protection Works</h3>
                    </div>
                    <div class="card-body">
                        <p>This form includes CSRF protection to prevent cross-site request forgery attacks:</p>
                        <ul>
                            <li>A hidden field containing a secure token is automatically added to the form</li>
                            <li>When the form is submitted, the token is validated on the server</li>
                            <li>If the token is missing or invalid, the request is rejected</li>
                        </ul>
                        <p>View the page source to see the hidden CSRF token field.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>