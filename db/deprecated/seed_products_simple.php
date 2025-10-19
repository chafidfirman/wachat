<?php
// Script to seed the database with sample products

// Include the database configuration
require_once __DIR__ . '/core/shared/config/database.php';

if (!$pdo) {
    die("Database connection failed. Please check your database configuration.\n");
}

try {
    // First, delete existing products to avoid duplicates
    $pdo->exec("DELETE FROM products");
    $pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");
    
    // Insert sample products
    $stmt = $pdo->prepare("INSERT INTO products (name, slug, price, stock, description, images, category_id, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $products = [
        [
            'name' => 'Natural Soap Set',
            'slug' => 'natural-soap-set',
            'price' => 65000,
            'stock' => 12,
            'description' => 'Set of 3 natural handmade soaps with essential oils.',
            'images' => '{"image": "images/product1.jpg"}',
            'category_id' => 3, // Beauty
            'is_active' => 1
        ],
        [
            'name' => 'Wooden Cutting Board',
            'slug' => 'wooden-cutting-board',
            'price' => 150000,
            'stock' => 5,
            'description' => 'Durable wooden cutting board made from sustainable bamboo.',
            'images' => '{"image": "images/product4.jpg"}',
            'category_id' => 2, // Home & Kitchen
            'is_active' => 1
        ],
        [
            'name' => 'Handwoven Scarf',
            'slug' => 'handwoven-scarf',
            'price' => 95000,
            'stock' => 8,
            'description' => 'Beautiful handwoven scarf in traditional patterns.',
            'images' => '{"image": "images/product5.jpg"}',
            'category_id' => 1, // Clothing
            'is_active' => 1
        ],
        [
            'name' => 'Organic Tea Collection',
            'slug' => 'organic-tea-collection',
            'price' => 110000,
            'stock' => 15,
            'description' => 'Selection of 5 organic teas from local farms.',
            'images' => '{"image": "images/product6.jpg"}',
            'category_id' => 4, // Food & Beverage
            'is_active' => 1
        ],
        [
            'name' => 'Silk Scarf',
            'slug' => 'silk-scarf',
            'price' => 120000,
            'stock' => 6,
            'description' => 'Luxurious silk scarf with elegant design.',
            'images' => '{"image": "images/product7.jpg"}',
            'category_id' => 1, // Clothing (same as Handwoven Scarf)
            'is_active' => 1
        ],
        [
            'name' => 'Cotton Scarf',
            'slug' => 'cotton-scarf',
            'price' => 75000,
            'stock' => 10,
            'description' => 'Comfortable cotton scarf for everyday wear.',
            'images' => '{"image": "images/product8.jpg"}',
            'category_id' => 1, // Clothing (same as Handwoven Scarf)
            'is_active' => 1
        ]
    ];
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['slug'],
            $product['price'],
            $product['stock'],
            $product['description'],
            $product['images'],
            $product['category_id'],
            $product['is_active']
        ]);
    }
    
    echo "Sample products seeded successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>