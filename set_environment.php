<?php
/**
 * Environment Switcher Script
 * Allows easy switching between development, staging, and production environments
 */

// Check if environment is provided
if (!isset($argv[1])) {
    echo "Usage: php set_environment.php [development|staging|production]\n";
    echo "Current environment: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'not set') . "\n";
    exit(1);
}

$environment = $argv[1];

// Validate environment
if (!in_array($environment, ['development', 'staging', 'production'])) {
    echo "Error: Invalid environment. Use 'development', 'staging', or 'production'\n";
    exit(1);
}

// Read the current config file
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    echo "Error: config.php not found\n";
    exit(1);
}

$configContent = file_get_contents($configFile);

// Replace the environment setting
$pattern = "/define\('ENVIRONMENT', '.*?'\);/";
$replacement = "define('ENVIRONMENT', '{$environment}');";
$newConfigContent = preg_replace($pattern, $replacement, $configContent);

// Write the updated config file
if (file_put_contents($configFile, $newConfigContent) !== false) {
    echo "Environment successfully set to: {$environment}\n";
    
    // Show the current debug mode status
    require_once $configFile;
    echo "Debug mode: " . (DEBUG_MODE ? 'ENABLED' : 'DISABLED') . "\n";
    echo "Display errors: " . (DISPLAY_ERRORS ? 'ENABLED' : 'DISABLED') . "\n";
} else {
    echo "Error: Failed to update config.php\n";
    exit(1);
}
?>