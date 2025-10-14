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
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database connection failed: " . $this->error);
            // Log to specific database log file
            $logFile = __DIR__ . '/../../logs/database.log';
            file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Connection Error: " . $this->error . PHP_EOL, FILE_APPEND);
            
            // In development mode, we can show the error
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo "Database connection failed: " . $this->error;
            }
        }
    }
    
    /**
     * Get PDO connection
     * @return PDO|null
     */
    public function getConnection() {
        return $this->pdo;
    }
}
?>