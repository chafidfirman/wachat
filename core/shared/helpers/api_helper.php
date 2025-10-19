<?php
/**
 * API Helper Functions
 * Provides standardized response formats for API endpoints
 */

if (!function_exists('getallheaders')) {
    /**
     * Get all HTTP headers
     * Polyfill for environments where getallheaders() is not available
     * @return array
     */
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * Send a standardized JSON success response
 * @param mixed $data The data to return
 * @param string $message Optional message
 * @param int $code HTTP status code (default: 200)
 */
function sendSuccessResponse($data = null, $message = '', $code = 200) {
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code($code);
    
    $response = [
        'success' => true,
        'timestamp' => date('c'), // ISO 8601 format
        'data' => $data
    ];
    
    if (!empty($message)) {
        $response['message'] = $message;
    }
    
    if ($code !== 200) {
        $response['code'] = $code;
    }
    
    // Ensure proper JSON encoding
    $json = json_encode($response, JSON_PRETTY_PRINT);
    if ($json === false) {
        // Fallback if JSON encoding fails
        error_log("JSON encoding failed in sendSuccessResponse: " . json_last_error_msg());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Internal server error',
            'timestamp' => date('c')
        ]);
    } else {
        echo $json;
    }
    
    exit;
}

/**
 * Send a standardized JSON error response
 * @param string $message Error message
 * @param int $code HTTP status code (default: 400)
 * @param mixed $data Optional additional data
 * @param string $context Optional context information
 */
function sendErrorResponse($message, $code = 400, $data = null, $context = '') {
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code($code);
    
    $response = [
        'success' => false,
        'timestamp' => date('c'), // ISO 8601 format,
        'error' => [
            'code' => $code,
            'message' => $message
        ]
    ];
    
    // Add context if provided
    if (!empty($context)) {
        $response['error']['context'] = $context;
    }
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    // Ensure proper JSON encoding
    $json = json_encode($response, JSON_PRETTY_PRINT);
    if ($json === false) {
        // Fallback if JSON encoding fails
        error_log("JSON encoding failed in sendErrorResponse: " . json_last_error_msg());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Internal server error',
            'timestamp' => date('c')
        ]);
    } else {
        echo $json;
    }
    
    // Log the error
    error_log("API Error [{$code}]: {$message}" . (!empty($context) ? " (Context: {$context})" : ""));
    
    exit;
}

/**
 * Send a standardized JSON validation error response
 * @param array $errors Array of validation errors
 * @param string $message Optional message
 */
function sendValidationErrorResponse($errors, $message = 'Validation failed') {
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code(422); // Unprocessable Entity
    
    $response = [
        'success' => false,
        'timestamp' => date('c'), // ISO 8601 format
        'error' => [
            'code' => 422,
            'message' => $message,
            'validation_errors' => $errors
        ]
    ];
    
    // Ensure proper JSON encoding
    $json = json_encode($response, JSON_PRETTY_PRINT);
    if ($json === false) {
        // Fallback if JSON encoding fails
        error_log("JSON encoding failed in sendValidationErrorResponse: " . json_last_error_msg());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Internal server error',
            'timestamp' => date('c')
        ]);
    } else {
        echo $json;
    }
    
    // Log the validation errors
    error_log("Validation Error: " . json_encode($errors));
    
    exit;
}

/**
 * Send a standardized JSON pagination response
 * @param array $data The data to return
 * @param int $total Total number of items
 * @param int $page Current page
 * @param int $limit Items per page
 * @param string $message Optional message
 */
function sendPaginatedResponse($data, $total, $page, $limit, $message = '') {
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    $totalPages = ceil($total / $limit);
    
    $response = [
        'success' => true,
        'timestamp' => date('c'), // ISO 8601 format
        'data' => $data,
        'pagination' => [
            'current_page' => (int)$page,
            'per_page' => (int)$limit,
            'total_items' => (int)$total,
            'total_pages' => (int)$totalPages,
            'has_previous' => $page > 1,
            'has_next' => $page < $totalPages
        ]
    ];
    
    if (!empty($message)) {
        $response['message'] = $message;
    }
    
    // Ensure proper JSON encoding
    $json = json_encode($response, JSON_PRETTY_PRINT);
    if ($json === false) {
        // Fallback if JSON encoding fails
        error_log("JSON encoding failed in sendPaginatedResponse: " . json_last_error_msg());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Internal server error',
            'timestamp' => date('c')
        ]);
    } else {
        http_response_code(200);
        echo $json;
    }
    
    exit;
}

/**
 * Parse JSON input from request body
 * @return array|null Parsed JSON data or null if invalid
 */
function parseJsonInput() {
    $input = file_get_contents('php://input');
    
    if (empty($input)) {
        return null;
    }
    
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON parsing error: " . json_last_error_msg());
        return null;
    }
    
    return $data;
}

/**
 * Get request method in a case-insensitive way
 * @return string Uppercase request method
 */
function getRequestMethod() {
    return strtoupper($_SERVER['REQUEST_METHOD']);
}

/**
 * Check if request method is allowed
 * @param array $allowedMethods Array of allowed HTTP methods
 * @return bool True if method is allowed, false otherwise
 */
function isRequestMethodAllowed($allowedMethods) {
    $method = getRequestMethod();
    return in_array($method, $allowedMethods);
}

/**
 * Send method not allowed response
 * @param array $allowedMethods Array of allowed HTTP methods
 */
function sendMethodNotAllowedResponse($allowedMethods = ['GET']) {
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    header('Allow: ' . implode(', ', $allowedMethods));
    sendErrorResponse('Method not allowed. Allowed methods: ' . implode(', ', $allowedMethods), 405);
}

/**
 * Sanitize output data to prevent XSS
 * @param mixed $data Data to sanitize
 * @return mixed Sanitized data
 */
function sanitizeOutput($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitizeOutput($value);
        }
    } elseif (is_string($data)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    } elseif (is_numeric($data)) {
        // Ensure numeric values are properly typed
        if (is_float($data) || is_double($data)) {
            $data = (float)$data;
        } else {
            $data = (int)$data;
        }
    }
    
    return $data;
}
?>