<?php
session_start();

// Include configuration
require_once __DIR__ . '/../config.php';

// Include database connection
require_once __DIR__ . '/../core/shared/config/database.php';

// Include controllers
require_once __DIR__ . '/../modules/user/controllers/admin-controller.php';

// Initialize controller with database connection
$controller = new AdminController($pdo);

// Route based on path parameter
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

// Handle different routes
switch ($path) {
    case '':
    case 'dashboard':
        $controller->dashboard();
        break;
        
    case 'login':
        $controller->login();
        break;
        
    case 'logout':
        $controller->logout();
        break;
        
    case 'products':
        $controller->products();
        break;
        
    case 'categories':
        $controller->categories();
        break;
        
    case 'orders':
        $controller->orders();
        break;
        
    case 'users':
        $controller->users();
        break;
        
    case 'reports':
        $controller->reports();
        break;
        
    case 'analytics':
        $controller->analytics();
        break;
        
    case 'settings':
        $controller->settings();
        break;
        
    case 'logs':
        $controller->logs();
        break;
        
    case 'error-monitoring':
        $controller->errorMonitoring();
        break;
        
    // Environment management
    case 'environment':
        // Check if admin is logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin/login');
            exit;
        }
        
        // Include environment management
        require_once __DIR__ . '/environment.php';
        break;
        
    default:
        // Check if admin is logged in for other paths
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin/login');
            exit;
        }
        
        // For any other path, show 404
        http_response_code(404);
        echo "Page not found";
        break;
}
?>