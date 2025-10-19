# ChatCart Web API Documentation

This document provides comprehensive documentation for the ChatCart Web API, including endpoints, request/response formats, and usage examples.

## Table of Contents

1. [Overview](#overview)
2. [Base URL](#base-url)
3. [Authentication](#authentication)
4. [Rate Limiting](#rate-limiting)
5. [Error Handling](#error-handling)
6. [API Endpoints](#api-endpoints)
   - [Products](#products)
   - [Categories](#categories)
7. [Data Structures](#data-structures)
8. [Usage Examples](#usage-examples)
9. [Testing](#testing)

## Overview

The ChatCart Web API provides programmatic access to product information, categories, and other e-commerce data. The API follows REST principles and returns JSON responses.

## Base URL

All API endpoints are relative to the base URL:
```
http://localhost/wachat/api/v1/
```

In production, this would be:
```
https://yourdomain.com/api/v1/
```

## Authentication

Currently, the API does not require authentication for read operations. Administrative operations will require authentication in future versions.

## Rate Limiting

The API currently has no rate limiting. In production environments, rate limiting should be implemented to prevent abuse.

## Error Handling

The API uses standard HTTP status codes to indicate the success or failure of requests:

### HTTP Status Codes
- `200 OK` - Successful request
- `400 Bad Request` - Invalid request parameters
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

### Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "error_code": "ERROR_CODE"
}
```

## API Endpoints

### Products

#### Get All Products
```
GET /products
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "description": "Product description",
      "price": 99000,
      "category_id": 1,
      "category": "Electronics",
      "image": "assets/img/product1.jpg",
      "in_stock": true,
      "stock_quantity": 10,
      "created_at": "2025-10-17 10:00:00",
      "updated_at": "2025-10-17 10:00:00"
    }
  ]
}
```

#### Get Product by ID
```
GET /products/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "description": "Product description",
    "price": 99000,
    "category_id": 1,
    "category": "Electronics",
    "image": "assets/img/product1.jpg",
    "in_stock": true,
    "stock_quantity": 10,
    "created_at": "2025-10-17 10:00:00",
    "updated_at": "2025-10-17 10:00:00"
  }
}
```

#### Search Products
```
GET /products?search={query}
```

**Parameters:**
- `search` - Search term to filter products

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "description": "Product description",
      "price": 99000,
      "category_id": 1,
      "category": "Electronics",
      "image": "assets/img/product1.jpg",
      "in_stock": true,
      "stock_quantity": 10
    }
  ]
}
```

#### Filter Products by Category
```
GET /products?category={category_id}
```

**Parameters:**
- `category` - Category ID to filter products

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "description": "Product description",
      "price": 99000,
      "category_id": 1,
      "category": "Electronics",
      "image": "assets/img/product1.jpg",
      "in_stock": true,
      "stock_quantity": 10
    }
  ]
}
```

### Categories

#### Get All Categories
```
GET /categories
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "description": "Electronic devices and accessories",
      "created_at": "2025-10-17 10:00:00"
    }
  ]
}
```

#### Get Category by ID
```
GET /categories/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Electronics",
    "description": "Electronic devices and accessories",
    "created_at": "2025-10-17 10:00:00"
  }
}
```

## Data Structures

### Product Object
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique product identifier |
| name | string | Product name |
| description | string | Product description |
| price | integer | Product price in cents (IDR) |
| category_id | integer | Category identifier |
| category | string | Category name |
| image | string | Image path or URL |
| in_stock | boolean | Stock availability |
| stock_quantity | integer | Number of items in stock |
| created_at | string | Creation timestamp |
| updated_at | string | Last update timestamp |

### Category Object
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique category identifier |
| name | string | Category name |
| description | string | Category description |
| created_at | string | Creation timestamp |

## Usage Examples

### JavaScript Example
```javascript
// Fetch all products
async function fetchProducts() {
  try {
    const response = await fetch('/api/v1/products');
    const result = await response.json();
    
    if (result.success) {
      console.log('Products:', result.data);
      return result.data;
    } else {
      console.error('Error fetching products:', result.message);
      return [];
    }
  } catch (error) {
    console.error('Network error:', error);
    return [];
  }
}

// Fetch product by ID
async function fetchProductById(productId) {
  try {
    const response = await fetch(`/api/v1/products/${productId}`);
    const result = await response.json();
    
    if (result.success) {
      console.log('Product:', result.data);
      return result.data;
    } else {
      console.error('Error fetching product:', result.message);
      return null;
    }
  } catch (error) {
    console.error('Network error:', error);
    return null;
  }
}
```

### PHP Example
```php
// Fetch all products
function fetchProducts() {
  $url = site_url('api/v1/products');
  $response = file_get_contents($url);
  $data = json_decode($response, true);
  
  if ($data['success']) {
    return $data['data'];
  }
  
  return [];
}

// Fetch product by ID
function fetchProductById($productId) {
  $url = site_url("api/v1/products/{$productId}");
  $response = file_get_contents($url);
  $data = json_decode($response, true);
  
  if ($data['success']) {
    return $data['data'];
  }
  
  return null;
}
```

## Testing

### API Test Script
The application includes a test script at `tests/test-api.html` to verify API functionality.

### Manual Testing
You can test API endpoints directly using your browser or tools like curl:

```bash
# Get all products
curl http://localhost/wachat/api/v1/products

# Get product by ID
curl http://localhost/wachat/api/v1/products/1

# Search products
curl "http://localhost/wachat/api/v1/products?search=laptop"
```

## Future Enhancements

### Planned API Features
1. User authentication and authorization
2. Shopping cart management
3. Order processing
4. Customer management
5. Payment integration
6. Advanced filtering and sorting options
7. Pagination for large datasets

### Versioning
Future API versions will be accessible at:
```
/api/v2/
/api/v3/
```

## Conclusion

This API documentation provides a comprehensive guide to integrating with the ChatCart Web application. For additional support, refer to the development documentation or contact the development team.