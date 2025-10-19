# Comprehensive Error Handling Guide

## Overview

This guide provides comprehensive documentation on the error handling and logging improvements implemented in the ChatCart Web application. It covers the architecture, implementation details, best practices, and usage examples.

## Error Handling Architecture

### Exception Hierarchy

The application implements a centralized exception handling system with the following hierarchy:

1. **ChatCartException** - Base exception class
   - **DatabaseException** - Database-related errors
   - **ValidationException** - Input validation errors
   - **AuthenticationException** - Authentication failures
   - **AuthorizationException** - Authorization failures
   - **NotFoundException** - Resource not found errors

### Error Severity Levels

1. **DEBUG** - Detailed information for diagnosing problems
2. **INFO** - General information about application execution
3. **WARNING** - Indicates a potential problem
4. **ERROR** - Indicates a serious problem that needs attention
5. **CRITICAL** - Indicates a very serious error that may cause the application to stop

## Implementation Details

### 1. Centralized Exception Handling

The [ChatCartException.php](file:///c:/xampp/htdocs/wachat/core/shared/exceptions/ChatCartException.php) file implements the base exception class and specialized exceptions:

```php
// Example usage of ChatCartException
throw new ChatCartException(
    "Product not found", 
    404, 
    "ProductController", 
    ChatCartException::SEVERITY_WARNING, 
    ['product_id' => $productId]
);
```

### 2. Enhanced Logging

The [debug_helper.php](file:///c:/xampp/htdocs/wachat/core/shared/helpers/debug_helper.php) file provides enhanced logging functions:

```php
// Log with context and severity
logError("Database connection failed", "Database");
logWarning("Invalid input parameter", "Validation");
logInfo("User logged in", "Authentication");
logDebug("Processing request", "RequestHandler");
```

### 3. User-Friendly Error Pages

The application now provides user-friendly error pages that:
- Hide sensitive technical details
- Provide clear, actionable messages
- Maintain consistent branding
- Include navigation options

### 4. API Error Responses

API endpoints now return standardized JSON error responses:

```json
{
  "success": false,
  "timestamp": "2023-01-01T12:00:00Z",
  "error": {
    "code": 404,
    "message": "Product not found",
    "context": "ProductController"
  }
}
```

## Best Practices

### For Developers

1. **Always use try-catch blocks** for operations that can fail
2. **Log errors with appropriate context** for debugging
3. **Provide user-friendly error messages** to end users
4. **Never expose sensitive information** in error messages
5. **Use appropriate HTTP status codes** for API responses
6. **Include relevant debugging information** in logs but not in user-facing messages

### For Error Handling

1. **Validate all input parameters** before processing
2. **Handle database connection failures** gracefully
3. **Implement proper fallback mechanisms**
4. **Log all errors** for debugging purposes
5. **Monitor error logs** regularly
6. **Implement alerting** for critical errors

## Usage Examples

### Throwing Exceptions

```php
// Throwing a database exception
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    throw new DatabaseException("Failed to retrieve product: " . $e->getMessage(), "Product Retrieval");
}

// Throwing a validation exception
if (empty($name)) {
    throw new ValidationException("Product name is required", ['name' => 'Product name is required']);
}
```

### Handling Exceptions

```php
// In a controller method
try {
    $product = $this->productModel->getById($id);
    if (!$product) {
        throw new NotFoundException("Product not found");
    }
    // Process product
} catch (ChatCartException $e) {
    // Log the error
    error_log("Application error: " . $e->getMessage());
    
    // Return appropriate response
    if ($this->isApiRequest()) {
        sendErrorResponse($e->getMessage(), $e->getCode());
    } else {
        $this->errorController->showError($e->getUserMessage());
    }
} catch (Exception $e) {
    // Handle unexpected errors
    error_log("Unexpected error: " . $e->getMessage());
    sendErrorResponse("Internal server error", 500);
}
```

### Logging with Context

```php
// Logging with context information
try {
    $result = $this->productModel->create($productData);
    logInfo("Product created successfully", "ProductController", ['product_id' => $result['id']]);
} catch (ChatCartException $e) {
    logError("Failed to create product", "ProductController", [
        'error' => $e->getMessage(),
        'product_data' => $productData
    ]);
    throw $e;
}
```

## Monitoring and Maintenance

### Log Rotation

Implement log rotation to prevent log files from growing too large:
- Daily rotation for high-volume logs
- Weekly rotation for medium-volume logs
- Monthly rotation for low-volume logs
- Archive old logs for historical analysis

### Error Analysis

Regular error analysis should include:
- Identifying recurring errors
- Tracking error frequency and patterns
- Monitoring response times during errors
- Correlating errors with system events

## Future Enhancements

1. **Integration with external monitoring services** (e.g., Sentry, New Relic)
2. **Real-time alerting** for critical errors
3. **Automated error reporting** to development teams
4. **Performance impact analysis** of error handling
5. **Enhanced debugging tools** for development environments
6. **Error rate tracking** and reporting dashboards

## Conclusion

The enhanced error handling and logging system provides:
- Better user experience through clear error messages
- Improved debugging capabilities through detailed logging
- Consistent error handling across the application
- Enhanced security through proper information hiding
- Better monitoring and alerting capabilities

This system should be maintained and extended as the application grows to ensure continued reliability and maintainability.