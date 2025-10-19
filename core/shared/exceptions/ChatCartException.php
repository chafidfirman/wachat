<?php
/**
 * ChatCart Exception
 * Centralized exception handling for the ChatCart Web application
 */

require_once __DIR__ . '/../helpers/debug_helper.php';

class ChatCartException extends Exception {
    private $context;
    private $severity;
    private $additionalData;
    
    // Severity levels
    const SEVERITY_DEBUG = 'DEBUG';
    const SEVERITY_INFO = 'INFO';
    const SEVERITY_WARNING = 'WARNING';
    const SEVERITY_ERROR = 'ERROR';
    const SEVERITY_CRITICAL = 'CRITICAL';
    
    public function __construct($message, $code = 0, $context = '', $severity = self::SEVERITY_ERROR, $additionalData = [], Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        
        $this->context = $context;
        $this->severity = $severity;
        $this->additionalData = $additionalData;
        
        // Log the exception
        $this->logException();
    }
    
    /**
     * Log the exception with context and severity
     */
    private function logException() {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$this->severity}] [{$this->context}] {$this->getMessage()} | Additional Data: " . json_encode($this->additionalData);
        
        // Log to appropriate file based on severity
        switch ($this->severity) {
            case self::SEVERITY_CRITICAL:
            case self::SEVERITY_ERROR:
                logError($logMessage);
                break;
            case self::SEVERITY_WARNING:
                $this->logToFile('warning.log', $logMessage);
                break;
            case self::SEVERITY_INFO:
                $this->logToFile('info.log', $logMessage);
                break;
            case self::SEVERITY_DEBUG:
                $this->logToFile('debug.log', $logMessage);
                break;
            default:
                logError($logMessage);
        }
        
        // For critical errors, also log to error log
        if ($this->severity === self::SEVERITY_CRITICAL) {
            logError($logMessage);
        }
    }
    
    /**
     * Log to a specific file
     * @param string $filename
     * @param string $message
     */
    private function logToFile($filename, $message) {
        $logDir = __DIR__ . '/../../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logDir . '/' . $filename, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get context
     * @return string
     */
    public function getContext() {
        return $this->context;
    }
    
    /**
     * Get severity
     * @return string
     */
    public function getSeverity() {
        return $this->severity;
    }
    
    /**
     * Get additional data
     * @return array
     */
    public function getAdditionalData() {
        return $this->additionalData;
    }
    
    /**
     * Get formatted error message for user display
     * @return string
     */
    public function getUserMessage() {
        // Don't expose sensitive information to users
        switch ($this->severity) {
            case self::SEVERITY_CRITICAL:
                return "We're experiencing technical difficulties. Please try again later.";
            case self::SEVERITY_ERROR:
                return "An error occurred while processing your request. Please try again.";
            case self::SEVERITY_WARNING:
                return "There was a minor issue with your request. Please try again.";
            default:
                return "An unexpected error occurred. Please try again.";
        }
    }
    
    /**
     * Get detailed error information for debugging
     * @return array
     */
    public function getDebugInfo() {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'context' => $this->context,
            'severity' => $this->severity,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'additional_data' => $this->additionalData,
            'trace' => $this->getTraceAsString()
        ];
    }
}

/**
 * Database Exception
 */
class DatabaseException extends ChatCartException {
    public function __construct($message, $context = 'Database', $additionalData = [], Exception $previous = null) {
        parent::__construct($message, 500, $context, ChatCartException::SEVERITY_ERROR, $additionalData, $previous);
    }
    
    public function getUserMessage() {
        return "We're experiencing technical difficulties with our database. Please try again later.";
    }
}

/**
 * Validation Exception
 */
class ValidationException extends ChatCartException {
    private $validationErrors;
    
    public function __construct($message, $validationErrors = [], $context = 'Validation', Exception $previous = null) {
        $this->validationErrors = $validationErrors;
        parent::__construct($message, 400, $context, ChatCartException::SEVERITY_WARNING, $validationErrors, $previous);
    }
    
    public function getValidationErrors() {
        return $this->validationErrors;
    }
    
    public function getUserMessage() {
        return "There were validation errors with your submission. Please check your input and try again.";
    }
}

/**
 * Authentication Exception
 */
class AuthenticationException extends ChatCartException {
    public function __construct($message, $context = 'Authentication', Exception $previous = null) {
        parent::__construct($message, 401, $context, ChatCartException::SEVERITY_WARNING, [], $previous);
    }
    
    public function getUserMessage() {
        return "Authentication failed. Please check your credentials and try again.";
    }
}

/**
 * Authorization Exception
 */
class AuthorizationException extends ChatCartException {
    public function __construct($message, $context = 'Authorization', Exception $previous = null) {
        parent::__construct($message, 403, $context, ChatCartException::SEVERITY_WARNING, [], $previous);
    }
    
    public function getUserMessage() {
        return "You don't have permission to perform this action.";
    }
}

/**
 * NotFoundException
 */
class NotFoundException extends ChatCartException {
    public function __construct($message, $context = 'NotFound', Exception $previous = null) {
        parent::__construct($message, 404, $context, ChatCartException::SEVERITY_INFO, [], $previous);
    }
    
    public function getUserMessage() {
        return "The requested resource was not found.";
    }
}
?>