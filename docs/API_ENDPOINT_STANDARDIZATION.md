# API Endpoint Standardization

This document explains the standardization of API endpoint field names and data structures in the ChatCart Web application.

## Problem

Previously, the API endpoints had inconsistencies between:

1. Request parameter names (snake_case vs camelCase)
2. Response field names (inconsistent naming)
3. Frontend expectations vs backend implementation
4. Database field names vs JSON data structure

These inconsistencies caused confusion and potential bugs when working with the API.

## Solution

We've standardized the API endpoints to use consistent camelCase field names throughout the entire application to maintain alignment with:

1. Frontend JavaScript naming conventions
2. JSON data structure
3. Modern web development practices

## Implementation Details

### Field Name Standardization

All API fields now use camelCase naming convention:

| Old Name (snake_case) | New Name (camelCase) |
|----------------------|---------------------|
| category_id          | categoryId          |
| in_stock             | inStock             |
| stock_quantity       | stockQuantity       |
| whatsapp_number      | whatsappNumber      |

### API Endpoint Updates

#### GET /api/v1/products
Returns products with standardized field names:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "description": "Product description",
      "price": 100000,
      "category": "Electronics",
      "categoryId": 1,
      "image": "assets/img/product.jpg",
      "inStock": true,
      "stockQuantity": 10,
      "whatsappNumber": "6281234567890"
    }
  ]
}
```

#### POST /api/v1/products
Accepts product data with standardized field names:
```json
{
  "name": "New Product",
  "price": 150000,
  "description": "New product description",
  "categoryId": 2,
  "inStock": true,
  "stockQuantity": 5,
  "image": "assets/img/new-product.jpg",
  "whatsappNumber": "6281234567890"
}
```

#### PUT /api/v1/products/{id}
Updates product data with standardized field names:
```json
{
  "id": 1,
  "name": "Updated Product",
  "price": 200000,
  "description": "Updated product description",
  "categoryId": 3,
  "inStock": false,
  "stockQuantity": 0,
  "image": "assets/img/updated-product.jpg",
  "whatsappNumber": "6289876543210"
}
```

### Data Model Updates

The Product model has been updated to handle the standardized field names consistently:

1. API controllers use camelCase parameter names
2. Product model converts camelCase to snake_case for database operations
3. Data normalization maintains camelCase for API responses
4. Backward compatibility is preserved

## Usage Examples

### Creating a Product
```javascript
const productData = {
  name: "Sample Product",
  price: 100000,
  description: "A sample product",
  categoryId: 1,
  inStock: true,
  stockQuantity: 10,
  image: "assets/img/sample.jpg",
  whatsappNumber: "6281234567890"
};

fetch('/api/v1/products', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(productData)
})
.then(response => response.json())
.then(data => console.log(data));
```

### Updating a Product
```javascript
const updatedProductData = {
  id: 1,
  name: "Updated Product",
  price: 150000,
  description: "An updated product",
  categoryId: 2,
  inStock: false,
  stockQuantity: 0,
  image: "assets/img/updated.jpg",
  whatsappNumber: "6289876543210"
};

fetch('/api/v1/products/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(updatedProductData)
})
.then(response => response.json())
.then(data => console.log(data));
```

### Retrieving Products
```javascript
fetch('/api/v1/products')
.then(response => response.json())
.then(data => {
  if (data.success) {
    data.data.forEach(product => {
      console.log(`Product: ${product.name}`);
      console.log(`Price: ${product.price}`);
      console.log(`In Stock: ${product.inStock}`);
      console.log(`Stock Quantity: ${product.stockQuantity}`);
      console.log(`WhatsApp: ${product.whatsappNumber}`);
    });
  }
});
```

## Benefits

1. **Consistency**: Same naming convention used throughout the application
2. **Maintainability**: Easier to understand and modify API-related code
3. **Compatibility**: Consistent with frontend JavaScript naming conventions
4. **Reduced Bugs**: Eliminates confusion from inconsistent field names
5. **Developer Experience**: More intuitive API for frontend developers

## Backward Compatibility

The application maintains backward compatibility by:

1. The data normalization layer handles field name conversions
2. Existing database records continue to work
3. API responses maintain consistent field names
4. Legacy field names are supported where necessary

## Testing

The API functionality is tested in:
- `tests/test_api.php`
- `tests/comprehensive_test_suite.php`
- `api/v1/products/` endpoints
- Integration tests with frontend components

## Future Improvements

1. Add API versioning support
2. Implement comprehensive API documentation
3. Add request/response validation middleware
4. Implement rate limiting for API endpoints
5. Add support for batch operations