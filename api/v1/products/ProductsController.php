<?php
/**
 * Products API Controller
 * Handles all product-related API endpoints
 */

require_once __DIR__ . '/../../../core/shared/ApiController.php';
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../../../core/shared/Database.php';
require_once __DIR__ . '/../../../modules/product/models/product.php';
require_once __DIR__ . '/../../../modules/user/models/LogActivity.php';
require_once __DIR__ . '/../../../core/shared/exceptions/ChatCartException.php';

// Define default values
if (!defined('DEFAULT_WHATSAPP_NUMBER')) {
    define('DEFAULT_WHATSAPP_NUMBER', '+6281234567890');
}

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
        try {
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
        } catch (ChatCartException $e) {
            error_log("ChatCart API Error: " . $e->getMessage());
            sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
        } catch (Exception $e) {
            error_log("API Error in ProductsController: " . $e->getMessage());
            sendErrorResponse('Internal server error occurred', 500);
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
            // Get all products with optional pagination and filters
            $page = (int)$this->getParam('page', 1);
            $limit = (int)$this->getParam('limit', 10);
            $search = $this->sanitizeInput($this->getParam('search'));
            $category = $this->getParam('category');
            $minPrice = $this->getParam('min_price');
            $maxPrice = $this->getParam('max_price');
            $inStock = $this->getParam('in_stock');
            $sort = $this->getParam('sort', 'id');
            $order = $this->getParam('order', 'asc');
            
            // Ensure page and limit are valid
            $page = max(1, $page);
            $limit = max(1, min(100, $limit)); // Limit between 1 and 100
            
            // Prepare filters
            $filters = [];
            if ($category) {
                // Validate category parameter - should be numeric for ID lookup
                if (!is_numeric($category)) {
                    sendErrorResponse('Category parameter must be numeric', 400);
                }
                $filters['category_id'] = (int)$category;
            }
            if ($minPrice !== null && is_numeric($minPrice)) {
                $filters['min_price'] = (float)$minPrice;
            }
            if ($maxPrice !== null && is_numeric($maxPrice)) {
                $filters['max_price'] = (float)$maxPrice;
            }
            if ($inStock !== null) {
                $filters['in_stock'] = (int)$inStock;
            }
            $filters['sort'] = $sort;
            $filters['order'] = $order;
            
            // Get products with filters
            try {
                if ($search || !empty($filters)) {
                    // Use enhanced search method
                    $products = $this->productModel->search($search, $filters);
                    sendSuccessResponse($products, 'Products retrieved successfully');
                } else {
                    $products = $this->productModel->getAll();
                    sendSuccessResponse($products, 'Products retrieved successfully');
                }
            } catch (ChatCartException $e) {
                error_log("ChatCart Error in handleGet: " . $e->getMessage());
                sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
            } catch (Exception $e) {
                error_log("Database error in handleGet: " . $e->getMessage());
                sendErrorResponse('Failed to retrieve products', 500);
            }
        }
    }
    
    /**
     * Handle POST requests (create product)
     */
    protected function handlePost() {
        // In a real implementation, you would authenticate the user first
        
        // Validate required fields
        $requiredFields = ['name', 'price'];
        $missingFields = $this->validateRequiredFields($requiredFields);
        
        if ($missingFields) {
            sendValidationErrorResponse($missingFields);
        }
        
        // Sanitize input data with enhanced security
        $name = $this->sanitizeProductName($this->getParam('name'));
        $price = $this->getParam('price');
        $description = $this->sanitizeDescription($this->getParam('description', ''));
        $categoryId = $this->getParam('categoryId', 0);
        $inStock = (bool)$this->getParam('inStock', true);
        $stockQuantity = $this->getParam('stockQuantity');
        $image = $this->sanitizeImageUrl($this->getParam('image', ''));
        $whatsappNumber = $this->getParam('whatsappNumber', DEFAULT_WHATSAPP_NUMBER);
        
        // Validate and sanitize price
        $price = $this->sanitizePrice($price);
        if ($price === false) {
            sendValidationErrorResponse(['price' => 'Invalid price format']);
        }
        
        // Validate price is positive
        if ($price <= 0) {
            sendValidationErrorResponse(['price' => 'Price must be greater than zero']);
        }
        
        // Validate and sanitize category ID
        $categoryId = $this->sanitizeCategoryId($categoryId);
        if ($categoryId === false) {
            $categoryId = 0; // Default to uncategorized
        }
        
        // Validate and sanitize stock quantity if provided
        if ($stockQuantity !== null) {
            $stockQuantity = $this->sanitizeStockQuantity($stockQuantity);
            if ($stockQuantity === false) {
                sendValidationErrorResponse(['stockQuantity' => 'Invalid stock quantity format']);
            }
            // Validate stock quantity is non-negative
            if ($stockQuantity < 0) {
                sendValidationErrorResponse(['stockQuantity' => 'Stock quantity cannot be negative']);
            }
        }
        
        // Sanitize other fields
        $name = $this->sanitizeProductName($name);
        $description = $this->sanitizeDescription($description);
        $image = $this->sanitizeImageUrl($image);
        $whatsappNumber = $this->sanitizeWhatsappNumber($whatsappNumber);
        
        // Additional validation for product name
        if (empty($name)) {
            sendValidationErrorResponse(['name' => 'Product name cannot be empty']);
        }
        
        // Additional validation for WhatsApp number
        if (empty($whatsappNumber)) {
            sendValidationErrorResponse(['whatsappNumber' => 'WhatsApp number is required']);
        }
        
        // Create product data array
        $productData = [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'categoryId' => $categoryId,
            'inStock' => $inStock,
            'stockQuantity' => $stockQuantity,
            'image' => $image,
            'whatsappNumber' => $whatsappNumber
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
        } catch (ChatCartException $e) {
            error_log("ChatCart Error in handlePost: " . $e->getMessage());
            sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
        } catch (Exception $e) {
            error_log("Database error in handlePost: " . $e->getMessage());
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
        $requiredFields = ['name', 'price'];
        $missingFields = $this->validateRequiredFields($requiredFields);
        
        if ($missingFields) {
            sendValidationErrorResponse($missingFields);
        }
        
        // Get parameters
        $name = $this->getParam('name');
        $price = $this->getParam('price');
        $description = $this->getParam('description', $existingProduct['description']);
        $categoryId = $this->getParam('categoryId', $existingProduct['category_id']);
        $inStock = $this->getParam('inStock');
        $stockQuantity = $this->getParam('stockQuantity');
        $image = $this->getParam('image', $existingProduct['image']);
        $whatsappNumber = $this->getParam('whatsappNumber', $existingProduct['whatsappNumber']);
        
        // Validate and sanitize price
        $price = $this->sanitizePrice($price);
        if ($price === false) {
            sendValidationErrorResponse(['price' => 'Invalid price format']);
        }
        
        // Validate price is positive
        if ($price <= 0) {
            sendValidationErrorResponse(['price' => 'Price must be greater than zero']);
        }
        
        // Validate and sanitize category ID
        $categoryId = $this->sanitizeCategoryId($categoryId);
        if ($categoryId === false) {
            $categoryId = $existingProduct['category_id'];
        }
        
        // Validate and sanitize stock quantity if provided
        if ($stockQuantity !== null) {
            $stockQuantity = $this->sanitizeStockQuantity($stockQuantity);
            if ($stockQuantity === false) {
                sendValidationErrorResponse(['stockQuantity' => 'Invalid stock quantity format']);
            }
            // Validate stock quantity is non-negative
            if ($stockQuantity < 0) {
                sendValidationErrorResponse(['stockQuantity' => 'Stock quantity cannot be negative']);
            }
        } else {
            $stockQuantity = $existingProduct['stock_quantity'];
        }
        
        // Handle inStock parameter
        if ($inStock !== null) {
            $inStock = (bool)$inStock;
        } else {
            $inStock = $existingProduct['in_stock'];
        }
        
        // Sanitize other fields
        $name = $this->sanitizeProductName($name);
        $description = $this->sanitizeDescription($description);
        $image = $this->sanitizeImageUrl($image);
        $whatsappNumber = $this->sanitizeWhatsappNumber($whatsappNumber);
        
        // Additional validation for product name
        if (empty($name)) {
            sendValidationErrorResponse(['name' => 'Product name cannot be empty']);
        }
        
        // Additional validation for WhatsApp number
        if (empty($whatsappNumber)) {
            sendValidationErrorResponse(['whatsappNumber' => 'WhatsApp number is required']);
        }
        
        // Create product data array
        $productData = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'categoryId' => $categoryId,
            'inStock' => $inStock,
            'stockQuantity' => $stockQuantity,
            'image' => $image,
            'whatsappNumber' => $whatsappNumber
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
        } catch (ChatCartException $e) {
            error_log("ChatCart Error in handlePut: " . $e->getMessage());
            sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
        } catch (Exception $e) {
            error_log("Database error in handlePut: " . $e->getMessage());
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
        } catch (ChatCartException $e) {
            error_log("ChatCart Error in handleDelete: " . $e->getMessage());
            sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
        } catch (Exception $e) {
            error_log("Database error in handleDelete: " . $e->getMessage());
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
        return trim($name);
    }
    
    /**
     * Sanitize price
     * @param mixed $price Price value
     * @return float|false Sanitized price or false if invalid
     */
    private function sanitizePrice($price) {
        $price = filter_var($price, FILTER_VALIDATE_FLOAT);
        if ($price === false) {
            return false;
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
        return trim($description);
    }
    
    /**
     * Sanitize category ID
     * @param mixed $categoryId Category ID
     * @return int|false Sanitized category ID or false if invalid
     */
    private function sanitizeCategoryId($categoryId) {
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
        if ($categoryId === false) {
            return false;
        }
        return max(0, $categoryId); // Ensure non-negative
    }
    
    /**
     * Sanitize stock quantity
     * @param mixed $stockQuantity Stock quantity
     * @return int|false Sanitized stock quantity or false if invalid
     */
    private function sanitizeStockQuantity($stockQuantity) {
        if ($stockQuantity === null) {
            return null;
        }
        
        $stockQuantity = filter_var($stockQuantity, FILTER_VALIDATE_INT);
        if ($stockQuantity === false) {
            return false;
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
        return trim($imageUrl);
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
        return trim($whatsappNumber);
    }
}

// Process the request
try {
    $controller = new ProductsController();
    $controller->processRequest();
} catch (ChatCartException $e) {
    error_log("ChatCart Unhandled API Error: " . $e->getMessage());
    sendErrorResponse($e->getMessage(), $e->getCode(), $e->getAdditionalData(), $e->getContext());
} catch (Exception $e) {
    error_log("Unhandled API Error: " . $e->getMessage());
    sendErrorResponse('Internal server error occurred', 500);
}
?>