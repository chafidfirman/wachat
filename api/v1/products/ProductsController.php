<?php
/**
 * Products API Controller
 * Handles all product-related API endpoints
 */

require_once __DIR__ . '/../../../core/shared/ApiController.php';
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../../../core/shared/Database.php';
require_once __DIR__ . '/../../../modules/product/models/Product.php';
require_once __DIR__ . '/../../../modules/user/models/LogActivity.php';

class ProductsController extends ApiController {
    private $productModel;
    private $logActivityModel;
    
    public function __construct() {
        parent::__construct();
        
        // Initialize database connection
        $database = new Database();
        $pdo = $database->getConnection();
        
        // Initialize product model
        $this->productModel = new Product($pdo);
        // Initialize log activity model
        $this->logActivityModel = new LogActivity($pdo);
    }
    
    /**
     * Process the API request
     */
    public function processRequest() {
        switch ($this->method) {
            case 'GET':
                return $this->handleGet();
            case 'POST':
                return $this->handlePost();
            case 'PUT':
                return $this->handlePut();
            case 'DELETE':
                return $this->handleDelete();
            default:
                sendMethodNotAllowedResponse(['GET', 'POST', 'PUT', 'DELETE']);
        }
    }
    
    /**
     * Handle GET requests
     */
    protected function handleGet() {
        $id = $this->getParam('id');
        
        if ($id) {
            // Validate ID parameter
            if (!is_numeric($id) || $id <= 0) {
                sendErrorResponse('Invalid product ID', 400);
            }
            
            // Get specific product
            $product = $this->productModel->getById($id);
            if ($product) {
                sendSuccessResponse($product, 'Product retrieved successfully');
            } else {
                sendErrorResponse('Product not found', 404);
            }
        } else {
            // Get all products with optional pagination
            $page = (int)$this->getParam('page', 1);
            $limit = (int)$this->getParam('limit', 10);
            $search = $this->sanitizeInput($this->getParam('search'));
            $category = $this->sanitizeInput($this->getParam('category'));
            
            // Ensure page and limit are valid
            $page = max(1, $page);
            $limit = max(1, min(100, $limit)); // Limit between 1 and 100
            
            // Get products with filters
            if ($search) {
                // Further sanitize search term
                $search = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $search);
                $products = $this->productModel->search($search);
                sendSuccessResponse($products, 'Products retrieved successfully');
            } elseif ($category) {
                // Validate category parameter
                if (!is_numeric($category) && !is_string($category)) {
                    sendErrorResponse('Invalid category parameter', 400);
                }
                $products = $this->productModel->getByCategory($category);
                sendSuccessResponse($products, 'Products retrieved successfully');
            } else {
                $products = $this->productModel->getAll();
                sendSuccessResponse($products, 'Products retrieved successfully');
            }
        }
    }
    
    /**
     * Handle POST requests (create product)
     */
    protected function handlePost() {
        // In a real implementation, you would authenticate the user first
        
        // Validate required fields
        $requiredFields = ['name', 'price', 'description'];
        $missingFields = $this->validateRequiredFields($requiredFields);
        
        if ($missingFields) {
            sendValidationErrorResponse($missingFields);
        }
        
        // Sanitize input data with enhanced security
        $name = $this->sanitizeProductName($this->getParam('name'));
        $price = $this->sanitizePrice($this->getParam('price'));
        $description = $this->sanitizeDescription($this->getParam('description'));
        $categoryId = $this->sanitizeCategoryId($this->getParam('category_id', 0));
        $inStock = (bool)$this->getParam('in_stock', true);
        $stockQuantity = $this->getParam('stock_quantity') !== null ? $this->sanitizeStockQuantity($this->getParam('stock_quantity')) : null;
        $image = $this->sanitizeImageUrl($this->getParam('image'));
        $whatsappNumber = $this->sanitizeWhatsappNumber($this->getParam('whatsapp_number', DEFAULT_WHATSAPP_NUMBER));
        
        // Validate price is positive
        if ($price <= 0) {
            sendValidationErrorResponse(['price' => 'Price must be greater than zero']);
        }
        
        // Validate stock quantity if provided
        if ($stockQuantity !== null && $stockQuantity < 0) {
            sendValidationErrorResponse(['stock_quantity' => 'Stock quantity cannot be negative']);
        }
        
        // Create product data array
        $productData = [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'category_id' => $categoryId,
            'in_stock' => $inStock,
            'stock_quantity' => $stockQuantity,
            'image' => $image,
            'whatsapp_number' => $whatsappNumber
        ];
        
        // Try to create the product
        try {
            $result = $this->productModel->create($productData);
            if ($result) {
                // Log the activity if admin is logged in
                if (isset($_SESSION['admin_id'])) {
                    $this->logActivityModel->logActivity(
                        $_SESSION['admin_id'], 
                        'product_create', 
                        'Created product: ' . $name . ' (ID: ' . $result['id'] . ')'
                    );
                }
                sendSuccessResponse($result, 'Product created successfully', 201);
            } else {
                sendErrorResponse('Failed to create product', 500);
            }
        } catch (Exception $e) {
            sendErrorResponse('Failed to create product: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Handle PUT requests (update product)
     */
    protected function handlePut() {
        // In a real implementation, you would authenticate the user first
        
        $id = $this->getParam('id');
        if (!$id) {
            sendErrorResponse('Product ID is required for update', 400);
        }
        
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            sendErrorResponse('Invalid product ID', 400);
        }
        
        // Check if product exists
        $existingProduct = $this->productModel->getById($id);
        if (!$existingProduct) {
            sendErrorResponse('Product not found', 404);
        }
        
        // Validate required fields
        $requiredFields = ['name', 'price', 'description'];
        $missingFields = $this->validateRequiredFields($requiredFields);
        
        if ($missingFields) {
            sendValidationErrorResponse($missingFields);
        }
        
        // Sanitize input data with enhanced security
        $name = $this->sanitizeProductName($this->getParam('name'));
        $price = $this->sanitizePrice($this->getParam('price'));
        $description = $this->sanitizeDescription($this->getParam('description'));
        $categoryId = $this->sanitizeCategoryId($this->getParam('category_id', $existingProduct['category_id']));
        $inStock = $this->getParam('in_stock') !== null ? (bool)$this->getParam('in_stock') : $existingProduct['in_stock'];
        $stockQuantity = $this->getParam('stock_quantity') !== null ? $this->sanitizeStockQuantity($this->getParam('stock_quantity')) : $existingProduct['stock_quantity'];
        $image = $this->sanitizeImageUrl($this->getParam('image', $existingProduct['image']));
        $whatsappNumber = $this->sanitizeWhatsappNumber($this->getParam('whatsapp_number', $existingProduct['whatsapp_number']));
        
        // Validate price is positive
        if ($price <= 0) {
            sendValidationErrorResponse(['price' => 'Price must be greater than zero']);
        }
        
        // Validate stock quantity if provided
        if ($stockQuantity !== null && $stockQuantity < 0) {
            sendValidationErrorResponse(['stock_quantity' => 'Stock quantity cannot be negative']);
        }
        
        // Create product data array
        $productData = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'category_id' => $categoryId,
            'in_stock' => $inStock,
            'stock_quantity' => $stockQuantity,
            'image' => $image,
            'whatsapp_number' => $whatsappNumber
        ];
        
        // Try to update the product
        try {
            $result = $this->productModel->update($productData);
            if ($result) {
                // Log the activity if admin is logged in
                if (isset($_SESSION['admin_id'])) {
                    $this->logActivityModel->logActivity(
                        $_SESSION['admin_id'], 
                        'product_update', 
                        'Updated product: ' . $name . ' (ID: ' . $id . ')'
                    );
                }
                sendSuccessResponse($result, 'Product updated successfully');
            } else {
                sendErrorResponse('Failed to update product', 500);
            }
        } catch (Exception $e) {
            sendErrorResponse('Failed to update product: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Handle DELETE requests (delete product)
     */
    protected function handleDelete() {
        // In a real implementation, you would authenticate the user first
        
        $id = $this->getParam('id');
        if (!$id) {
            sendErrorResponse('Product ID is required for deletion', 400);
        }
        
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            sendErrorResponse('Invalid product ID', 400);
        }
        
        // Check if product exists
        $existingProduct = $this->productModel->getById($id);
        if (!$existingProduct) {
            sendErrorResponse('Product not found', 404);
        }
        
        // Try to delete the product
        try {
            $result = $this->productModel->delete($id);
            if ($result) {
                // Log the activity if admin is logged in
                if (isset($_SESSION['admin_id'])) {
                    $this->logActivityModel->logActivity(
                        $_SESSION['admin_id'], 
                        'product_delete', 
                        'Deleted product ID: ' . $id
                    );
                }
                sendSuccessResponse(null, 'Product deleted successfully');
            } else {
                sendErrorResponse('Failed to delete product', 500);
            }
        } catch (Exception $e) {
            sendErrorResponse('Failed to delete product: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Sanitize product name
     * @param string $name Product name
     * @return string Sanitized product name
     */
    private function sanitizeProductName($name) {
        $name = $this->sanitizeInput($name);
        // Limit product name length
        $name = substr($name, 0, 255);
        return $name;
    }
    
    /**
     * Sanitize price
     * @param mixed $price Price value
     * @return float Sanitized price
     */
    private function sanitizePrice($price) {
        $price = filter_var($price, FILTER_VALIDATE_FLOAT);
        if ($price === false) {
            sendValidationErrorResponse(['price' => 'Invalid price format']);
        }
        return round($price, 2); // Round to 2 decimal places
    }
    
    /**
     * Sanitize description
     * @param string $description Product description
     * @return string Sanitized description
     */
    private function sanitizeDescription($description) {
        $description = $this->sanitizeInput($description);
        // Limit description length
        $description = substr($description, 0, 1000);
        return $description;
    }
    
    /**
     * Sanitize category ID
     * @param mixed $categoryId Category ID
     * @return int Sanitized category ID
     */
    private function sanitizeCategoryId($categoryId) {
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
        if ($categoryId === false) {
            return 0; // Default to uncategorized
        }
        return max(0, $categoryId); // Ensure non-negative
    }
    
    /**
     * Sanitize stock quantity
     * @param mixed $stockQuantity Stock quantity
     * @return int Sanitized stock quantity
     */
    private function sanitizeStockQuantity($stockQuantity) {
        $stockQuantity = filter_var($stockQuantity, FILTER_VALIDATE_INT);
        if ($stockQuantity === false) {
            sendValidationErrorResponse(['stock_quantity' => 'Invalid stock quantity format']);
        }
        return max(0, $stockQuantity); // Ensure non-negative
    }
    
    /**
     * Sanitize image URL
     * @param string $imageUrl Image URL
     * @return string Sanitized image URL
     */
    private function sanitizeImageUrl($imageUrl) {
        if (empty($imageUrl)) {
            return '';
        }
        
        $imageUrl = $this->sanitizeInput($imageUrl);
        
        // Validate URL format
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            // If not a valid URL, treat as relative path
            // Remove any potentially harmful characters
            $imageUrl = preg_replace('/[^a-zA-Z0-9\/._\-]/', '', $imageUrl);
        }
        
        // Limit URL length
        $imageUrl = substr($imageUrl, 0, 500);
        return $imageUrl;
    }
    
    /**
     * Sanitize WhatsApp number
     * @param string $whatsappNumber WhatsApp number
     * @return string Sanitized WhatsApp number
     */
    private function sanitizeWhatsappNumber($whatsappNumber) {
        if (empty($whatsappNumber)) {
            return '';
        }
        
        $whatsappNumber = $this->sanitizeInput($whatsappNumber);
        
        // Remove any non-digit characters except +
        $whatsappNumber = preg_replace('/[^\d+]/', '', $whatsappNumber);
        
        // Limit length
        $whatsappNumber = substr($whatsappNumber, 0, 20);
        return $whatsappNumber;
    }
}

// Process the request
$controller = new ProductsController();
$controller->processRequest();
?>