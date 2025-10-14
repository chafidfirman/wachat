<?php
/**
 * Environment Configuration
 * Handles environment-specific settings
 */

class Environment {
    private static $environment;
    
    /**
     * Get the current environment
     * @return string Environment name
     */
    public static function getEnvironment() {
        if (self::$environment === null) {
            self::$environment = defined('ENVIRONMENT') ? ENVIRONMENT : 'development';
        }
        
        return self::$environment;
    }
    
    /**
     * Check if we're in development environment
     * @return bool True if in development
     */
    public static function isDevelopment() {
        return self::getEnvironment() === 'development';
    }
    
    /**
     * Check if we're in staging environment
     * @return bool True if in staging
     */
    public static function isStaging() {
        return self::getEnvironment() === 'staging';
    }
    
    /**
     * Check if we're in production environment
     * @return bool True if in production
     */
    public static function isProduction() {
        return self::getEnvironment() === 'production';
    }
    
    /**
     * Get debug mode setting based on environment
     * @return bool True if debug mode is enabled
     */
    public static function isDebugMode() {
        return self::isDevelopment();
    }
    
    /**
     * Get error display setting based on environment
     * @return bool True if errors should be displayed
     */
    public static function shouldDisplayErrors() {
        return self::isDevelopment();
    }
    
    /**
     * Get error logging setting based on environment
     * @return bool True if errors should be logged
     */
    public static function shouldLogErrors() {
        return true; // Always log errors
    }
}
?>