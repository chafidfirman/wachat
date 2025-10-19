<?php
/**
 * Debug Helper Functions
 * Provides comprehensive debugging and error monitoring capabilities
 */

// Ensure logs directory exists
$logDir = __DIR__ . '/../../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

/**
 * Enable full error reporting for development
 */
function enableDebugMode() {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Set custom error handler
    set_error_handler('customErrorHandler');
    set_exception_handler('customExceptionHandler');
    
    // Register shutdown function to catch fatal errors
    register_shutdown_function('shutdownHandler');
}

/**
 * Custom error handler
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorType = [
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated'
    ];
    
    $type = isset($errorType[$errno]) ? $errorType[$errno] : 'Unknown Error';
    $message = "[$type] $errstr in $errfile on line $errline";
    
    // Log to file
    logError($message);
    
    // Display error if debug mode is enabled
    if (defined('DEBUG_MODE') && DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true') {
        echo "<div style='background-color: #ffecec; border: 1px solid #ff0000; padding: 10px; margin: 10px;'>";
        echo "<strong>DEBUG ERROR:</strong> $message<br>";
        echo "</div>";
    }
    
    return true;
}

/**
 * Custom exception handler
 */
function customExceptionHandler($exception) {
    $message = "[EXCEPTION] " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    
    // Log to file
    logError($message);
    
    // Display exception if debug mode is enabled
    if (defined('DEBUG_MODE') && DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true') {
        echo "<div style='background-color: #ffecec; border: 1px solid #ff0000; padding: 10px; margin: 10px;'>";
        echo "<strong>DEBUG EXCEPTION:</strong> $message<br>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        echo "</div>";
    }
}

/**
 * Shutdown handler to catch fatal errors
 */
function shutdownHandler() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        $message = "[FATAL ERROR] " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
        logError($message);
    }
}

/**
 * Log error to file with enhanced formatting
 */
function logError($message, $context = 'General') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [ERROR] [$context] $message\n";
    file_put_contents(__DIR__ . '/../../logs/error.log', $logMessage, FILE_APPEND | LOCK_EX);
}

/**
 * Log warning to file
 */
function logWarning($message, $context = 'General') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [WARNING] [$context] $message\n";
    file_put_contents(__DIR__ . '/../../logs/warning.log', $logMessage, FILE_APPEND | LOCK_EX);
}

/**
 * Log info to file
 */
function logInfo($message, $context = 'General') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [INFO] [$context] $message\n";
    file_put_contents(__DIR__ . '/../../logs/info.log', $logMessage, FILE_APPEND | LOCK_EX);
}

/**
 * Log debug information to file
 */
function logDebug($message, $context = 'General') {
    // Only log debug messages in development environment
    if (!defined('ENVIRONMENT') || ENVIRONMENT !== 'development') {
        return;
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [DEBUG] [$context] $message\n";
    file_put_contents(__DIR__ . '/../../logs/debug.log', $logMessage, FILE_APPEND | LOCK_EX);
}

/**
 * Log navigation errors
 */
function logNavigationError($menu, $destination) {
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] NAVIGATION ERROR: Menu '$menu' -> Destination '$destination' (404 Not Found)\n";
    file_put_contents(__DIR__ . '/../../logs/navigation.log', $message, FILE_APPEND | LOCK_EX);
}

/**
 * Log database queries
 */
function logQuery($query, $params = []) {
    $timestamp = date('Y-m-d H:i:s');
    $paramStr = !empty($params) ? json_encode($params) : 'No parameters';
    $message = "[$timestamp] QUERY EXECUTED: $query | Params: $paramStr\n";
    file_put_contents(__DIR__ . '/../../logs/query.log', $message, FILE_APPEND | LOCK_EX);
    
    // Display debug info if debug mode is enabled
    if (defined('DEBUG_MODE') && DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true') {
        echo "<div style='background-color: #e8f4ff; border: 1px solid #007bff; padding: 10px; margin: 10px;'>";
        echo "<strong>DEBUG QUERY:</strong> $query<br>";
        if (!empty($params)) {
            echo "<strong>Params:</strong> " . json_encode($params) . "<br>";
        }
        echo "</div>";
    }
}

/**
 * Log form submissions
 */
function logFormSubmission($formName, $data = []) {
    $timestamp = date('Y-m-d H:i:s');
    $dataStr = !empty($data) ? json_encode($data) : 'No data';
    $message = "[$timestamp] FORM SUBMITTED: $formName | Data: $dataStr\n";
    file_put_contents(__DIR__ . '/../../logs/form.log', $message, FILE_APPEND | LOCK_EX);
}

/**
 * Log delete operations
 */
function logDeleteOperation($operation, $id) {
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] DELETE OPERATION: $operation | ID: $id\n";
    file_put_contents(__DIR__ . '/../../logs/delete.log', $message, FILE_APPEND | LOCK_EX);
}

/**
 * Log admin access
 */
function logAdminAccess($page, $user = 'unknown') {
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] ADMIN ACCESS: Page '$page' | User: $user\n";
    file_put_contents(__DIR__ . '/../../logs/admin.log', $message, FILE_APPEND | LOCK_EX);
}

/**
 * Display debug overlay for developers
 */
function displayDebugOverlay() {
    if (!defined('DEBUG_MODE') || !DEBUG_MODE || !isset($_GET['debug']) || $_GET['debug'] !== 'true') {
        return;
    }
    
    // Get log file contents
    $errorLog = file_exists(__DIR__ . '/../../logs/error.log') ? file_get_contents(__DIR__ . '/../../logs/error.log') : 'No errors logged.';
    $navLog = file_exists(__DIR__ . '/../../logs/navigation.log') ? file_get_contents(__DIR__ . '/../../logs/navigation.log') : 'No navigation errors logged.';
    $queryLog = file_exists(__DIR__ . '/../../logs/query.log') ? file_get_contents(__DIR__ . '/../../logs/query.log') : 'No queries logged.';
    
    echo "
    <div id='debug-overlay' style='position: fixed; bottom: 0; right: 0; width: 400px; max-height: 300px; background: rgba(0,0,0,0.9); color: white; padding: 10px; font-size: 12px; z-index: 9999; overflow-y: auto; border: 2px solid #00ff00;'>
        <div style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;'>
            <h3 style='margin: 0; color: #00ff00;'>DEBUG OVERLAY</h3>
            <button onclick='document.getElementById(\"debug-overlay\").style.display=\"none\"' style='background: red; color: white; border: none; padding: 5px;'>X</button>
        </div>
        <div>
            <strong>Error Log:</strong><br>
            <pre style='background: #333; padding: 5px; max-height: 100px; overflow-y: auto;'>$errorLog</pre>
        </div>
        <div>
            <strong>Navigation Log:</strong><br>
            <pre style='background: #333; padding: 5px; max-height: 100px; overflow-y: auto;'>$navLog</pre>
        </div>
        <div>
            <strong>Query Log:</strong><br>
            <pre style='background: #333; padding: 5px; max-height: 100px; overflow-y: auto;'>$queryLog</pre>
        </div>
    </div>";
}

/**
 * Generate bug checklist in JSON format
 */
function generateBugChecklist() {
    $checklist = [
        'timestamp' => date('Y-m-d H:i:s'),
        'bugs' => [
            [
                'id' => 1,
                'category' => 'UI Responsiveness',
                'description' => 'Check if all UI elements respond to user interactions',
                'status' => 'Open',
                'priority' => 'High'
            ],
            [
                'id' => 2,
                'category' => 'Button Functionality',
                'description' => 'Verify all buttons perform their intended actions',
                'status' => 'Open',
                'priority' => 'High'
            ],
            [
                'id' => 3,
                'category' => 'Input Validation',
                'description' => 'Test all form inputs for proper validation',
                'status' => 'Open',
                'priority' => 'Medium'
            ],
            [
                'id' => 4,
                'category' => 'Page Availability',
                'description' => 'Check for broken links or 404 errors',
                'status' => 'Open',
                'priority' => 'High'
            ],
            [
                'id' => 5,
                'category' => 'Database Operations',
                'description' => 'Verify all database queries execute correctly',
                'status' => 'Open',
                'priority' => 'High'
            ],
            [
                'id' => 6,
                'category' => 'API Responses',
                'description' => 'Ensure all API endpoints return proper responses',
                'status' => 'Open',
                'priority' => 'Medium'
            ]
        ]
    ];
    
    return json_encode($checklist, JSON_PRETTY_PRINT);
}

/**
 * Save bug checklist to file
 */
function saveBugChecklist() {
    $checklist = generateBugChecklist();
    file_put_contents(__DIR__ . '/../../logs/bug_checklist.json', $checklist);
}

// Initialize debug mode if enabled
if (defined('DEBUG_MODE') && DEBUG_MODE && isset($_GET['debug']) && $_GET['debug'] === 'true') {
    enableDebugMode();
}
?>