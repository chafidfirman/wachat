<?php
/**
 * Error Handling Example
 * This file demonstrates proper error handling patterns
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../core/shared/helpers/debug_helper.php';

// Example 1: Input validation
function validateProductId($id) {
    // Validate that ID is not empty, is numeric, and is positive
    if (empty($id)) {
        logNavigationError("Product Detail", "Missing Product ID");
        return false;
    }
    
    if (!is_numeric($id)) {
        logNavigationError("Product Detail", "Non-numeric Product ID: " . $id);
        return false;
    }
    
    if ($id <= 0) {
        logNavigationError("Product Detail", "Invalid Product ID: " . $id);
        return false;
    }
    
    return true;
}

// Example 2: Exception handling with try-catch
function loadProductData($id) {
    try {
        // Validate input first
        if (!validateProductId($id)) {
            return null;
        }
        
        // Simulate database operation
        // In a real application, this would be:
        // $product = $this->productModel->getById($id);
        
        // Simulate different scenarios
        if ($id == 1) {
            return ['id' => 1, 'name' => 'Sample Product', 'price' => 29.99];
        } elseif ($id == 2) {
            // Simulate database error
            throw new Exception("Database connection failed");
        } else {
            // Simulate product not found
            return null;
        }
    } catch (Exception $e) {
        // Log the error for debugging
        logError("Failed to load product data: " . $e->getMessage());
        
        // Return false to indicate system error
        return false;
    }
}

// Example 3: Controller method with proper error handling
function displayProduct($id) {
    // Validate input
    $product = loadProductData($id);
    
    if ($product === false) {
        // System error occurred
        $error = "We're experiencing technical difficulties. Please try again later.";
        include __DIR__ . '/../modules/product/views/404.php';
        return;
    }
    
    if ($product === null) {
        // Product not found
        include __DIR__ . '/../modules/product/views/404.php';
        return;
    }
    
    // Product found, display it
    // include __DIR__ . '/../modules/product/views/product.php';
    echo "<h1>" . htmlspecialchars($product['name']) . "</h1>";
    echo "<p>Price: $" . number_format($product['price'], 2) . "</p>";
}

// Example 4: Session validation for admin areas
function validateAdminSession() {
    try {
        // Check if session exists
        if (!isset($_SESSION['admin_id'])) {
            return ['valid' => false, 'reason' => 'not_logged_in'];
        }
        
        // Validate session data
        $adminId = $_SESSION['admin_id'];
        if (empty($adminId) || !is_numeric($adminId) || $adminId <= 0) {
            return ['valid' => false, 'reason' => 'invalid_session'];
        }
        
        // In a real application, you would check if the admin still exists:
        // $admin = $this->adminModel->getById($adminId);
        // if (!$admin) {
        //     return ['valid' => false, 'reason' => 'admin_not_found'];
        // }
        
        // For this example, we'll assume session is valid
        return ['valid' => true, 'admin_id' => $adminId];
    } catch (Exception $e) {
        logError("Error validating admin session: " . $e->getMessage());
        return ['valid' => false, 'reason' => 'system_error'];
    }
}

// Example 5: Admin controller method with proper error handling
function adminDashboard() {
    // Validate session
    $session = validateAdminSession();
    
    if (!$session['valid']) {
        if ($session['reason'] === 'not_logged_in' || $session['reason'] === 'invalid_session') {
            // Redirect to login
            header('Location: ' . site_url('admin/login'));
            exit;
        } elseif ($session['reason'] === 'admin_not_found') {
            // Destroy session and redirect to login
            session_destroy();
            header('Location: ' . site_url('admin/login'));
            exit;
        } else {
            // System error
            $error = "Unable to verify your credentials. Please try again.";
            header('Location: ' . site_url('admin/login'));
            exit;
        }
    }
    
    // Session is valid, load dashboard data
    try {
        // $admin = $this->adminModel->getById($session['admin_id']);
        // $products = $this->productModel->getAll();
        // $categories = $this->categoryModel->getAll();
        
        // include __DIR__ . '/../modules/user/views/admin/dashboard.php';
        echo "<h1>Admin Dashboard</h1>";
        echo "<p>Welcome, Admin!</p>";
    } catch (Exception $e) {
        logError("Error loading admin dashboard: " . $e->getMessage());
        $error = "Failed to load dashboard data. Please try again later.";
        header('Location: ' . site_url('admin/login'));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Handling Example</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Error Handling Example</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h2>Product Display Examples</h2>
                <p>Testing product display with different ID values:</p>
                
                <ul>
                    <li><a href="?action=product&id=1">Valid Product (ID: 1)</a></li>
                    <li><a href="?action=product&id=-1">Invalid Product (ID: -1)</a></li>
                    <li><a href="?action=product&id=abc">Invalid Product (ID: abc)</a></li>
                    <li><a href="?action=product&id=999">Not Found Product (ID: 999)</a></li>
                </ul>
                
                <?php
                if (isset($_GET['action']) && $_GET['action'] === 'product') {
                    $id = $_GET['id'] ?? null;
                    echo "<h3>Result for ID: " . htmlspecialchars($id) . "</h3>";
                    displayProduct($id);
                }
                ?>
            </div>
            
            <div class="col-md-6">
                <h2>Admin Dashboard Example</h2>
                <p>Testing admin dashboard access:</p>
                
                <ul>
                    <li><a href="?action=admin">Try Admin Dashboard</a></li>
                </ul>
                
                <?php
                if (isset($_GET['action']) && $_GET['action'] === 'admin') {
                    echo "<h3>Admin Dashboard Result</h3>";
                    adminDashboard();
                }
                ?>
                
                <h2 class="mt-4">Error Handling Benefits</h2>
                <ul>
                    <li>Input validation prevents invalid data processing</li>
                    <li>Exception handling prevents system crashes</li>
                    <li>Proper logging helps with debugging</li>
                    <li>Graceful error responses improve user experience</li>
                    <li>Session validation ensures security</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>