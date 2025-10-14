<?php
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../../../modules/category/models/category.php';
require_once __DIR__ . '/../../../core/shared/helpers/view_helpers.php';
require_once __DIR__ . '/../../../core/shared/helpers/debug_helper.php';

class MainController {
    private $productModel;
    private $categoryModel;
    
    public function __construct($pdo) {
        $this->productModel = new Product($pdo);
        $this->categoryModel = new Category($pdo);
    }
    
    // Display homepage
    public function index() {
        try {
            logQuery("Loading homepage products");
            $products = $this->productModel->getAll();
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/index.php';
        } catch (Exception $e) {
            logError("Error loading homepage: " . $e->getMessage());
            // Display error page
            $error = "Failed to load products. Please try again later.";
            include __DIR__ . '/../views/404.php';
        }
    }
    
    // Display product detail
    public function product($id) {
        try {
            // Validate ID parameter
            if (empty($id) || !is_numeric($id) || $id <= 0) {
                logNavigationError("Product Detail", "Invalid Product ID: " . $id);
                include __DIR__ . '/../views/404.php';
                return;
            }
            
            logQuery("Loading product detail", ['id' => $id]);
            $product = $this->productModel->getById($id);
            
            // Debug information
            logQuery("Product lookup result", [
                'requested_id' => $id,
                'requested_id_type' => gettype($id),
                'product_found' => $product ? 'yes' : 'no'
            ]);
            
            if (!$product) {
                // Try to get product from JSON if database failed
                $product = $this->productModel->getByIdFromJson($id);
                
                if (!$product) {
                    // Log navigation error
                    logNavigationError("Product Detail", "Product ID: " . $id . " (Not Found)");
                    
                    // Product not found, redirect to 404
                    include __DIR__ . '/../views/404.php';
                    return;
                }
            }
            
            // For JSON-based implementation, we use the category name directly
            $relatedProducts = $this->productModel->getRelated($id, $product['category']);
            
            // Include the view
            include __DIR__ . '/../views/product.php';
        } catch (Exception $e) {
            logError("Error loading product detail: " . $e->getMessage());
            // Display error page
            $error = "Failed to load product details. Please try again later.";
            include __DIR__ . '/../views/404.php';
        }
    }
    
    // Search products
    public function search() {
        try {
            $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
            $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
            
            logFormSubmission("Search", ['keyword' => $keyword, 'category_id' => $categoryId]);
            
            if ($categoryId > 0) {
                $products = $this->productModel->getByCategory($categoryId);
            } else if (!empty($keyword)) {
                $products = $this->productModel->search($keyword);
            } else {
                $products = $this->productModel->getAll();
            }
            
            $categories = $this->categoryModel->getAll();
            
            // Include the view
            include __DIR__ . '/../views/search.php';
        } catch (Exception $e) {
            logError("Error during search: " . $e->getMessage());
            // Display error page
            $error = "Search failed. Please try again later.";
            $products = [];
            $categories = [];
            include __DIR__ . '/../views/search.php';
        }
    }
    
    // Handle WhatsApp click
    public function whatsapp($id) {
        try {
            // Validate ID parameter
            if (empty($id) || !is_numeric($id) || $id <= 0) {
                logNavigationError("WhatsApp Click", "Invalid Product ID: " . $id);
                header('Location: ' . site_url());
                exit;
            }
            
            logQuery("WhatsApp click", ['product_id' => $id]);
            $product = $this->productModel->getById($id);
            if ($product) {
                // Log the click
                $this->productModel->logWhatsAppClick($id);
                
                // Generate WhatsApp message
                $message = "Halo, saya ingin pesan " . $product['name'];
                if (isset($_GET['variation']) && !empty($_GET['variation'])) {
                    $message .= " varian " . $_GET['variation'];
                }
                if (isset($_GET['quantity']) && (int)$_GET['quantity'] > 1) {
                    $message .= " sebanyak " . (int)$_GET['quantity'];
                }
                $message .= " dengan harga " . formatPrice($product['price']);
                
                // Redirect to WhatsApp
                $whatsappUrl = "https://wa.me/" . $product['whatsapp_number'] . "?text=" . urlencode($message);
                header("Location: $whatsappUrl");
                exit;
            } else {
                // Log navigation error
                logNavigationError("WhatsApp Click", "Product ID: " . $id . " (Not Found)");
                
                // Product not found, redirect to homepage
                header('Location: ' . site_url());
                exit;
            }
        } catch (Exception $e) {
            logError("Error during WhatsApp redirect: " . $e->getMessage());
            // Redirect to homepage on error
            header('Location: ' . site_url());
            exit;
        }
    }
}
?>