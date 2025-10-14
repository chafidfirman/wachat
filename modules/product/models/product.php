<?php
class Product {
    private $productsFile;
    private $pdo;
    
    public function __construct($pdo = null) {
        $this->productsFile = __DIR__ . '/../../data/products.json';
        $this->pdo = $pdo;
    }
    
    // Get all products
    public function getAll() {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY p.created_at DESC");
                $stmt->execute();
                $products = $stmt->fetchAll();
                
                // If no products found in database, fallback to JSON
                if (empty($products)) {
                    return $this->getAllFromJson();
                }
                
                return $products;
            } catch (PDOException $e) {
                error_log("Database error in getAll: " . $e->getMessage());
                // Fallback to JSON
                return $this->getAllFromJson();
            }
        }
        
        // Fallback to JSON implementation
        return $this->getAllFromJson();
    }
    
    // Get all products from JSON (fallback)
    private function getAllFromJson() {
        if (file_exists($this->productsFile)) {
            $json = file_get_contents($this->productsFile);
            $data = json_decode($json, true);
            return is_array($data) ? $data : [];
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
                return $stmt->fetch();
            } catch (PDOException $e) {
                error_log("Database error in getById: " . $e->getMessage());
                // Fallback to JSON
                return $this->getByIdFromJson($id);
            }
        }
        
        // Fallback to JSON implementation
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
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.is_active = 1 ORDER BY p.created_at DESC");
                $stmt->execute([$categoryId]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database error in getByCategory: " . $e->getMessage());
                // Fallback to JSON
                return $this->getByCategoryFromJson($categoryId);
            }
        }
        
        // Fallback to JSON implementation
        return $this->getByCategoryFromJson($categoryId);
    }
    
    // Get products by category from JSON (fallback)
    private function getByCategoryFromJson($categoryId) {
        // Since we're using strings for categories in JSON, we'll treat $categoryId as the category name
        $products = $this->getAllFromJson();
        $result = [];
        foreach ($products as $product) {
            if ($product['category'] == $categoryId) {
                // Add category_name field to match the expected structure
                $product['category_name'] = $product['category'];
                $result[] = $product;
            }
        }
        return $result;
    }
    
    // Search products
    public function search($keyword) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE (p.name LIKE ? OR p.description LIKE ?) AND p.is_active = 1 ORDER BY p.created_at DESC");
                $searchTerm = "%{$keyword}%";
                $stmt->execute([$searchTerm, $searchTerm]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database error in search: " . $e->getMessage());
                // Fallback to JSON
                return $this->searchFromJson($keyword);
            }
        }
        
        // Fallback to JSON implementation
        return $this->searchFromJson($keyword);
    }
    
    // Search products from JSON (fallback)
    private function searchFromJson($keyword) {
        $products = $this->getAllFromJson();
        $result = [];
        foreach ($products as $product) {
            if (stripos($product['name'], $keyword) !== false || stripos($product['description'], $keyword) !== false) {
                // Add category_name field to match the expected structure
                $product['category_name'] = $product['category'];
                $result[] = $product;
            }
        }
        return $result;
    }
    
    // Get related products
    public function getRelated($productId, $category, $limit = 4) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id != ? AND p.category_id = ? AND p.is_active = 1 ORDER BY p.created_at DESC LIMIT ?");
                $stmt->execute([$productId, $category, $limit]);
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Database error in getRelated: " . $e->getMessage());
                // Fallback to JSON
                return $this->getRelatedFromJson($productId, $category, $limit);
            }
        }
        
        // Fallback to JSON implementation
        return $this->getRelatedFromJson($productId, $category, $limit);
    }
    
    // Get related products from JSON (fallback)
    private function getRelatedFromJson($productId, $category, $limit = 4) {
        // Since we're using strings for categories in JSON, we'll treat $category as the category name
        $products = $this->getAllFromJson();
        $result = [];
        foreach ($products as $product) {
            if ($product['id'] != $productId && $product['category'] == $category) {
                // Add category_name field to match the expected structure
                $product['category_name'] = $product['category'];
                $result[] = $product;
                if (count($result) >= $limit) {
                    break;
                }
            }
        }
        return $result;
    }
    
    // Log WhatsApp click
    public function logWhatsAppClick($productId) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO wa_clicks (product_id) VALUES (?)");
                return $stmt->execute([$productId]);
            } catch (PDOException $e) {
                error_log("Database error in logWhatsAppClick: " . $e->getMessage());
                // For JSON implementation, we'll just return true
                return true;
            }
        }
        
        // For JSON implementation, we'll just return true
        return true;
    }
    
    // Create a new product (for API)
    public function create($productData) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO products (name, slug, price, stock, description, category_id, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                // Generate slug from name
                $slug = $this->generateSlug($productData['name']);
                
                // Default values
                $stock = $productData['stock_quantity'] ?? null;
                $categoryId = $productData['category_id'] ?? null;
                $isActive = $productData['in_stock'] ? 1 : 0;
                
                $stmt->execute([
                    $productData['name'],
                    $slug,
                    $productData['price'],
                    $stock,
                    $productData['description'],
                    $categoryId,
                    $isActive
                ]);
                
                $productId = $this->pdo->lastInsertId();
                
                // Return the created product
                return $this->getById($productId);
            } catch (PDOException $e) {
                error_log("Database error in create: " . $e->getMessage());
                throw new Exception("Failed to create product: " . $e->getMessage());
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new Exception("Product creation not supported in JSON mode");
    }
    
    // Update a product (for API)
    public function update($productData) {
        // If we have a database connection, use it
        if ($this->pdo) {
            try {
                $stmt = $this->pdo->prepare("UPDATE products SET name = ?, slug = ?, price = ?, stock = ?, description = ?, category_id = ?, is_active = ? WHERE id = ?");
                
                // Generate slug from name
                $slug = $this->generateSlug($productData['name']);
                
                // Default values
                $stock = $productData['stock_quantity'] ?? null;
                $categoryId = $productData['category_id'] ?? null;
                $isActive = $productData['in_stock'] ? 1 : 0;
                
                $stmt->execute([
                    $productData['name'],
                    $slug,
                    $productData['price'],
                    $stock,
                    $productData['description'],
                    $categoryId,
                    $isActive,
                    $productData['id']
                ]);
                
                // Return the updated product
                return $this->getById($productData['id']);
            } catch (PDOException $e) {
                error_log("Database error in update: " . $e->getMessage());
                throw new Exception("Failed to update product: " . $e->getMessage());
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new Exception("Product update not supported in JSON mode");
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
                throw new Exception("Failed to delete product: " . $e->getMessage());
            }
        }
        
        // For JSON implementation, we'll throw an exception as it's not supported
        throw new Exception("Product deletion not supported in JSON mode");
    }
    
    // Generate slug from name
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
?>