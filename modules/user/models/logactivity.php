<?php
class LogActivity {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Log an admin activity
     * 
     * @param int $adminId Admin ID
     * @param string $action Action performed
     * @param string $description Detailed description
     * @return bool Success status
     */
    public function logActivity($adminId, $action, $description = '') {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO log_activity (admin_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
            $ipAddress = $this->getIpAddress();
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            
            return $stmt->execute([$adminId, $action, $description, $ipAddress, $userAgent]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all activity logs
     * 
     * @param int $limit Number of records to return
     * @return array Activity logs
     */
    public function getAllLogs($limit = 50) {
        try {
            $stmt = $this->pdo->prepare("SELECT la.*, a.username, a.name as admin_name FROM log_activity la LEFT JOIN admins a ON la.admin_id = a.id ORDER BY la.created_at DESC LIMIT ?");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to fetch activity logs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get logs for a specific admin
     * 
     * @param int $adminId Admin ID
     * @param int $limit Number of records to return
     * @return array Activity logs
     */
    public function getLogsByAdmin($adminId, $limit = 50) {
        try {
            $stmt = $this->pdo->prepare("SELECT la.*, a.username, a.name as admin_name FROM log_activity la LEFT JOIN admins a ON la.admin_id = a.id WHERE la.admin_id = ? ORDER BY la.created_at DESC LIMIT ?");
            $stmt->execute([$adminId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to fetch admin activity logs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get logs by action type
     * 
     * @param string $action Action type
     * @param int $limit Number of records to return
     * @return array Activity logs
     */
    public function getLogsByAction($action, $limit = 50) {
        try {
            $stmt = $this->pdo->prepare("SELECT la.*, a.username, a.name as admin_name FROM log_activity la LEFT JOIN admins a ON la.admin_id = a.id WHERE la.action = ? ORDER BY la.created_at DESC LIMIT ?");
            $stmt->execute([$action, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to fetch activity logs by action: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get client IP address
     * 
     * @return string IP address
     */
    private function getIpAddress() {
        // Handle CLI environment
        if (!isset($_SERVER)) {
            return 'cli';
        }
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
        return $ip;
    }
}
?>