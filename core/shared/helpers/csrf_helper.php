<?php
/**
 * CSRF Protection Helper
 * Provides functions for generating and validating CSRF tokens
 */

/**
 * Generate a CSRF token
 * @return string CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token
 * @param string $token Token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Require a valid CSRF token or die
 * @param string $token Token to validate
 */
function requireValidCsrfToken($token) {
    if (!validateCsrfToken($token)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => [
                'code' => 403,
                'message' => 'Invalid or missing CSRF token'
            ]
        ]);
        exit;
    }
}

/**
 * Generate a hidden input field with CSRF token
 * @return string HTML input field
 */
function csrfField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}
?>