<?php
// Simple router to mimic .htaccess behavior for built-in PHP server

// Get the requested path
$uri = $_SERVER['REQUEST_URI'];

// Remove query string if present
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Remove leading slash
$uri = ltrim($uri, '/');

// Handle admin routes by redirecting to public router
if (strpos($uri, 'admin/') === 0) {
    // Redirect to public router with path parameter
    $path = $uri;
    include __DIR__ . '/public/index.php';
    exit;
}

// Handle product routes
if (preg_match('/^product\/(\d+)$/', $uri, $matches)) {
    $_GET['path'] = $uri;
    include __DIR__ . '/public/index.php';
    exit;
}

// Handle other specific routes
if (in_array($uri, ['search', 'whatsapp'])) {
    $_GET['path'] = $uri;
    include __DIR__ . '/public/index.php';
    exit;
}

// For all other requests, let the built-in server handle static files
// If no static file is found, let the public router handle it
if (file_exists(__DIR__ . '/' . $uri) && is_file(__DIR__ . '/' . $uri)) {
    return false; // Let PHP server serve the static file
} else {
    // No static file found, route through public router
    $_GET['path'] = $uri;
    include __DIR__ . '/public/index.php';
    exit;
}
?>