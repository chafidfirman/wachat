<?php
/**
 * Admin User Management Script
 * This script provides functions to create and manage admin users with proper password hashing
 */

require_once __DIR__ . '/core/shared/config/database.php';

class AdminUserManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create a new admin user with proper password hashing
     * 
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $phone
     * @return bool
     */
    public function createAdmin($username, $password, $name = '', $phone = '') {
        try {
            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the new admin user
            $stmt = $this->pdo->prepare("INSERT INTO admins (username, password_hash, name, phone) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$username, $passwordHash, $name, $phone]);
            
            if ($result) {
                echo "Admin user '$username' created successfully!\n";
                return true;
            } else {
                echo "Failed to create admin user '$username'.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error creating admin user: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Update an admin user's password with proper hashing
     * 
     * @param int $adminId
     * @param string $newPassword
     * @return bool
     */
    public function updateAdminPassword($adminId, $newPassword) {
        try {
            // Hash the new password
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the admin user's password
            $stmt = $this->pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
            $result = $stmt->execute([$passwordHash, $adminId]);
            
            if ($result) {
                echo "Admin user password updated successfully!\n";
                return true;
            } else {
                echo "Failed to update admin user password.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error updating admin user password: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Update an admin user's information
     * 
     * @param int $adminId
     * @param string $username
     * @param string $name
     * @param string $phone
     * @return bool
     */
    public function updateAdminInfo($adminId, $username, $name = '', $phone = '') {
        try {
            // Update the admin user's information
            $stmt = $this->pdo->prepare("UPDATE admins SET username = ?, name = ?, phone = ? WHERE id = ?");
            $result = $stmt->execute([$username, $name, $phone, $adminId]);
            
            if ($result) {
                echo "Admin user information updated successfully!\n";
                return true;
            } else {
                echo "Failed to update admin user information.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error updating admin user information: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * List all admin users
     * 
     * @return array
     */
    public function listAdmins() {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, name, phone, created_at FROM admins ORDER BY id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error listing admin users: " . $e->getMessage() . "\n";
            return [];
        }
    }
    
    /**
     * Delete an admin user
     * 
     * @param int $adminId
     * @return bool
     */
    public function deleteAdmin($adminId) {
        try {
            // Prevent deletion of the last admin user
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM admins");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($count <= 1) {
                echo "Cannot delete the last admin user.\n";
                return false;
            }
            
            // Delete the admin user
            $stmt = $this->pdo->prepare("DELETE FROM admins WHERE id = ?");
            $result = $stmt->execute([$adminId]);
            
            if ($result) {
                echo "Admin user deleted successfully!\n";
                return true;
            } else {
                echo "Failed to delete admin user.\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error deleting admin user: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    if (!$pdo) {
        die("Database connection failed. Please check your database configuration.\n");
    }
    
    $adminManager = new AdminUserManager($pdo);
    
    // Simple CLI interface
    if ($argc < 2) {
        echo "Usage: php admin_user_management.php [command] [options]\n";
        echo "Commands:\n";
        echo "  create [username] [password] [name] [phone] - Create a new admin user\n";
        echo "  list - List all admin users\n";
        echo "  help - Show this help message\n";
        exit(1);
    }
    
    $command = $argv[1];
    
    switch ($command) {
        case 'create':
            if ($argc < 4) {
                echo "Usage: php admin_user_management.php create [username] [password] [name] [phone]\n";
                exit(1);
            }
            $username = $argv[2];
            $password = $argv[3];
            $name = isset($argv[4]) ? $argv[4] : '';
            $phone = isset($argv[5]) ? $argv[5] : '';
            $adminManager->createAdmin($username, $password, $name, $phone);
            break;
            
        case 'list':
            $admins = $adminManager->listAdmins();
            if (empty($admins)) {
                echo "No admin users found.\n";
            } else {
                echo "Admin Users:\n";
                echo str_pad("ID", 5) . str_pad("Username", 20) . str_pad("Name", 25) . str_pad("Phone", 15) . "Created At\n";
                echo str_repeat("-", 80) . "\n";
                foreach ($admins as $admin) {
                    echo str_pad($admin['id'], 5) . 
                         str_pad($admin['username'], 20) . 
                         str_pad($admin['name'], 25) . 
                         str_pad($admin['phone'], 15) . 
                         $admin['created_at'] . "\n";
                }
            }
            break;
            
        case 'help':
        default:
            echo "Usage: php admin_user_management.php [command] [options]\n";
            echo "Commands:\n";
            echo "  create [username] [password] [name] [phone] - Create a new admin user\n";
            echo "  list - List all admin users\n";
            echo "  help - Show this help message\n";
            break;
    }
}
?>