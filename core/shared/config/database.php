<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'chatcart');

// Initialize PDO connection
$pdo = null;
$dbError = null;

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    // Store error message for debugging
    $dbError = $e->getMessage();
    
    // Log error instead of dying to allow fallback to JSON data
    error_log("Database connection failed: " . $dbError);
    
    // Log to specific database log file
    $logFile = __DIR__ . '/../../../logs/database.log';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Connection Error: " . $dbError . PHP_EOL, FILE_APPEND | LOCK_EX);
    
    // Create empty PDO object for fallback
    $pdo = null;
}

/**
 * Display a user-friendly database connection error message
 * @param string $context Context where the error occurred
 * @return string User-friendly error message
 */
function getDatabaseErrorMessage($context = '') {
    global $dbError;
    
    if ($dbError) {
        $message = "We're experiencing technical difficulties with our database at the moment. ";
        $message .= "Please try again later. ";
        $message .= "If the problem persists, please contact our support team.\n\n";
        $message .= "Error details: " . $dbError;
        
        // Log the error with context
        error_log("Database error in {$context}: " . $dbError);
        
        return $message;
    }
    return '';
}

/**
 * Check if database connection is available
 * @return bool True if database connection is available
 */
function isDatabaseConnected() {
    global $pdo;
    return $pdo !== null;
}
?>