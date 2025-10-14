<?php
/**
 * Base API Controller
 * Provides common functionality for all API controllers
 */

// Set default headers for all API responses
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-Token');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/helpers/api_helper.php';
require_once __DIR__ . '/helpers/csrf_helper.php';

abstract class ApiController {
    protected $method;
    protected $requestBody;
    
    public function __construct() {
        $this->method = getRequestMethod();
        $this->requestBody = parseJsonInput();
    }
    
    /**
     * Process the API request
     */
    abstract public function processRequest();
    
    /**
     * Handle GET requests
     */
    protected function handleGet() {
        sendMethodNotAllowedResponse(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
    /**
     * Handle POST requests
     */
    protected function handlePost() {
        // Validate CSRF token for POST requests
        $this->validateCsrfToken();
        sendMethodNotAllowedResponse(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
    /**
     * Handle PUT requests
     */
    protected function handlePut() {
        // Validate CSRF token for PUT requests
        $this->validateCsrfToken();
        sendMethodNotAllowedResponse(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
    /**
     * Handle DELETE requests
     */
    protected function handleDelete() {
        // Validate CSRF token for DELETE requests
        $this->validateCsrfToken();
        sendMethodNotAllowedResponse(['GET', 'POST', 'PUT', 'DELETE']);
    }
    
    /**
     * Validate CSRF token for write operations
     */
    protected function validateCsrfToken() {
        // Skip CSRF validation for API requests that use proper authentication
        // In a production environment, you would implement proper API authentication
        // For now, we'll check for a CSRF token in the header or request body
        
        // Check for CSRF token in headers
        $csrfToken = null;
        $headers = getallheaders();
        if (isset($headers['X-CSRF-Token'])) {
            $csrfToken = $headers['X-CSRF-Token'];
        }
        
        // If not in headers, check in request body
        if (!$csrfToken && $this->requestBody && isset($this->requestBody['csrf_token'])) {
            $csrfToken = $this->requestBody['csrf_token'];
        }
        
        // If not in request body, check in POST data
        if (!$csrfToken && isset($_POST['csrf_token'])) {
            $csrfToken = $_POST['csrf_token'];
        }
        
        // If we have a token, validate it
        if ($csrfToken) {
            if (!validateCsrfToken($csrfToken)) {
                sendErrorResponse('Invalid CSRF token', 403);
            }
        }
        // Note: In a real production environment, you would require a CSRF token for all write operations
        // For this educational example, we're allowing requests without tokens but validating them when present
    }
    
    /**
     * Validate required fields in request body
     * @param array $requiredFields List of required fields
     * @return array|null Array of missing fields or null if all present
     */
    protected function validateRequiredFields($requiredFields) {
        $missingFields = [];
        
        if (!$this->requestBody) {
            return ['request_body' => 'Request body is missing or invalid'];
        }
        
        foreach ($requiredFields as $field) {
            if (!isset($this->requestBody[$field]) || empty($this->requestBody[$field])) {
                $missingFields[$field] = "Field '{$field}' is required";
            }
        }
        
        return empty($missingFields) ? null : $missingFields;
    }
    
    /**
     * Get a parameter from GET or POST data
     * @param string $key Parameter key
     * @param mixed $default Default value if not found
     * @return mixed Parameter value
     */
    protected function getParam($key, $default = null) {
        // Check GET parameters first
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        
        // Check POST parameters
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        
        // Check request body for JSON requests
        if ($this->requestBody && isset($this->requestBody[$key])) {
            return $this->requestBody[$key];
        }
        
        return $default;
    }
    
    /**
     * Sanitize input data
     * @param mixed $data Data to sanitize
     * @return mixed Sanitized data
     */
    protected function sanitizeInput($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeInput($value);
            }
        } elseif (is_string($data)) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            // Additional sanitization for security
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } elseif (is_numeric($data)) {
            // Ensure numeric values are properly typed
            if (is_float($data) || is_double($data)) {
                $data = filter_var($data, FILTER_VALIDATE_FLOAT);
            } else {
                $data = filter_var($data, FILTER_VALIDATE_INT);
            }
        }
        
        return $data;
    }
}
?>