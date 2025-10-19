# Standardized Data Structure for ChatCart Web

This document defines the standardized data structure used across all data sources (database and JSON) in the ChatCart Web application.

## Product Data Structure

| Field Name | Type | Description | Database Column | JSON Field |
|------------|------|-------------|-----------------|------------|
| id | integer | Unique product identifier | id | id |
| name | string | Product name | name | name |
| description | string | Product description | description | description |
| price | float | Product price in IDR | price | price |
| category | string | Category name | category_name (from JOIN) | category |
| category_id | integer/null | Category ID (database only) | category_id | N/A |
| image | string | Image path/URL | images | image |
| inStock | boolean | Stock availability | is_active | inStock |
| stockQuantity | integer/null | Stock quantity (null = unlimited) | stock | stockQuantity |
| whatsappNumber | string | WhatsApp contact number | N/A | whatsappNumber |

## Field Mapping Details

### Database to Standardized Structure
- `id` → `id`
- `name` → `name`
- `description` → `description`
- `price` → `price`
- `category_id` + JOIN with categories table → `category` (category name)
- `category_id` → `category_id`
- `images` → `image`
- `is_active` → `inStock`
- `stock` → `stockQuantity`
- Custom field → `whatsappNumber`

### JSON to Standardized Structure
- `id` → `id`
- `name` → `name`
- `description` → `description`
- `price` → `price`
- `category` → `category`
- N/A → `category_id`
- `image` → `image`
- `inStock` → `inStock`
- `stockQuantity` → `stockQuantity`
- `whatsappNumber` → `whatsappNumber`

## Data Type Consistency Rules

1. All numeric fields should be explicitly cast to their appropriate types:
   - IDs: integer
   - Prices: float
   - Stock quantities: integer or null

2. Boolean fields should be explicitly converted:
   - inStock: boolean

3. String fields should be validated and sanitized:
   - All string fields should be trimmed
   - Special characters should be handled appropriately

4. Image paths should follow a consistent format:
   - All paths should be relative to the application root
   - All paths should start with "assets/"

## Implementation Guidelines

1. The `normalizeProduct()` method in the Product model is responsible for ensuring data consistency
2. All database queries should explicitly cast fields to ensure type consistency
3. JSON data should be validated and normalized before use
4. When adding new fields, ensure they are added to both data sources or handled appropriately in the normalization process