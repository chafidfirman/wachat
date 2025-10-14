<?php
/**
 * Main API Entry Point
 * Routes requests to appropriate API versions
 */

// Get the requested version
$version = isset($_GET['version']) ? $_GET['version'] : 'v1';

// Route the request based on version
if ($version === 'v1') {
    require_once 'v1/index.php';
} else {
    require_once '../core/shared/helpers/api_helper.php';
    sendErrorResponse("API version '{$version}' not supported", 400);
}
?>