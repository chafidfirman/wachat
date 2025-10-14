<?php
// Configuration file for ChatCart Web

// Include environment configuration
require_once __DIR__ . '/core/shared/config/environment.php';

// Environment configuration
define('ENVIRONMENT', 'development'); // 'development', 'staging', or 'production'

// Base URL configuration
// For Windows XAMPP setup, use 'http://localhost/wachat/'
// In production, this should be your domain (e.g., 'https://yourdomain.com/')
define('BASE_URL', 'http://localhost/wachat/');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'chatcart');

// WhatsApp configuration
define('DEFAULT_WHATSAPP_NUMBER', '6281234567890');

// Application settings
define('APP_NAME', 'ChatCart Web');
define('APP_VERSION', '2.0.0');

// Paths
define('DATA_PATH', __DIR__ . '/data');
define('IMAGE_PATH', __DIR__ . '/images');

// Debug settings
define('DEBUG_MODE', Environment::isDebugMode());
define('DISPLAY_ERRORS', Environment::shouldDisplayErrors());
define('LOG_ERRORS', Environment::shouldLogErrors());
define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');

// Error reporting
if (DISPLAY_ERRORS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL); // Still log errors in production
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ERROR_LOG_FILE);
}

// Include debug helper
require_once __DIR__ . '/core/shared/helpers/debug_helper.php';

// Initialize debug mode if enabled
if (DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true') {
    enableDebugMode();
}

/**
 * Helper function to generate base URL
 * Automatically detects the base URL if not explicitly set
 */
function base_url($path = '') {
    static $baseUrl = null;
    
    // If we haven't determined the base URL yet, do it now
    if ($baseUrl === null) {
        if (defined('BASE_URL') && BASE_URL !== '') {
            $baseUrl = BASE_URL;
        } else {
            // Auto-detect base URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = rtrim(dirname($scriptName), '/');
            $baseUrl = $protocol . '://' . $host . $basePath . '/';
        }
    }
    
    // Remove trailing slash from base URL if path is provided
    if (!empty($path)) {
        // Ensure path doesn't start with a slash
        $path = ltrim($path, '/');
        return rtrim($baseUrl, '/') . '/' . $path;
    }
    return $baseUrl;
}

/**
 * Helper function to generate site URL (includes index.php)
 */
function site_url($path = '') {
    $siteUrl = rtrim(base_url(), '/') . '/public/index.php';
    if (!empty($path)) {
        // Ensure path doesn't start with a slash
        $path = ltrim($path, '/');
        return $siteUrl . '?path=' . $path;
    }
    return $siteUrl;
}

/**
 * Helper function to redirect to a specific path
 */
function redirect($path = '') {
    header('Location: ' . site_url($path));
    exit;
}
?>