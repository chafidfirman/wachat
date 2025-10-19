<?php
require_once __DIR__ . '/../../../core/shared/config/database.php';
require_once __DIR__ . '/../../../core/shared/Database.php';
require_once __DIR__ . '/../../../core/shared/helpers/image_helper.php';
require_once __DIR__ . '/../../../core/shared/helpers/debug_helper.php';
require_once __DIR__ . '/../../../core/shared/exceptions/ChatCartException.php';

class Product {
    private $pdo;
    private $productsFile;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->productsFile = __DIR__ . '/../../../data/products.json';
    }
    
    // Get all products
    public function getAll() {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.id DESC");
                $stmt->execute();
                $products = $stmt->fetchAll();
                return $this->normalizeProducts($products);
            } catch (PDOException $e) {
                error_log("Database error in getAll: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('getAll'));
                }
                error_log("Falling back to JSON data for getAll");
                // Fallback to JSON
                return $this->getAllFromJson();
            }
        }
        
        // Fallback to JSON implementation
        error_log("No database connection available, using JSON data for getAll");
        return $this->getAllFromJson();
    }
    
    // Get all products from JSON (fallback)
    private function getAllFromJson() {
        if (file_exists($this->productsFile)) {
            $json = file_get_contents($this->productsFile);
            $data = json_decode($json, true);
            return is_array($data) ? $this->normalizeProducts($data) : [];
        }
        return [];
    }
    
    // Get product by ID
    public function getById($id) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.is_active = 1");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                return $product ? $this->normalizeProduct($product) : null;
            } catch (PDOException $e) {
                error_log("Database error in getById: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('getById'));
                }
                error_log("Falling back to JSON data for getById");
                // Fallback to JSON
                return $this->getByIdFromJson($id);
            }
        }
        
        // Fallback to JSON implementation
        error_log("No database connection available, using JSON data for getById");
        return $this->getByIdFromJson($id);
    }
    
    // Get product by ID from JSON (fallback)
    private function getByIdFromJson($id) {
        $products = $this->getAllFromJson();
        foreach ($products as $product) {
            // Use strict comparison after converting both to the same type
            if ((string)$product['id'] === (string)$id) {
                // Add category_name field to match the expected structure
                $product['category_name'] = $product['category'];
                return $product;
            }
        }
        return null;
    }
    
    // Get products by category
    public function getByCategory($categoryId) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.is_active = 1 ORDER BY p.id DESC");
                $stmt->execute([$categoryId]);
                $products = $stmt->fetchAll();
                return $this->normalizeProducts($products);
            } catch (PDOException $e) {
                error_log("Database error in getByCategory: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('getByCategory'));
                }
                // Fallback to JSON - but we can't filter by category in JSON
                // So we'll return all products and let the caller handle filtering if needed
                return $this->getAllFromJson();
            }
        }
        
        // For JSON implementation, we can't easily filter by category
        // So we'll return all products and let the caller handle filtering
        return $this->getAllFromJson();
    }
    
    // Enhanced search products with advanced filtering
    public function search($keyword = '', $filters = []) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                // Build the query dynamically based on provided filters
                $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
                $params = [];
                
                // Add keyword search if provided
                if (!empty($keyword)) {
                    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
                    $searchTerm = '%' . $keyword . '%';
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                }
                
                // Add category filter if provided
                if (!empty($filters['category_id'])) {
                    $sql .= " AND p.category_id = ?";
                    $params[] = $filters['category_id'];
                }
                
                // Add price range filters if provided
                if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
                    $sql .= " AND p.price >= ?";
                    $params[] = $filters['min_price'];
                }
                
                if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
                    $sql .= " AND p.price <= ?";
                    $params[] = $filters['max_price'];
                }
                
                // Add stock status filter if provided
                if (isset($filters['in_stock'])) {
                    if ($filters['in_stock']) {
                        $sql .= " AND p.is_active = 1 AND (p.stock_quantity IS NULL OR p.stock_quantity > 0)";
                    } else {
                        $sql .= " AND (p.is_active = 0 OR p.stock_quantity = 0)";
                    }
                }
                
                // Add sorting if provided
                $sortField = isset($filters['sort']) ? $filters['sort'] : 'id';
                $sortOrder = isset($filters['order']) && strtolower($filters['order']) === 'desc' ? 'DESC' : 'ASC';
                
                // Validate sort field to prevent SQL injection
                $allowedSortFields = ['id', 'name', 'price', 'created_at'];
                if (!in_array($sortField, $allowedSortFields)) {
                    $sortField = 'id';
                }
                
                $sql .= " ORDER BY p.{$sortField} {$sortOrder}";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                $products = $stmt->fetchAll();
                return $this->normalizeProducts($products);
            } catch (PDOException $e) {
                error_log("Database error in enhanced search: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('search'));
                }
                // Fallback to basic search
                return $this->basicSearch($keyword);
            }
        }
        
        // For JSON implementation, we can't easily search
        // So we'll return all products and let the caller handle filtering
        return $this->getAllFromJson();
    }
    
    // Basic search for fallback
    private function basicSearch($keyword) {
        if (empty($keyword)) {
            return $this->getAllFromJson();
        }
        
        $products = $this->getAllFromJson();
        $filteredProducts = array_filter($products, function($product) use ($keyword) {
            return stripos($product['name'], $keyword) !== false || stripos($product['description'], $keyword) !== false;
        });
        
        return array_values($filteredProducts);
    }
    
    // Get related products
    public function getRelated($productId, $category) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id != ? AND p.category_id = (SELECT category_id FROM products WHERE id = ?) AND p.is_active = 1 ORDER BY RAND() LIMIT 4");
                $stmt->execute([$productId, $productId]);
                $products = $stmt->fetchAll();
                return $this->normalizeProducts($products);
            } catch (PDOException $e) {
                error_log("Database error in getRelated: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('getRelated'));
                }
                // Fallback to JSON - but we can't easily get related products from JSON
                // So we'll return an empty array
                return [];
            }
        }
        
        // For JSON implementation, we can't easily get related products
        // So we'll return an empty array
        return [];
    }
    
    // Log WhatsApp click
    public function logWhatsAppClick($productId) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("UPDATE products SET whatsapp_clicks = whatsapp_clicks + 1 WHERE id = ?");
                return $stmt->execute([$productId]);
            } catch (PDOException $e) {
                error_log("Database error in logWhatsAppClick: " . $e->getMessage());
                // Log the error with more details
                if (class_exists('Database')) {
                    $db = new Database();
                    error_log("Database error details: " . $db->getErrorMessage('logWhatsAppClick'));
                }
                error_log("Skipping WhatsApp click logging due to database error");
                // For JSON implementation, we'll just return true
                return true;
            }
        }
        
        // For JSON implementation, we'll just return true
        error_log("No database connection available, skipping WhatsApp click logging");
        return true;
    }
    
    // Create a new product (for API)
    public function create($productData) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO products (name, slug, price, stock_quantity, description, image, category_id, is_active, whatsapp_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                // Generate slug from name
                $slug = $this->generateSlug($productData['name']);
                
                // Default values
                $stock = $productData['stockQuantity'] ?? null;
                $inStock = $productData['inStock'] ?? true;
                $categoryId = $productData['categoryId'] ?? 0;
                $description = $productData['description'] ?? '';
                $image = $productData['image'] ?? '';
                $whatsappNumber = $productData['whatsappNumber'] ?? DEFAULT_WHATSAPP_NUMBER;
                
                $stmt->execute([
                    $productData['name'],
                    $slug,
                    $productData['price'],
                    $stock,
                    $description,
                    $image,
                    $categoryId,
                    $inStock ? 1 : 0,
                    $whatsappNumber
                ]);
                
                // Get the inserted ID
                $id = $this->pdo->lastInsertId();
                
                // Return the created product
                return $this->getById($id);
            } catch (PDOException $e) {
                error_log("Database error in create: " . $e->getMessage());
                throw new DatabaseException("Failed to create product: " . $e->getMessage(), 'Product Creation');
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new ChatCartException("Product creation not supported in JSON mode", 500, 'Product Creation', ChatCartException::SEVERITY_ERROR);
    }
    
    // Update a product (for API)
    public function update($productData) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("UPDATE products SET name = ?, slug = ?, price = ?, stock_quantity = ?, description = ?, image = ?, category_id = ?, is_active = ?, whatsapp_number = ? WHERE id = ?");
                
                // Generate slug from name
                $slug = $this->generateSlug($productData['name']);
                
                // Extract values with defaults
                $stock = $productData['stockQuantity'] ?? null;
                $inStock = $productData['inStock'] ?? true;
                $categoryId = $productData['categoryId'] ?? 0;
                $description = $productData['description'] ?? '';
                $image = $productData['image'] ?? '';
                $whatsappNumber = $productData['whatsappNumber'] ?? DEFAULT_WHATSAPP_NUMBER;
                
                $stmt->execute([
                    $productData['name'],
                    $slug,
                    $productData['price'],
                    $stock,
                    $description,
                    $image,
                    $categoryId,
                    $inStock ? 1 : 0,
                    $whatsappNumber,
                    $productData['id']
                ]);
                
                // Return the updated product
                return $this->getById($productData['id']);
            } catch (PDOException $e) {
                error_log("Database error in update: " . $e->getMessage());
                throw new DatabaseException("Failed to update product: " . $e->getMessage(), 'Product Update');
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new ChatCartException("Product update not supported in JSON mode", 500, 'Product Update', ChatCartException::SEVERITY_ERROR);
    }
    
    // Delete a product (for API)
    public function delete($productId) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
                return $stmt->execute([$productId]);
            } catch (PDOException $e) {
                error_log("Database error in delete: " . $e->getMessage());
                throw new DatabaseException("Failed to delete product: " . $e->getMessage(), 'Product Deletion');
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new ChatCartException("Product deletion not supported in JSON mode", 500, 'Product Deletion', ChatCartException::SEVERITY_ERROR);
    }
    
    // Generate slug from name
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    // Normalize products data structure
    private function normalizeProducts($products) {
        foreach ($products as &$product) {
            $product = $this->normalizeProduct($product);
        }
        return $products;
    }
    
    // Normalize product data structure
    private function normalizeProduct($product) {
        // Ensure consistent field names
        if (isset($product['stock_quantity'])) {
            $product['stockQuantity'] = $product['stock_quantity'];
            unset($product['stock_quantity']);
        }
        
        if (isset($product['is_active'])) {
            $product['inStock'] = (bool)$product['is_active'];
            unset($product['is_active']);
        }
        
        if (isset($product['whatsapp_number'])) {
            $product['whatsappNumber'] = $product['whatsapp_number'];
            unset($product['whatsapp_number']);
        }
        
        if (isset($product['category_name'])) {
            $product['category'] = $product['category_name'];
            unset($product['category_name']);
        }
        
        // Ensure image path is normalized
        if (isset($product['image'])) {
            $product['image'] = normalizeImagePath($product['image']);
        }
        
        // Set default values if not present
        if (!isset($product['stockQuantity'])) {
            $product['stockQuantity'] = null;
        }
        
        if (!isset($product['inStock'])) {
            $product['inStock'] = true;
        }
        
        if (!isset($product['whatsappNumber'])) {
            $product['whatsappNumber'] = DEFAULT_WHATSAPP_NUMBER;
        }
        
        return $product;
    }
}
?>