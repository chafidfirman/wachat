<?php
/**
 * Error Controller
 * Handles error pages and user-friendly error messages
 */

require_once __DIR__ . '/../../../core/shared/helpers/debug_helper.php';

class ErrorController {
    
    /**
     * Display a generic error page
     * @param string $message User-friendly error message
     * @param array $debugInfo Debug information (only shown in debug mode)
     */
    public function showError($message = '', $debugInfo = []) {
        // Log the error
        if (!empty($message)) {
            logError($message);
        }
        
        // Set error message
        $error = !empty($message) ? $message : "An unexpected error occurred. Please try again later.";
        
        // Include the error view
        include __DIR__ . '/../views/error.php';
    }
    
    /**
     * Display a 404 error page
     * @param string $message User-friendly 404 message
     * @param array $debugInfo Debug information (only shown in debug mode)
     */
    public function show404($message = '', $debugInfo = []) {
        // Log the 404 error
        $requestedUri = $_SERVER['REQUEST_URI'] ?? 'Unknown';
        logNavigationError('Page Not Found', $requestedUri);
        
        // Set error message
        $error = !empty($message) ? $message : "The page you're looking for doesn't exist.";
        
        // Set HTTP status code
        http_response_code(404);
        
        // Include the 404 view
        include __DIR__ . '/../views/404.php';
    }
    
    /**
     * Display a database error page
     * @param string $context Context where the error occurred
     */
    public function showDatabaseError($context = '') {
        // Log the database error
        $message = "Database error occurred in {$context}";
        logError($message);
        
        // Set error message
        $error = "We're experiencing technical difficulties with our database. Please try again later.";
        
        // Include the error view
        include __DIR__ . '/../views/error.php';
    }
    
    /**
     * Display a validation error page
     * @param array $errors Validation errors
     */
    public function showValidationErrors($errors = []) {
        // Log the validation errors
        logError("Validation errors: " . json_encode($errors));
        
        // Set error message
        $error = "There were validation errors with your submission. Please check your input and try again.";
        
        // Include the error view
        include __DIR__ . '/../views/error.php';
    }
}
?>