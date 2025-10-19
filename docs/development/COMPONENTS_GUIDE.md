# ChatCart Web Components Guide

This document provides comprehensive documentation for the reusable components in the ChatCart Web application, including usage instructions, customization options, and best practices.

## Table of Contents

1. [Overview](#overview)
2. [Component Structure](#component-structure)
3. [Core Components](#core-components)
   - [Header and Navigation](#header-and-navigation)
   - [Footer](#footer)
   - [Product Cards](#product-cards)
   - [Hero Section](#hero-section)
   - [Best Sellers](#best-sellers)
   - [Limited Products](#limited-products)
4. [Layout Components](#layout-components)
5. [Helper Components](#helper-components)
6. [Creating Custom Components](#creating-custom-components)
7. [Component Integration](#component-integration)
8. [Best Practices](#best-practices)

## Overview

The ChatCart Web application uses a component-based architecture to promote code reusability, maintainability, and consistency. Components are located in the `core/shared/components/` directory and can be easily integrated into any page.

## Component Structure

Components are organized in the following directory structure:
```
core/shared/
├── components/
│   ├── best-sellers.php
│   ├── footer.php
│   ├── header.php
│   ├── hero.php
│   ├── limited-products.php
│   ├── navbar.php
│   ├── product-card.php
│   ├── related-product-card.php
│   └── unified-components/
│       ├── unified-footer.php
│       ├── unified-header.php
│       └── unified-product-card.php
├── layouts/
│   ├── main.php
│   └── unified.php
└── helpers/
    └── Various helper functions
```

## Core Components

### Header and Navigation

#### navbar.php
The main navigation component that includes the site logo and menu items.

**Usage:**
```php
<?php include __DIR__ . '/core/shared/components/navbar.php'; ?>
```

**Features:**
- Responsive Bootstrap navigation
- Active link detection
- Mobile-friendly hamburger menu
- Consistent styling across pages

#### header.php
Wrapper component that includes the navbar with proper container structure.

**Usage:**
```php
<?php include __DIR__ . '/core/shared/components/header.php'; ?>
```

**Features:**
- Shadow effect for visual separation
- Container wrapper for content alignment
- Includes navbar component automatically

### Footer

#### footer.php
The standard footer component with contact information and copyright.

**Usage:**
```php
<?php include __DIR__ . '/core/shared/components/footer.php'; ?>
```

**Features:**
- Dark background for visual contrast
- Contact information display
- Copyright with dynamic year
- Responsive column layout

#### unified-footer.php
Enhanced footer component that can be used in both static and dynamic contexts.

**Usage:**
```php
<?php include __DIR__ . '/core/shared/components/unified-footer.php'; ?>
```

### Product Cards

#### product-card.php
Standard product card component for displaying product information.

**Usage:**
```php
<?php 
$product = [
    'id' => 1,
    'name' => 'Product Name',
    'description' => 'Product description',
    'price' => 99000,
    'image' => 'assets/img/product1.jpg',
    'stock' => 10
];
include __DIR__ . '/core/shared/components/product-card.php';
?>
```

**Features:**
- Image display with fallback
- Price formatting
- Stock status indicators
- Action buttons (Detail, Order)
- Responsive design

#### related-product-card.php
Simplified product card for related products section.

**Usage:**
```php
<?php 
$relatedProduct = [
    'id' => 1,
    'name' => 'Related Product',
    'price' => 79000,
    'image' => 'assets/img/related1.jpg'
];
include __DIR__ . '/core/shared/components/related-product-card.php';
?>
```

#### unified-product-card.php
Universal product card component that works in both PHP and JavaScript contexts.

**Usage in PHP:**
```php
<?php 
include __DIR__ . '/core/shared/components/unified-product-card.php';
renderProductCard($product, 'best-seller');
?>
```

**Functions:**
- `renderProductCard($product, $badgeType)` - Render a product card
- `formatPrice($price)` - Format price in IDR

### Hero Section

#### hero.php
Full-width hero section for the homepage.

**Usage:**
```php
<?php include __DIR__ . '/core/shared/components/hero.php'; ?>
```

**Features:**
- Large call-to-action button
- Responsive text sizing
- Background styling
- Centered content layout

### Best Sellers

#### best-sellers.php
Component for displaying best-selling products.

**Usage:**
```php
<?php 
$bestSellers = getBestSellers(); // Your function to fetch best sellers
include __DIR__ . '/core/shared/components/best-sellers.php';
?>
```

**Features:**
- Title section
- Product grid layout
- Integration with product-card component

### Limited Products

#### limited-products.php
Component for displaying limited stock products.

**Usage:**
```php
<?php 
$limitedProducts = getLimitedProducts(); // Your function to fetch limited products
include __DIR__ . '/core/shared/components/limited-products.php';
?>
```

**Features:**
- Title section
- Product grid layout
- Integration with product-card component
- Limited stock indicators

## Layout Components

### main.php
Standard layout template that includes header, content, and footer.

**Usage:**
```php
<?php
$title = 'Page Title';
ob_start();
?>
<!-- Page content here -->
<h1>Welcome to our site</h1>
<?php
$content = ob_get_clean();
include __DIR__ . '/core/shared/layouts/main.php';
?>
```

### unified.php
Universal layout that works with both static and dynamic pages.

**Usage:**
```php
<?php
$title = 'Page Title';
ob_start();
?>
<!-- Page content here -->
<h1>Welcome to our site</h1>
<?php
$content = ob_get_clean();
include __DIR__ . '/core/shared/layouts/unified.php';
?>
```

## Helper Components

Helper functions are located in `core/shared/helpers/` and provide utility functions for components:

### Asset Helpers (asset_helper.php)
Functions for managing assets:
- `asset_css($path)` - Generate CSS asset URL
- `asset_js($path)` - Generate JavaScript asset URL
- `asset_img($path)` - Generate image asset URL

### Template Helpers (template_helper.php)
Functions for template management:
- `render_view($view, $data)` - Render a view with data
- `render_component($component, $data)` - Render a component with data
- `extend_layout($layout)` - Extend a layout template

### View Helpers (view_helpers.php)
Functions for view rendering:
- `format_price($amount)` - Format price in IDR
- `base_url($path)` - Generate base URL
- `site_url($path)` - Generate site URL

## Creating Custom Components

### Component Structure
Create a new component file in `core/shared/components/`:

```php
<!-- core/shared/components/custom-component.php -->
<div class="custom-component">
    <h3><?= htmlspecialchars($title ?? 'Default Title') ?></h3>
    <p><?= htmlspecialchars($content ?? 'Default content') ?></p>
</div>
```

### Component Parameters
Pass data to components using variables:

```php
<?php
$title = 'My Component Title';
$content = 'This is the component content';
include __DIR__ . '/core/shared/components/custom-component.php';
?>
```

### Component Styling
Add component-specific CSS in `assets/css/components.css`:

```css
.custom-component {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
```

## Component Integration

### In Static HTML Pages
For static HTML pages, components can be included using server-side includes or converted to JavaScript components.

### In Dynamic PHP Pages
For dynamic PHP pages, use the standard include mechanism:

```php
<?php
// Include necessary components
include __DIR__ . '/core/shared/components/header.php';
include __DIR__ . '/core/shared/components/navbar.php';

// Page content
echo '<div class="container">';
echo '<h1>Page Content</h1>';
echo '</div>';

// Include footer
include __DIR__ . '/core/shared/components/footer.php';
?>
```

### In Layout Templates
Use components within layout templates:

```php
<!-- core/shared/layouts/main.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'ChatCart Web' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>
    
    <main>
        <?= $content ?? '' ?>
    </main>
    
    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>
```

## Best Practices

### Component Design
1. **Keep components small and focused** - Each component should have a single responsibility
2. **Use semantic HTML** - Ensure proper HTML structure for accessibility
3. **Provide fallbacks** - Handle missing data gracefully
4. **Escape output** - Always use `htmlspecialchars()` for user data
5. **Maintain consistency** - Follow established styling and naming conventions

### Component Reusability
1. **Parameterize content** - Make components configurable through parameters
2. **Avoid hardcoding** - Use variables for dynamic content
3. **Document usage** - Provide clear usage examples in comments
4. **Test components** - Verify components work in different contexts

### Performance Considerations
1. **Minimize includes** - Only include necessary components
2. **Optimize assets** - Ensure CSS and JavaScript are optimized
3. **Cache when possible** - Cache component output for static content
4. **Lazy load images** - Implement lazy loading for better performance

### Maintenance
1. **Version components** - Track changes to component interfaces
2. **Deprecate gracefully** - Provide migration paths for breaking changes
3. **Document changes** - Update documentation when components change
4. **Test thoroughly** - Ensure components work after updates

## Conclusion

The component-based architecture of ChatCart Web promotes code reuse, maintainability, and consistency. By following the guidelines in this document, developers can effectively create, use, and maintain components throughout the application.