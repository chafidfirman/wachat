# Debugging and Error Monitoring System

This document explains how to use the debugging and error monitoring system implemented in the ChatCart Web application.

## Features

1. **Full Error Reporting**: Enables detailed error reporting in development mode
2. **Automatic Error Logging**: Logs all errors to `logs/error.log`
3. **Navigation Error Tracking**: Logs broken links and navigation errors to `logs/navigation.log`
4. **Query Logging**: Logs database queries to `logs/query.log`
5. **Form Submission Logging**: Logs form submissions to `logs/form.log`
6. **Delete Operation Logging**: Logs delete operations to `logs/delete.log`
7. **Admin Access Logging**: Logs admin page access to `logs/admin.log`
8. **Debug Overlay**: Displays a developer overlay with recent logs when `?debug=true` is added to URLs
9. **Bug Checklist**: Generates a JSON bug checklist for systematic bug tracking

## Enabling Debug Mode

Debug mode is enabled by default in development. To control this:

1. Open `config.php`
2. Set `DEBUG_MODE` to `true` or `false`:
   ```php
   define('DEBUG_MODE', true);  // Enable debug mode
   // or
   define('DEBUG_MODE', false); // Disable debug mode
   ```

## Using the Debug Overlay

To view the debug overlay:

1. Add `?debug=true` to any URL
2. The overlay will appear in the bottom right corner
3. Click the "X" button to close the overlay

## Log Files

All logs are stored in the `logs/` directory:

- `error.log`: All PHP errors and exceptions
- `navigation.log`: Broken links and navigation errors
- `query.log`: Database queries
- `form.log`: Form submissions
- `delete.log`: Delete operations
- `admin.log`: Admin page access
- `bug_checklist.json`: Bug tracking checklist

## Testing the Debugging System

Run the test script at `test_debug.php` to verify the debugging system is working correctly.

## Bug Checklist

The bug checklist is automatically generated and saved to `logs/bug_checklist.json`. It includes:

- UI Responsiveness issues
- Button functionality problems
- Input validation errors
- Page availability issues (404 errors)
- Database operation problems
- API response issues

Each item has a status (Open, Fixed, Recheck) for systematic bug tracking.

## Custom Error Handling

The system includes custom handlers for:

- PHP errors (`customErrorHandler`)
- Exceptions (`customExceptionHandler`)
- Fatal errors (`shutdownHandler`)

These handlers automatically log errors and display them when debug mode is enabled.

## Adding Custom Logging

To add custom logging in your code:

```php
// Log an error
logError("Custom error message");

// Log a navigation error
logNavigationError("Menu Name", "Destination URL");

// Log a query
logQuery("SELECT * FROM table WHERE id = ?", [$id]);

// Log a form submission
logFormSubmission("Form Name", $_POST);

// Log a delete operation
logDeleteOperation("Table Name", $id);

// Log admin access
logAdminAccess("page.php", $username);
```

## Disabling Debug Mode for Production

Before deploying to production:

1. Set `DEBUG_MODE` to `false` in `config.php`
2. This will:
   - Disable error display
   - Continue logging errors to files
   - Disable the debug overlay