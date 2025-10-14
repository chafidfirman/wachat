# Input Sanitization Implementation

## Overview

This document explains how input sanitization has been implemented in the ChatCart application to prevent security vulnerabilities such as XSS (Cross-Site Scripting) and injection attacks.

## Implementation Details

### Enhanced Sanitization in ApiController

The base `ApiController` class now includes enhanced input sanitization methods:

1. **sanitizeInput()** - Sanitizes all types of input data
2. **validateRequiredFields()** - Validates that required fields are present
3. **getParam()** - Safely retrieves parameters from various sources

### Specific Sanitization in ProductsController

The `ProductsController` includes specialized sanitization methods for different data types:

#### String Sanitization
- HTML special characters are escaped using `htmlspecialchars()`
- Strings are trimmed and stripped of slashes
- Length limits are enforced:
  - Product names: 255 characters maximum
  - Descriptions: 1000 characters maximum
  - Image URLs: 500 characters maximum
  - WhatsApp numbers: 20 characters maximum

#### Numeric Sanitization
- Prices are validated as floating-point numbers
- Prices are rounded to 2 decimal places
- Category IDs and stock quantities are validated as integers
- Negative values are prevented for prices and stock quantities

#### URL Sanitization
- Image URLs are validated using `filter_var()` with `FILTER_VALIDATE_URL`
- Invalid URLs are treated as relative paths and sanitized
- Potentially harmful characters are removed from relative paths

#### Phone Number Sanitization
- WhatsApp numbers are stripped of all non-digit characters (except +)
- Length is limited to prevent abuse

## Security Features

### XSS Prevention
All string inputs are sanitized using `htmlspecialchars()` with `ENT_QUOTES` to prevent XSS attacks.

### SQL Injection Prevention
All database operations use prepared statements with parameterized queries to prevent SQL injection.

### Data Validation
- Required fields are validated before processing
- Data types are strictly enforced
- Value ranges are checked (e.g., prices must be positive)
- String lengths are limited to prevent buffer overflow attacks

### Error Handling
Validation errors are returned in a standardized format with appropriate HTTP status codes:
- 400 Bad Request for missing or invalid parameters
- 422 Unprocessable Entity for validation errors
- 403 Forbidden for CSRF token validation failures

## Usage Examples

### In Controllers
```php
// Sanitize product name
$name = $this->sanitizeProductName($this->getParam('name'));

// Sanitize price
$price = $this->sanitizePrice($this->getParam('price'));

// Validate required fields
$requiredFields = ['name', 'price', 'description'];
$missingFields = $this->validateRequiredFields($requiredFields);
if ($missingFields) {
    sendValidationErrorResponse($missingFields);
}
```

### Custom Sanitization Methods
```php
private function sanitizeProductName($name) {
    $name = $this->sanitizeInput($name);
    // Limit product name length
    $name = substr($name, 0, 255);
    return $name;
}

private function sanitizePrice($price) {
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);
    if ($price === false) {
        sendValidationErrorResponse(['price' => 'Invalid price format']);
    }
    return round($price, 2); // Round to 2 decimal places
}
```

## Best Practices

1. **Always sanitize input**: Never trust user input, always sanitize it before processing
2. **Validate data types**: Ensure data is of the expected type before using it
3. **Enforce length limits**: Prevent buffer overflow attacks by limiting string lengths
4. **Use prepared statements**: Always use parameterized queries for database operations
5. **Return meaningful errors**: Provide clear error messages for validation failures
6. **Log security events**: Log failed validation attempts and security violations

## Testing

Input sanitization is tested through:
1. Unit tests for individual sanitization functions
2. Integration tests for API endpoints
3. Manual testing with various input scenarios

The enhanced sanitization ensures that the application is protected against common security vulnerabilities while maintaining usability and performance.