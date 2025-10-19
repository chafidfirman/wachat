# Related Products Functionality Standardization

This document explains the standardized approach to handling related products functionality in the ChatCart Web application.

## Problem

Previously, the related products functionality had inconsistent implementations between database and JSON data sources:

1. Database implementation expected category ID
2. JSON implementation expected category name
3. Different data structures returned by each implementation
4. Inconsistent error handling and fallback mechanisms

## Solution

We've standardized the related products functionality to work consistently across both data sources:

1. The `getRelated()` method now accepts either category ID (for database) or category name (for JSON)
2. Both implementations return data in the same normalized structure
3. Improved error handling and fallback mechanisms
4. Consistent limit parameter handling

## Implementation Details

### Method Signature
```php
public function getRelated($productId, $category, $limit = 4)
```

### Parameters
- `$productId`: The ID of the product to find related products for
- `$category`: Either category ID (for database) or category name (for JSON)
- `$limit`: Maximum number of related products to return (default: 4)

### Return Value
An array of related products in the standardized data structure:
```php
[
    [
        'id' => 1,
        'name' => 'Product Name',
        'description' => 'Product Description',
        'price' => 100000.00,
        'category' => 'Category Name',
        'category_name' => 'Category Name',
        'category_id' => 1,
        'image' => 'assets/img/product.jpg',
        'inStock' => true,
        'stockQuantity' => 10,
        'whatsappNumber' => '6281234567890'
    ],
    // ... more products
]
```

### Database Implementation
1. Accepts category ID or category name
2. If category name is provided, looks up the corresponding category ID
3. Queries products in the same category (excluding the current product)
4. Orders by creation date (newest first)
5. Limits results to the specified count

### JSON Implementation
1. Accepts category name
2. Filters products by category name (excluding the current product)
3. Returns up to the specified limit
4. Normalizes data structure to match database output

## Usage Examples

### In Controllers
```php
// For database implementation (when category ID is available)
$relatedProducts = $productModel->getRelated($productId, $categoryId);

// For JSON implementation (when category name is available)
$relatedProducts = $productModel->getRelated($productId, $categoryName);
```

### In Views
```php
<?php foreach ($relatedProducts as $related): ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
        <?php renderRelatedProductCard($related); ?>
    </div>
<?php endforeach; ?>
```

## Benefits

1. **Consistency**: Both implementations return data in the same structure
2. **Flexibility**: Accepts both category ID and category name
3. **Reliability**: Improved error handling and fallback mechanisms
4. **Maintainability**: Centralized logic in the Product model
5. **Performance**: Efficient querying with proper limits

## Testing

The related products functionality is tested in:
- `tests/test_related_products.php`
- `tests/comprehensive_test_suite.php`
- `tests/test_product_detail.php`

## Future Improvements

1. Add more sophisticated related products algorithms (based on tags, etc.)
2. Implement caching for related products queries
3. Add support for cross-category related products
4. Improve the matching algorithm to consider product similarity