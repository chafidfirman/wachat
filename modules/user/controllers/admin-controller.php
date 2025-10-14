<?php
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../models/admin.php';
require_once __DIR__ . '/../models/logactivity.php';
require_once __DIR__ . '/../../../modules/product/models/product.php';
require_once __DIR__ . '/../../../modules/category/models/category.php';

class AdminController {
    private $adminModel;
    private $logActivityModel;
    private $productModel;
    private $categoryModel;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->adminModel = new Admin($pdo);
        $this->logActivityModel = new LogActivity($pdo);
        $this->productModel = new Product($pdo);
        $this->categoryModel = new Category($pdo);
    }
    
    // Display admin login page
    public function login() {
        try {
            // Check if already logged in
            if (isset($_SESSION['admin_id']) || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true)) {
                redirect('admin/dashboard');
            }
            
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $error = 'Please fill in all fields';
                } else {
                    $admin = $this->adminModel->authenticate($username, $password);
                    if ($admin) {
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_name'] = $admin['name'];
                        $_SESSION['admin_logged_in'] = true;
                        // Log successful login
                        $this->logActivityModel->logActivity($admin['id'], 'login', 'Admin logged in successfully');
                        redirect('admin/dashboard');
                    } else {
                        // Log failed login attempt
                        $this->logActivityModel->logActivity(null, 'login_failed', 'Failed login attempt for username: ' . $username);
                        $error = 'Invalid username or password';
                    }
                }
            }
            
            // Include the view
            $loginViewPath = __DIR__ . '/../views/admin/login.php';
            if (file_exists($loginViewPath)) {
                include $loginViewPath;
            } else {
                // Fallback to a simple login form if the view doesn't exist
                echo '<h1>Admin Login</h1>';
                if (isset($error) && !empty($error)) echo '<p style="color:red">' . $error . '</p>';
                echo '<form method="post">';
                echo '<div><label>Username: <input type="text" name="username" required></label></div>';
                echo '<div><label>Password: <input type="password" name="password" required></label></div>';
                echo '<div><button type="submit">Login</button></div>';
                echo '</form>';
            }
        } catch (Exception $e) {
            logError("Error in admin login: " . $e->getMessage());
            $error = "Login system error. Please try again later.";
            include __DIR__ . '/../views/admin/login.php';
        }
    }
    
    // Display admin dashboard
    public function dashboard() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                redirect('admin/login');
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                redirect('admin/login');
            }
            
            $products = $this->productModel->getAll();
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/dashboard.php';
        } catch (Exception $e) {
            logError("Error loading admin dashboard: " . $e->getMessage());
            $error = "Failed to load dashboard. Please try again later.";
            // Redirect to login on error
            redirect('admin/login');
        }
    }
    
    // Logout admin
    public function logout() {
        try {
            // Log logout activity if admin is logged in
            if (isset($_SESSION['admin_id'])) {
                $this->logActivityModel->logActivity($_SESSION['admin_id'], 'logout', 'Admin logged out');
            }
            session_destroy();
            redirect('admin/login');
        } catch (Exception $e) {
            logError("Error during admin logout: " . $e->getMessage());
            // Still redirect to login even if there's an error
            redirect('admin/login');
        }
    }
    
    // Manage products
    public function products() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                redirect('admin/login');
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                redirect('admin/login');
            }
            
            $products = $this->productModel->getAll();
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/products.php';
        } catch (Exception $e) {
            logError("Error loading admin products: " . $e->getMessage());
            $error = "Failed to load products. Please try again later.";
            include __DIR__ . '/../views/admin/products.php';
        }
    }
    
    // Manage categories
    public function categories() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                redirect('admin/login');
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                redirect('admin/login');
            }
            
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/categories.php';
        } catch (Exception $e) {
            logError("Error loading admin categories: " . $e->getMessage());
            $error = "Failed to load categories. Please try again later.";
            include __DIR__ . '/../views/admin/categories.php';
        }
    }
    // View activity logs
    public function logs() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                redirect('admin/login');
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                redirect('admin/login');
            }
            
            // Get activity logs
            $logs = $this->logActivityModel->getAllLogs(100);
            
            // Include the view
            include __DIR__ . '/../views/admin/logs.php';
        } catch (Exception $e) {
            logError("Error loading activity logs: " . $e->getMessage());
            $error = "Failed to load activity logs. Please try again later.";
            include __DIR__ . '/../views/admin/logs.php';
        }
    }
}
?>