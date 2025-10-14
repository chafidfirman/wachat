# CSRF Protection Implementation

## Overview

This document explains how Cross-Site Request Forgery (CSRF) protection has been implemented in the ChatCart application. CSRF protection helps prevent malicious websites from making unauthorized requests on behalf of authenticated users.

## Implementation Details

### Files Added

1. `core/shared/helpers/csrf_helper.php` - Contains CSRF token generation and validation functions
2. `core/shared/Database.php` - Database connection class for proper database operations
3. Enhanced `core/shared/ApiController.php` - Added CSRF validation for write operations
4. Updated `modules/product/models/Product.php` - Added database operations for create, update, and delete
5. Enhanced `api/v1/products/ProductsController.php` - Added comprehensive input sanitization

### CSRF Helper Functions

The CSRF helper provides the following functions:

#### `generateCsrfToken()`
Generates a secure CSRF token and stores it in the session.

#### `validateCsrfToken($token)`
Validates a CSRF token against the one stored in the session.

#### `requireValidCsrfToken($token)`
Requires a valid CSRF token or terminates the request with a 403 error.

#### `csrfField()`
Generates an HTML hidden input field containing the CSRF token.

## Usage Examples

### In HTML Forms

```php
<form method="POST" action="/api/v1/products">
    <?= csrfField() ?>
    <input type="text" name="name" required>
    <input type="number" name="price" step="0.01" required>
    <textarea name="description" required></textarea>
    <button type="submit">Create Product</button>
</form>
```

### In AJAX Requests

```javascript
// Include the token in the request header
fetch('/api/v1/products', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': '<?= generateCsrfToken() ?>'
    },
    body: JSON.stringify({
        name: 'Product Name',
        price: 29.99,
        description: 'Product Description',
        csrf_token: '<?= generateCsrfToken() ?>' // Alternative: include in body
    })
})
```

## Security Enhancements

### Input Sanitization

The ProductsController now includes enhanced input sanitization:

1. **Product Name**: Limited to 255 characters, HTML special characters escaped
2. **Price**: Validated as float, rounded to 2 decimal places, must be positive
3. **Description**: Limited to 1000 characters, HTML special characters escaped
4. **Category ID**: Validated as integer, non-negative
5. **Stock Quantity**: Validated as integer, non-negative
6. **Image URL**: Validated as URL or sanitized path, limited to 500 characters
7. **WhatsApp Number**: Sanitized to digits only, limited to 20 characters

### Database Operations

The Product model now supports full CRUD operations with the database:

1. **Create**: Insert new products into the database
2. **Read**: Retrieve products from the database
3. **Update**: Modify existing products in the database
4. **Delete**: Remove products from the database

All database operations use prepared statements to prevent SQL injection attacks.

## Testing

A test file is available at `tests/test_csrf.php` to verify the CSRF protection functionality.

## Best Practices

1. Always include CSRF tokens in forms that modify data
2. Validate CSRF tokens on all POST, PUT, and DELETE requests
3. Use HTTPS in production to prevent token interception
4. Regenerate CSRF tokens periodically for long-lived sessions
5. Implement proper error handling for failed CSRF validation

## API Security

The API endpoints now require CSRF tokens for write operations:

- POST requests to create products
- PUT requests to update products
- DELETE requests to remove products

GET requests for retrieving data do not require CSRF tokens as they don't modify data.