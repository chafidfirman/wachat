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
$router->get('admin/dashboard', 'user', 'AdminController', 'dashboard');
$router->get('admin/products', 'user', 'AdminController', 'products');
$router->get('admin/categories', 'user', 'AdminController', 'categories');
$router->get('admin/logs', 'user', 'AdminController', 'logs');
$router->get('admin/logout', 'user', 'AdminController', 'logout');

// Get the requested path
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
if (!$router->route($method, $path, $pdo)) {
    // 404 page
    include __DIR__ . '/../modules/product/views/404.php';
}
?>