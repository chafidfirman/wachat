<?php
/**
 * Asset Helper Functions
 * Provides standardized functions for loading assets (CSS, JS, images)
 */

/**
 * Generate URL for CSS asset
 * 
 * @param string $path Path to CSS file relative to assets/css/
 * @return string Full URL to CSS file
 */
function asset_css($path) {
    return base_url('assets/css/' . ltrim($path, '/'));
}

/**
 * Generate URL for JS asset
 * 
 * @param string $path Path to JS file relative to assets/js/
 * @return string Full URL to JS file
 */
function asset_js($path) {
    return base_url('assets/js/' . ltrim($path, '/'));
}

/**
 * Generate URL for image asset
 * 
 * @param string $path Path to image file relative to assets/img/
 * @return string Full URL to image file
 */
function asset_img($path) {
    return base_url('assets/img/' . ltrim($path, '/'));
}

/**
 * Generate a complete CSS link tag
 * 
 * @param string $path Path to CSS file
 * @param array $attributes Additional attributes for the link tag
 * @return string Complete link tag
 */
function link_css($path, $attributes = []) {
    $defaultAttributes = [
        'rel' => 'stylesheet',
        'href' => asset_css($path)
    ];
    
    // Merge default and custom attributes
    $attrs = array_merge($defaultAttributes, $attributes);
    
    // Build attribute string
    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<link' . $attrString . '>';
}

/**
 * Generate a complete JS script tag
 * 
 * @param string $path Path to JS file
 * @param array $attributes Additional attributes for the script tag
 * @return string Complete script tag
 */
function script_js($path, $attributes = []) {
    $defaultAttributes = [
        'src' => asset_js($path)
    ];
    
    // Merge default and custom attributes
    $attrs = array_merge($defaultAttributes, $attributes);
    
    // Build attribute string
    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<script' . $attrString . '></script>';
}

/**
 * Generate an image tag with proper asset URL
 * 
 * @param string $path Path to image file
 * @param string $alt Alt text for image
 * @param array $attributes Additional attributes for the img tag
 * @return string Complete img tag
 */
function img_asset($path, $alt = '', $attributes = []) {
    $defaultAttributes = [
        'src' => asset_img($path),
        'alt' => $alt
    ];
    
    // Merge default and custom attributes
    $attrs = array_merge($defaultAttributes, $attributes);
    
    // Build attribute string
    $attrString = '';
    foreach ($attrs as $key => $value) {
        $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<img' . $attrString . '>';
}
?>