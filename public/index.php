<?php
session_start();

// Include the database configuration
require_once __DIR__ . '/../core/shared/config/database.php';

// Include the router
require_once __DIR__ . '/../core/shared/Router.php';

// Create router instance
$router = new Router();

// Define routes
$router->get('', 'product', 'MainController', 'index');
$router->get('home', 'product', 'MainController', 'index');
$router->get('index.php', 'product', 'MainController', 'index');
$router->get('product/{id}', 'product', 'MainController', 'product');
$router->get('search', 'product', 'MainController', 'search');
$router->get('whatsapp/{id}', 'product', 'MainController', 'whatsapp');
$router->get('admin/login', 'user', 'AdminController', 'login');
$router->post('admin/login', 'user', 'AdminController', 'login'); // Added POST route for login
$router->get('admin/dashboard', 'user', 'AdminController', 'dashboard');
$router->get('admin/products', 'user', 'AdminController', 'products');
$router->get('admin/categories', 'user', 'AdminController', 'categories');
$router->get('admin/orders', 'user', 'AdminController', 'orders');
$router->get('admin/users', 'user', 'AdminController', 'users');
$router->get('admin/settings', 'user', 'AdminController', 'settings');
$router->get('admin/reports', 'user', 'AdminController', 'reports');
$router->get('admin/analytics', 'user', 'AdminController', 'analytics');
$router->get('admin/logs', 'user', 'AdminController', 'logs');
$router->get('admin/logout', 'user', 'AdminController', 'logout');

// Set fallback route
$router->setFallback('product', 'MainController', 'index');

// Get the requested path
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
if (!$router->route($method, $path, $pdo)) {
    // 404 page
    http_response_code(404);
    include __DIR__ . '/../modules/product/views/404.php';
}
?>