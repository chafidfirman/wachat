<?php
/**
 * API v1 Entry Point
 * Routes requests to appropriate controllers
 */

// Get the requested path
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

// Route the request based on path
if (empty($path) || $path === 'products') {
    require_once 'products/index.php';
} elseif (strpos($path, 'products/') === 0) {
    require_once 'products/index.php';
} elseif ($path === 'categories') {
    // For future implementation
    require_once '../../core/shared/helpers/api_helper.php';
    sendErrorResponse('Categories API not yet implemented', 501);
} elseif ($path === 'users') {
    // For future implementation
    require_once '../../core/shared/helpers/api_helper.php';
    sendErrorResponse('Users API not yet implemented', 501);
} else {
    require_once '../../core/shared/helpers/api_helper.php';
    sendErrorResponse('Endpoint not found', 404);
}
?>