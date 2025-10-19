# Database Structure

This directory contains all database-related files for the ChatCart Web application.

## Directory Structure

- `schema/` - Database schema files
- `seeds/` - Database seed files

## Files

### Schema Files

- `chatcart.sql` - Main database schema (unified)
- `schema/chatcart_full.sql` - Full database schema with sample data (deprecated)
- `schema/chatcart_simple.sql` - Simplified database schema (deprecated)
- `chatcart_standardized.sql` - Standardized database schema (deprecated)

### Seed Files

- `seed_products.php` - Main product seeding script (unified)
- `seeds/seed_products_full.php` - Full product seeding script with base64 images (deprecated)
- `seeds/seed_products_simple.php` - Simple product seeding script (deprecated)
- `seeds/seed_products_standardized.php` - Standardized product seeding script (deprecated)

## Usage

To set up the database:

1. Run `php db/setup.php` to create the database and import the schema
2. Run `php db/seeds/seed_products.php` to seed the database with sample products

## Deprecated Files

The following files are kept for reference but should not be used in new implementations:
- `deprecated/chatcart_full.sql`
- `deprecated/chatcart_simple.sql`
- `deprecated/chatcart_standardized.sql`
- `deprecated/seed_products_full.php`
- `deprecated/seed_products_simple.php`
- `deprecated/seed_products_standardized.php`