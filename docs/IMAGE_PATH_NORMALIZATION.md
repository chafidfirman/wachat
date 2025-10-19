# Image Path Normalization in ChatCart Web

This document explains the standardized approach to handling image paths in the ChatCart Web application.

## Problem

Previously, image paths were handled inconsistently across different parts of the application:

1. Some components had their own image path normalization logic
2. Different approaches were used in different files
3. No centralized validation or error handling
4. Inconsistent default image handling

## Solution

We've implemented a centralized image handling system using the `image_helper.php` file that provides consistent functions for all image path operations.

## Functions

### normalizeImagePath($imagePath, $defaultImage)

Normalizes an image path to ensure consistency across the application.

```php
$imagePath = normalizeImagePath($product['image']);
```

Features:
- Handles empty paths by returning a default image
- Processes JSON-encoded image data
- Ensures all paths start with "assets/"
- Maintains consistency between database and JSON data sources

### getImageUrl($imagePath, $defaultImage)

Returns a full URL for an image path by combining it with the base URL.

```php
$imageUrl = getImageUrl($product['image']);
```

### isValidImagePath($imagePath)

Validates if an image path has a valid extension.

```php
if (isValidImagePath($imagePath)) {
    // Process the image
}
```

## Implementation

All components and API endpoints now use the centralized image helper functions:

1. Product Card Components
2. Best Sellers Components
3. Limited Products Components
4. Related Product Components
5. API Endpoints

## Benefits

1. **Consistency**: All image paths are handled the same way
2. **Maintainability**: Changes only need to be made in one place
3. **Reliability**: Centralized error handling and validation
4. **Performance**: Reduced code duplication
5. **Scalability**: Easy to extend with new features

## Usage Examples

### In Components
```php
// Include the helper
require_once __DIR__ . '/../../helpers/image_helper.php';

// Get normalized image URL
$imageUrl = getImageUrl($product['image']);
```

### In API Endpoints
```php
// Include the helper
require_once __DIR__ . '/../core/shared/helpers/image_helper.php';

// Normalize image path
$image = normalizeImagePath(trim($input['image']));
```

## Future Improvements

1. Add image size validation
2. Implement image caching mechanisms
3. Add support for remote image URLs
4. Add image optimization features