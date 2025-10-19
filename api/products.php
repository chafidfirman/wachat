<?php
// Include configuration and debug helper
require_once __DIR__ . '/../config.php';

// Turn off error reporting to prevent PHP errors from interfering with JSON response
// (This will be overridden if debug mode is enabled)
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Path to the products JSON file
$productsFile = '../data/products.json';

// Read products from JSON file
function getProducts() {
    global $productsFile;
    // Log the query for debugging
    logQuery("Reading products from JSON file: " . realpath($productsFile));
    
    if (file_exists($productsFile)) {
        $json = file_get_contents($productsFile);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
    return [];
}

// Save products to JSON file
function saveProducts($products) {
    global $productsFile;
    // Log the query for debugging
    logQuery("Saving products to JSON file: " . realpath($productsFile));
    
    // Validate data before saving
    if (!is_array($products)) {
        logError("Invalid data format for saving products");
        return false;
    }
    
    $json = json_encode($products, JSON_PRETTY_PRINT);
    if ($json === false) {
        logError("Failed to encode products data to JSON: " . json_last_error_msg());
        return false;
    }
    
    $result = file_put_contents($productsFile, $json) !== false;
    if (!$result) {
        logError("Failed to write products data to file: " . realpath($productsFile));
    }
    return $result;
}

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];
logQuery("API Request Method: " . $method);

// Get the request data
$input = json_decode(file_get_contents('php://input'), true);
logFormSubmission("API Request", ['method' => $method, 'input' => $input]);

// Validate JSON input
if ($method !== 'GET' && (json_last_error() !== JSON_ERROR_NONE || !is_array($input))) {
    logError("Invalid JSON input received: " . json_last_error_msg());
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

// Ensure we don't have any previous output
if (ob_get_level()) {
    ob_clean();
}

try {
    switch ($method) {
        case 'GET':
            // Return all products
            $products = getProducts();
            // Normalize product data to ensure consistent structure
            foreach ($products as &$product) {
                // Ensure stockQuantity is set
                if (!isset($product['stockQuantity']) && isset($product['stock'])) {
                    $product['stockQuantity'] = $product['stock'];
                    unset($product['stock']);
                }
                
                // Ensure whatsappNumber is set
                if (!isset($product['whatsappNumber']) && isset($product['whatsapp_number'])) {
                    $product['whatsappNumber'] = $product['whatsapp_number'];
                    unset($product['whatsapp_number']);
                }
                
                // Ensure inStock is set
                if (!isset($product['inStock']) && isset($product['in_stock'])) {
                    $product['inStock'] = $product['in_stock'];
                    unset($product['in_stock']);
                }
            }
            logQuery("Returning " . count($products) . " products");
            echo json_encode($products);
            break;
            
        case 'POST':
            // Validate required fields
            if (!isset($input['name']) || !isset($input['price'])) {
                logError("Missing required fields for product creation");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Missing required fields: name and price are required']);
                exit;
            }
            
            // Add a new product
            $products = getProducts();
            
            // Generate new ID
            $newId = 1;
            if (!empty($products)) {
                $ids = array_column($products, 'id');
                $newId = !empty($ids) ? max($ids) + 1 : 1;
            }
            
            // Include image helper
            require_once __DIR__ . '/../core/shared/helpers/image_helper.php';
            
            // Sanitize input
            $name = trim($input['name']);
            $price = floatval($input['price']);
            $description = isset($input['description']) ? trim($input['description']) : '';
            $category = isset($input['category']) ? trim($input['category']) : 'General';
            $image = isset($input['image']) ? normalizeImagePath(trim($input['image'])) : 'assets/img/product-default.jpg';
            $inStock = isset($input['inStock']) ? boolval($input['inStock']) : true;
            $stockQuantity = isset($input['stockQuantity']) ? intval($input['stockQuantity']) : null; // null means unlimited stock
            $whatsappNumber = isset($input['whatsappNumber']) ? trim($input['whatsappNumber']) : '';
            
            // Validate price
            if ($price <= 0) {
                logError("Invalid price for product creation");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Price must be greater than zero']);
                exit;
            }
            
            // Validate stock quantity if provided
            if ($stockQuantity !== null && $stockQuantity < 0) {
                logError("Invalid stock quantity for product creation");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Stock quantity cannot be negative']);
                exit;
            }
            
            // Validate product name
            if (empty($name)) {
                logError("Empty product name for product creation");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Product name cannot be empty']);
                exit;
            }
            
            // Create new product
            $newProduct = [
                'id' => $newId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'image' => $image,
                'inStock' => $inStock,
                'stockQuantity' => $stockQuantity,
                'whatsappNumber' => $whatsappNumber
            ];
            
            // Add to products array
            $products[] = $newProduct;
            
            // Save and return result
            if (saveProducts($products)) {
                logQuery("Successfully created new product with ID: " . $newId);
                echo json_encode(['success' => true, 'product' => $newProduct]);
            } else {
                logError("Failed to save new product");
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to save product']);
            }
            break;
            
        case 'PUT':
            // Validate required fields
            if (!isset($input['id'])) {
                logError("Product ID is required for update");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Product ID is required']);
                exit;
            }
            
            // Update an existing product
            $products = getProducts();
            $productId = intval($input['id']);
            
            // Find the product
            $index = -1;
            foreach ($products as $i => $product) {
                if ($product['id'] == $productId) {
                    $index = $i;
                    break;
                }
            }
            
            if ($index !== -1) {
                // Sanitize input
                $name = isset($input['name']) ? trim($input['name']) : $products[$index]['name'];
                $price = isset($input['price']) ? floatval($input['price']) : $products[$index]['price'];
                $description = isset($input['description']) ? trim($input['description']) : $products[$index]['description'];
                $category = isset($input['category']) ? trim($input['category']) : $products[$index]['category'];
                $image = isset($input['image']) ? normalizeImagePath(trim($input['image'])) : $products[$index]['image'];
                $inStock = isset($input['inStock']) ? boolval($input['inStock']) : $products[$index]['inStock'];
                $stockQuantity = isset($input['stockQuantity']) ? intval($input['stockQuantity']) : $products[$index]['stockQuantity'];
                $whatsappNumber = isset($input['whatsappNumber']) ? trim($input['whatsappNumber']) : $products[$index]['whatsappNumber'];
                
                // Validate price
                if ($price <= 0) {
                    logError("Invalid price for product update with ID: " . $productId);
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Price must be greater than zero']);
                    exit;
                }
                
                // Validate stock quantity if provided
                if ($stockQuantity !== null && $stockQuantity < 0) {
                    logError("Invalid stock quantity for product update with ID: " . $productId);
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Stock quantity cannot be negative']);
                    exit;
                }
                
                // Validate product name
                if (empty($name)) {
                    logError("Empty product name for product update with ID: " . $productId);
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Product name cannot be empty']);
                    exit;
                }
                
                // Update product
                $products[$index] = [
                    'id' => $productId,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category' => $category,
                    'image' => $image,
                    'inStock' => $inStock,
                    'stockQuantity' => $stockQuantity,
                    'whatsappNumber' => $whatsappNumber
                ];
                
                // Save and return result
                if (saveProducts($products)) {
                    logQuery("Successfully updated product with ID: " . $productId);
                    echo json_encode(['success' => true, 'product' => $products[$index]]);
                } else {
                    logError("Failed to update product with ID: " . $productId);
                    http_response_code(500);
                    echo json_encode(['success' => false, 'error' => 'Failed to update product']);
                }
            } else {
                logError("Product not found for update with ID: " . $productId);
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Product not found']);
            }
            break;
            
        case 'DELETE':
            // Validate required fields
            if (!isset($input['id'])) {
                logError("Product ID is required for deletion");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Product ID is required']);
                exit;
            }
            
            // Delete a product
            $products = getProducts();
            $productId = intval($input['id']);
            
            // Find the product
            $index = -1;
            foreach ($products as $i => $product) {
                if ($product['id'] == $productId) {
                    $index = $i;
                    break;
                }
            }
            
            if ($index !== -1) {
                // Log the delete operation
                logDeleteOperation("Product", $productId);
                
                // Remove product
                array_splice($products, $index, 1);
                
                // Save and return result
                if (saveProducts($products)) {
                    logQuery("Successfully deleted product with ID: " . $productId);
                    echo json_encode(['success' => true]);
                } else {
                    logError("Failed to delete product with ID: " . $productId);
                    http_response_code(500);
                    echo json_encode(['success' => false, 'error' => 'Failed to delete product']);
                }
            } else {
                logError("Product not found for deletion with ID: " . $productId);
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Product not found']);
            }
            break;
            
        default:
            logError("Method not allowed: " . $method);
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    error_log("API Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Internal server error occurred']);
}

// Display debug overlay if debug mode is enabled
displayDebugOverlay();
?>