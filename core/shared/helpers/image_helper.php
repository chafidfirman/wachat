<?php
/**
 * Image Helper
 * Provides centralized functions for handling image paths consistently across the application
 */

/**
 * Normalize image path to ensure consistency
 * 
 * @param string $imagePath The image path to normalize
 * @param string $defaultImage The default image to use if path is empty
 * @return string Normalized image path
 */
function normalizeImagePath($imagePath, $defaultImage = 'assets/img/product-default.jpg') {
    // Handle empty paths
    if (empty($imagePath)) {
        return $defaultImage;
    }
    
    // Handle JSON data in image field
    if ($imagePath[0] === '{' || $imagePath[0] === '[') {
        $jsonData = json_decode($imagePath, true);
        if (is_array($jsonData)) {
            // Extract image path from JSON data
            if (isset($jsonData['image'])) {
                $imagePath = $jsonData['image'];
            } else if (isset($jsonData[0]) && is_array($jsonData[0]) && isset($jsonData[0]['image'])) {
                // Handle array of images, take the first one
                $imagePath = $jsonData[0]['image'];
            } else {
                // If we can't extract image path, use default
                return $defaultImage;
            }
        }
    }
    
    // If it's already a full path with assets/, return as is
    if (strpos($imagePath, 'assets/') === 0) {
        return $imagePath;
    }
    
    // If it's a relative path, prepend assets/
    if (strpos($imagePath, 'assets/') === false) {
        return 'assets/' . ltrim($imagePath, '/');
    }
    
    return $imagePath;
}

/**
 * Get full image URL with base URL
 * 
 * @param string $imagePath The image path to normalize and convert to full URL
 * @param string $defaultImage The default image to use if path is empty
 * @return string Full image URL
 */
function getImageUrl($imagePath, $defaultImage = 'assets/img/product-default.jpg') {
    $normalizedPath = normalizeImagePath($imagePath, $defaultImage);
    return base_url($normalizedPath);
}

/**
 * Validate image path
 * 
 * @param string $imagePath The image path to validate
 * @return bool True if the image path is valid
 */
function isValidImagePath($imagePath) {
    if (empty($imagePath)) {
        return false;
    }
    
    // Check if it's a valid file extension
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    
    return in_array($extension, $validExtensions);
}
?>