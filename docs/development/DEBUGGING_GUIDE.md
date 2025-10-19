# Comprehensive Debugging Guide

This document provides a detailed guide on using the debugging and error monitoring system in the ChatCart Web application, including advanced features and best practices.

## Table of Contents

1. [Overview](#overview)
2. [Debugging Features](#debugging-features)
3. [Enabling Debug Mode](#enabling-debug-mode)
4. [Using the Debug Overlay](#using-the-debug-overlay)
5. [Log Files and Monitoring](#log-files-and-monitoring)
6. [Custom Logging Functions](#custom-logging-functions)
7. [Bug Tracking System](#bug-tracking-system)
8. [Error Handling](#error-handling)
9. [Performance Monitoring](#performance-monitoring)
10. [Best Practices](#best-practices)

## Overview

The ChatCart Web debugging system provides comprehensive error reporting, logging, and monitoring capabilities to help developers identify and resolve issues quickly. The system is designed to be unobtrusive in production while providing detailed information during development.

## Debugging Features

The debugging system includes the following features:

1. **Full Error Reporting**: Detailed PHP error and exception reporting
2. **Automatic Error Logging**: All errors are automatically logged to files
3. **Navigation Error Tracking**: Broken links and navigation errors tracking
4. **Query Logging**: Database query execution logging
5. **Form Submission Logging**: Form submission tracking
6. **Delete Operation Logging**: Delete operation monitoring
7. **Admin Access Logging**: Administrative page access tracking
8. **Debug Overlay**: Real-time developer overlay with recent logs
9. **Bug Checklist**: Systematic bug tracking and management
10. **Performance Monitoring**: Query execution time tracking (future feature)

## Enabling Debug Mode

Debug mode is controlled through the `config.php` file:

```php
// In config.php
define('DEBUG_MODE', true);  // Enable for development
define('DEBUG_MODE', false); // Disable for production
```

When debug mode is enabled:
- Detailed error messages are displayed
- The debug overlay becomes available
- Additional logging information is captured

## Using the Debug Overlay

The debug overlay provides real-time visibility into application logs:

### Accessing the Overlay
1. Ensure debug mode is enabled in `config.php`
2. Add `?debug=true` to any URL
3. The overlay will appear in the bottom-right corner of the page

### Overlay Features
- Real-time error log display
- Navigation error tracking
- Database query monitoring
- Close button to hide the overlay

### Example Usage
```
http://localhost/wachat/?debug=true
http://localhost/wachat/product/123?debug=true
```

## Log Files and Monitoring

All logs are stored in the `logs/` directory with the following structure:

### Error Logs
- **File**: `logs/error.log`
- **Content**: PHP errors, warnings, and exceptions
- **Format**: `[TIMESTAMP] [ERROR_TYPE] Message in file on line number`

### Navigation Logs
- **File**: `logs/navigation.log`
- **Content**: Broken links and navigation errors
- **Format**: `[TIMESTAMP] NAVIGATION ERROR: Menu 'name' -> Destination 'url' (404 Not Found)`

### Query Logs
- **File**: `logs/query.log`
- **Content**: Database query execution
- **Format**: `[TIMESTAMP] QUERY EXECUTED: SQL query | Params: JSON parameters`

### Form Submission Logs
- **File**: `logs/form.log`
- **Content**: Form submission tracking
- **Format**: `[TIMESTAMP] FORM SUBMITTED: Form name | Data: JSON data`

### Delete Operation Logs
- **File**: `logs/delete.log`
- **Content**: Delete operation monitoring
- **Format**: `[TIMESTAMP] DELETE OPERATION: Operation name | ID: record ID`

### Admin Access Logs
- **File**: `logs/admin.log`
- **Content**: Administrative page access tracking
- **Format**: `[TIMESTAMP] ADMIN ACCESS: Page 'page.php' | User: username`

## Custom Logging Functions

The debugging system provides several custom logging functions for application-specific tracking:

### logError()
Log custom error messages:
```php
logError("Custom error message");
logError("Database connection failed: " . $exception->getMessage());
```

### logNavigationError()
Log navigation errors:
```php
logNavigationError("Main Menu", "/nonexistent-page.php");
logNavigationError("Product Category", "/category/electronics");
```

### logQuery()
Log database queries:
```php
logQuery("SELECT * FROM products WHERE id = ?", [$productId]);
logQuery("UPDATE users SET last_login = ? WHERE id = ?", [date('Y-m-d H:i:s'), $userId]);
```

### logFormSubmission()
Log form submissions:
```php
logFormSubmission("Contact Form", $_POST);
logFormSubmission("Product Search", ['query' => $searchTerm, 'category' => $category]);
```

### logDeleteOperation()
Log delete operations:
```php
logDeleteOperation("Delete Product", $productId);
logDeleteOperation("Remove User", $userId);
```

### logAdminAccess()
Log administrative access:
```php
logAdminAccess("admin/products.php", $_SESSION['username']);
logAdminAccess("admin/settings.php", "admin_user");
```

## Bug Tracking System

The bug tracking system provides a structured approach to identifying and resolving issues:

### Bug Checklist
The system automatically generates a bug checklist in `logs/bug_checklist.json` with the following categories:
1. UI Responsiveness
2. Button Functionality
3. Input Validation
4. Page Availability
5. Database Operations
6. API Responses

### Checklist Structure
```json
{
  "timestamp": "2025-10-17 14:30:00",
  "bugs": [
    {
      "id": 1,
      "category": "UI Responsiveness",
      "description": "Check if all UI elements respond to user interactions",
      "status": "Open",
      "priority": "High"
    }
  ]
}
```

### Managing Bug Status
Update bug status values:
- `Open`: Issue identified but not yet addressed
- `Fixed`: Issue has been resolved
- `Recheck`: Issue needs verification after fixes

## Error Handling

The debugging system implements custom error handlers for comprehensive error management:

### Custom Error Handler
Handles PHP errors and logs them appropriately:
```php
set_error_handler('customErrorHandler');
```

### Custom Exception Handler
Handles uncaught exceptions:
```php
set_exception_handler('customExceptionHandler');
```

### Shutdown Handler
Catches fatal errors that might not be caught by other handlers:
```php
register_shutdown_function('shutdownHandler');
```

## Performance Monitoring

While currently focused on error tracking, the system can be extended for performance monitoring:

### Future Features
1. Query execution time tracking
2. Page load time monitoring
3. Memory usage logging
4. API response time tracking

### Implementation Example
```php
// Future implementation for query timing
$start = microtime(true);
// Execute query
$end = microtime(true);
$executionTime = ($end - $start) * 1000; // milliseconds
logQueryPerformance($query, $executionTime);
```

## Best Practices

### During Development
1. Always enable debug mode
2. Use the debug overlay for real-time monitoring
3. Regularly check log files for issues
4. Update bug checklist status as issues are resolved

### In Production
1. Disable debug mode to prevent information disclosure
2. Ensure log files are rotated to prevent excessive disk usage
3. Monitor logs for security issues
4. Keep error logging enabled for issue tracking

### Code Instrumentation
1. Add logging at critical application points
2. Use descriptive log messages
3. Include relevant context information
4. Log both successful operations and failures

### Security Considerations
1. Never expose debug information in production
2. Sanitize sensitive data in logs
3. Protect log files from direct web access
4. Regularly review and clean log files

## Troubleshooting

### Common Issues
1. **Debug overlay not appearing**: Check that debug mode is enabled and `?debug=true` is in the URL
2. **Logs not being written**: Check file permissions on the `logs/` directory
3. **Missing error details**: Ensure error reporting is properly configured in `config.php`

### Log File Management
1. Regularly archive old log files
2. Monitor disk space usage
3. Implement log rotation for high-traffic applications
4. Use external log management tools for production environments

## Conclusion

The debugging system provides a comprehensive toolkit for monitoring, tracking, and resolving issues in the ChatCart Web application. By following the guidelines in this document, developers can effectively use these features to maintain application quality and performance.