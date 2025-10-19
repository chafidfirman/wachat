<?php
/**
 * Database class
 * Handles database connections and operations
 */

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $pdo;
    private $error;
    
    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        );
        
        // Create PDO instance
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->error = null;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database connection failed: " . $this->error);
            // Log to specific database log file
            $logFile = __DIR__ . '/../../logs/database.log';
            file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Connection Error: " . $this->error . PHP_EOL, FILE_APPEND | LOCK_EX);
            
            // Set PDO to null to allow fallback mechanisms
            $this->pdo = null;
        }
    }
    
    /**
     * Get PDO connection
     * @return PDO|null
     */
    public function getConnection() {
        return $this->pdo;
    }
    
    /**
     * Check if database connection is available
     * @return bool
     */
    public function isConnected() {
        return $this->pdo !== null;
    }
    
    /**
     * Get connection error message
     * @return string|null
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * Reconnect to the database
     * @return bool True if reconnection successful
     */
    public function reconnect() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        );
        
        // Create PDO instance
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->error = null;
            return true;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database reconnection failed: " . $this->error);
            // Log to specific database log file
            $logFile = __DIR__ . '/../../logs/database.log';
            file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Reconnection Error: " . $this->error . PHP_EOL, FILE_APPEND | LOCK_EX);
            
            // Set PDO to null to indicate failed reconnection
            $this->pdo = null;
            return false;
        }
    }
    
    /**
     * Display a user-friendly error message when database connection fails
     * @param string $context Context where the error occurred
     * @return string User-friendly error message
     */
    public function getErrorMessage($context = '') {
        if ($this->error) {
            $message = "We're experiencing technical difficulties with our database at the moment. ";
            $message .= "Please try again later. ";
            $message .= "If the problem persists, please contact our support team.\n\n";
            $message .= "Error details: " . $this->error;
            
            // Log the error with context
            error_log("Database error in {$context}: " . $this->error);
            
            return $message;
        }
        return '';
    }
    
    /**
     * Check connection and attempt to reconnect if needed
     * @return bool True if connection is available or successfully reconnected
     */
    public function ensureConnection() {
        // If we're already connected, return true
        if ($this->isConnected()) {
            return true;
        }
        
        // Try to reconnect
        return $this->reconnect();
    }
}
?>