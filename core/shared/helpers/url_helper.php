<?php
/**
 * URL Helper Functions
 * Provides enhanced URL generation and handling capabilities
 */

/**
 * Enhanced base URL generation with better error handling
 * 
 * @param string $path Optional path to append to base URL
 * @param bool $forceHttps Force HTTPS protocol (default: false)
 * @return string Full base URL with optional path
 */
function base_url($path = '', $forceHttps = false) {
    static $baseUrl = null;
    
    // If we haven't determined the base URL yet, do it now
    if ($baseUrl === null) {
        if (defined('BASE_URL') && BASE_URL !== '') {
            $baseUrl = BASE_URL;
        } else {
            // Auto-detect base URL
            $protocol = 'http';
            
            // Check if HTTPS is forced or detected
            if ($forceHttps || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')) {
                $protocol = 'https';
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                // Handle load balancer scenarios
                $protocol = 'https';
            }
            
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            
            // Handle proxy scenarios
            if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
                $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
            }
            
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = rtrim(dirname($scriptName), '/');
            
            // Special handling for public directory
            if (strpos($basePath, '/public') !== false) {
                $basePath = substr($basePath, 0, strpos($basePath, '/public'));
            }
            
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
 * Enhanced site URL generation with better routing support
 * 
 * @param string $path Optional path for routing
 * @param array $params Optional query parameters
 * @return string Full site URL with routing
 */
function site_url($path = '', $params = []) {
    $siteUrl = rtrim(base_url(), '/') . '/public/index.php';
    
    if (!empty($path)) {
        // Ensure path doesn't start with a slash
        $path = ltrim($path, '/');
        $siteUrl .= '?path=' . urlencode($path);
    }
    
    // Add query parameters if provided
    if (!empty($params) && is_array($params)) {
        $separator = empty($path) ? '?' : '&';
        $queryString = http_build_query($params);
        $siteUrl .= $separator . $queryString;
    }
    
    return $siteUrl;
}

/**
 * Enhanced redirect function with better error handling
 * 
 * @param string $path Path to redirect to
 * @param int $statusCode HTTP status code (default: 302)
 * @param array $params Optional query parameters
 */
function redirect($path = '', $statusCode = 302, $params = []) {
    // Validate status code
    if (!in_array($statusCode, [301, 302, 303, 307, 308])) {
        $statusCode = 302; // Default to temporary redirect
    }
    
    $url = site_url($path, $params);
    
    // Ensure we don't have any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code($statusCode);
    header('Location: ' . $url);
    exit;
}

/**
 * Generate URL for current page with optional parameters
 * 
 * @param array $params Parameters to add/modify
 * @param array $removeParams Parameters to remove
 * @return string Current page URL with modified parameters
 */
function current_url($params = [], $removeParams = []) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Parse current URL
    $urlParts = parse_url($protocol . '://' . $host . $uri);
    
    // Parse query string
    $queryParams = [];
    if (isset($urlParts['query'])) {
        parse_str($urlParts['query'], $queryParams);
    }
    
    // Remove specified parameters
    foreach ($removeParams as $param) {
        unset($queryParams[$param]);
    }
    
    // Add/modify parameters
    foreach ($params as $key => $value) {
        $queryParams[$key] = $value;
    }
    
    // Rebuild query string
    $queryString = '';
    if (!empty($queryParams)) {
        $queryString = '?' . http_build_query($queryParams);
    }
    
    // Build full URL
    $path = $urlParts['path'] ?? '/';
    return $protocol . '://' . $host . $path . $queryString;
}

/**
 * Check if current URL matches a pattern
 * 
 * @param string $pattern URL pattern to match
 * @return bool True if current URL matches pattern
 */
function is_current_url($pattern) {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Remove query string
    if (strpos($currentPath, '?') !== false) {
        $currentPath = substr($currentPath, 0, strpos($currentPath, '?'));
    }
    
    // Normalize paths
    $currentPath = trim($currentPath, '/');
    $pattern = trim($pattern, '/');
    
    // Handle special cases
    if (empty($pattern) && (empty($currentPath) || $currentPath === 'public/index.php')) {
        return true;
    }
    
    return $currentPath === $pattern;
}

/**
 * Generate canonical URL for SEO purposes
 * 
 * @param string $path Optional path
 * @return string Canonical URL
 */
function canonical_url($path = '') {
    $protocol = 'https'; // Always use HTTPS for canonical URLs
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    if (!empty($path)) {
        $path = '/' . ltrim($path, '/');
    } else {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        // Remove query string for canonical URL
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }
    }
    
    return $protocol . '://' . $host . $path;
}

/**
 * Validate URL format
 * 
 * @param string $url URL to validate
 * @return bool True if URL is valid
 */
function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Make URL absolute if it's relative
 * 
 * @param string $url URL to make absolute
 * @return string Absolute URL
 */
function make_absolute_url($url) {
    if (empty($url)) {
        return base_url();
    }
    
    // If already absolute, return as is
    if (strpos($url, '://') !== false) {
        return $url;
    }
    
    // If it's a protocol-relative URL
    if (strpos($url, '//') === 0) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https:' : 'http:';
        return $protocol . $url;
    }
    
    // If it's a root-relative URL
    if (strpos($url, '/') === 0) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host . $url;
    }
    
    // If it's a relative URL, make it relative to current path
    $currentPath = dirname($_SERVER['REQUEST_URI'] ?? '/');
    return base_url(rtrim($currentPath, '/') . '/' . $url);
}
?>