<?php
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../../../modules/user/models/admin.php';
require_once __DIR__ . '/../../../modules/product/models/product.php';
require_once __DIR__ . '/../../../modules/category/models/category.php';
require_once __DIR__ . '/../../../modules/user/models/logactivity.php';
require_once __DIR__ . '/../../../core/shared/helpers/view_helpers.php';
require_once __DIR__ . '/../../../core/shared/helpers/debug_helper.php';

require_once __DIR__ . '/../../../core/shared/exceptions/ChatCartException.php';
require_once __DIR__ . '/../../../modules/error/controllers/ErrorController.php';

class AdminController {
    private $adminModel;
    private $productModel;
    private $categoryModel;
    private $logActivityModel;
    private $errorController;
    
    public function __construct($pdo) {
        $this->adminModel = new Admin($pdo);
        $this->productModel = new Product($pdo);
        $this->categoryModel = new Category($pdo);
        $this->logActivityModel = new LogActivity($pdo);
        $this->errorController = new ErrorController();
    }
    
    // Display admin login page
    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_id'])) {
            header('Location: admin');
            exit;
        }
        
        $error = '';
        
        // Handle login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = isset($_POST['username']) ? trim($_POST['username']) : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                
                // Validate input
                if (empty($username) || empty($password)) {
                    $error = "Please enter both username and password.";
                } else {
                    // Check credentials using authenticate method
                    $admin = $this->adminModel->authenticate($username, $password);
                    
                    if ($admin) {
                        // Login successful
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        
                        // Log the activity
                        $this->logActivityModel->logActivity($admin['id'], 'login', 'Admin logged in');
                        
                        // Redirect to dashboard
                        header('Location: admin');
                        exit;
                    } else {
                        $error = "Invalid username or password.";
                        logError("Failed admin login attempt for username: " . $username);
                    }
                }
                
                // If it's an AJAX request, return JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
            } catch (ChatCartException $e) {
                logError("ChatCart Error in admin login: " . $e->getMessage());
                $error = $e->getUserMessage();
                
                // If it's an AJAX request, return JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
            } catch (Exception $e) {
                logError("Error in admin login: " . $e->getMessage());
                $error = "Login system error. Please try again later.";
                
                // If it's an AJAX request, return JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $error]);
                    return;
                }
            }
        }
        
        include __DIR__ . '/../views/admin/login.php';
    }
    
    // Display admin dashboard
    public function dashboard() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            $products = $this->productModel->getAll();
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/dashboard.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading admin dashboard: " . $e->getMessage());
            $error = $e->getUserMessage();
            // Redirect to login on error
            header('Location: admin/login');
        } catch (Exception $e) {
            logError("Error loading admin dashboard: " . $e->getMessage());
            $error = "Failed to load dashboard. Please try again later.";
            // Redirect to login on error
            header('Location: admin/login');
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
            header('Location: admin/login');
        } catch (ChatCartException $e) {
            logError("ChatCart Error during admin logout: " . $e->getMessage());
            // Still redirect to login even if there's an error
            header('Location: admin/login');
        } catch (Exception $e) {
            logError("Error during admin logout: " . $e->getMessage());
            // Still redirect to login even if there's an error
            header('Location: admin/login');
        }
    }
    
    // Manage products
    public function products() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            $products = $this->productModel->getAll();
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/products.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading admin products: " . $e->getMessage());
            $error = $e->getUserMessage();
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
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/admin/categories.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading admin categories: " . $e->getMessage());
            $error = $e->getUserMessage();
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
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Get activity logs
            $logs = $this->logActivityModel->getAllLogs(100);
            
            // Include the view
            include __DIR__ . '/../views/admin/logs.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading activity logs: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/logs.php';
        } catch (Exception $e) {
            logError("Error loading activity logs: " . $e->getMessage());
            $error = "Failed to load activity logs. Please try again later.";
            include __DIR__ . '/../views/admin/logs.php';
        }
    }
    
    // View orders
    public function orders() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/orders.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading orders: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/orders.php';
        } catch (Exception $e) {
            logError("Error loading orders: " . $e->getMessage());
            $error = "Failed to load orders. Please try again later.";
            include __DIR__ . '/../views/admin/orders.php';
        }
    }
    
    // Manage users
    public function users() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/users.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading users: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/users.php';
        } catch (Exception $e) {
            logError("Error loading users: " . $e->getMessage());
            $error = "Failed to load users. Please try again later.";
            include __DIR__ . '/../views/admin/users.php';
        }
    }
    
    // Manage settings
    public function settings() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/settings.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading settings: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/settings.php';
        } catch (Exception $e) {
            logError("Error loading settings: " . $e->getMessage());
            $error = "Failed to load settings. Please try again later.";
            include __DIR__ . '/../views/admin/settings.php';
        }
    }
    
    // View reports
    public function reports() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/reports.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading reports: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/reports.php';
        } catch (Exception $e) {
            logError("Error loading reports: " . $e->getMessage());
            $error = "Failed to load reports. Please try again later.";
            include __DIR__ . '/../views/admin/reports.php';
        }
    }
    
    // View analytics
    public function analytics() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: admin/login');
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: admin/login');
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/analytics.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading analytics: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/analytics.php';
        } catch (Exception $e) {
            logError("Error loading analytics: " . $e->getMessage());
            $error = "Failed to load analytics. Please try again later.";
            include __DIR__ . '/../views/admin/analytics.php';
        }
    }
    
    // Error monitoring dashboard
    public function errorMonitoring() {
        try {
            // Check if logged in
            if (!isset($_SESSION['admin_id'])) {
                header('Location: ' . site_url('admin/login'));
                exit;
            }
            
            $admin = $this->adminModel->getById($_SESSION['admin_id']);
            if (!$admin) {
                // Admin not found, destroy session and redirect to login
                session_destroy();
                header('Location: ' . site_url('admin/login'));
                exit;
            }
            
            // Include the view
            include __DIR__ . '/../views/admin/error_monitoring.php';
        } catch (ChatCartException $e) {
            logError("ChatCart Error loading error monitoring: " . $e->getMessage());
            $error = $e->getUserMessage();
            include __DIR__ . '/../views/admin/error_monitoring.php';
        } catch (Exception $e) {
            logError("Error loading error monitoring: " . $e->getMessage());
            $error = "Failed to load error monitoring. Please try again later.";
            include __DIR__ . '/../views/admin/error_monitoring.php';
        }
    }
    

}
?>