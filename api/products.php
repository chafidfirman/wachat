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
        logError("Failed to encode products data to JSON");
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
    logError("Invalid JSON input received");
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

switch ($method) {
    case 'GET':
        // Return all products
        $products = getProducts();
        logQuery("Returning " . count($products) . " products");
        echo json_encode($products);
        break;
        
    case 'POST':
        // Validate required fields
        if (!isset($input['name']) || !isset($input['price']) || !isset($input['whatsappNumber'])) {
            logError("Missing required fields for product creation");
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing required fields: name, price, and whatsappNumber are required']);
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
        
        // Create new product
        $newProduct = [
            'id' => $newId,
            'name' => trim($input['name']),
            'description' => isset($input['description']) ? trim($input['description']) : '',
            'price' => intval($input['price']),
            'category' => isset($input['category']) ? trim($input['category']) : 'General',
            'image' => isset($input['image']) ? trim($input['image']) : 'assets/img/product-default.jpg',
            'inStock' => isset($input['inStock']) ? boolval($input['inStock']) : true,
            'stockQuantity' => isset($input['stockQuantity']) ? intval($input['stockQuantity']) : null, // null means unlimited stock
            'whatsappNumber' => trim($input['whatsappNumber'])
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
            // Update product
            $products[$index] = [
                'id' => $productId,
                'name' => isset($input['name']) ? trim($input['name']) : $products[$index]['name'],
                'description' => isset($input['description']) ? trim($input['description']) : $products[$index]['description'],
                'price' => isset($input['price']) ? intval($input['price']) : $products[$index]['price'],
                'category' => isset($input['category']) ? trim($input['category']) : $products[$index]['category'],
                'image' => isset($input['image']) ? trim($input['image']) : $products[$index]['image'],
                'inStock' => isset($input['inStock']) ? boolval($input['inStock']) : $products[$index]['inStock'],
                'stockQuantity' => isset($input['stockQuantity']) ? intval($input['stockQuantity']) : $products[$index]['stockQuantity'],
                'whatsappNumber' => isset($input['whatsappNumber']) ? trim($input['whatsappNumber']) : $products[$index]['whatsappNumber']
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

// Display debug overlay if debug mode is enabled
displayDebugOverlay();
?>