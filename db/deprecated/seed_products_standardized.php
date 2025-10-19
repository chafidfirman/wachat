<?php
// Script to seed the database with sample products using standardized schema

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
    $stmt = $pdo->prepare("INSERT INTO products (name, slug, price, stock_quantity, description, image, category_id, is_active, whatsappNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $products = [
        [
            'name' => 'Natural Soap Set',
            'slug' => 'natural-soap-set',
            'price' => 65000,
            'stock_quantity' => 12,
            'description' => 'Set of 3 natural handmade soaps with essential oils.',
            'image' => 'images/product1.jpg',
            'category_id' => 3, // Beauty
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ],
        [
            'name' => 'Wooden Cutting Board',
            'slug' => 'wooden-cutting-board',
            'price' => 150000,
            'stock_quantity' => 5,
            'description' => 'Durable wooden cutting board made from sustainable bamboo.',
            'image' => 'images/product4.jpg',
            'category_id' => 2, // Home & Kitchen
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ],
        [
            'name' => 'Handwoven Scarf',
            'slug' => 'handwoven-scarf',
            'price' => 95000,
            'stock_quantity' => 8,
            'description' => 'Beautiful handwoven scarf in traditional patterns.',
            'image' => 'images/product5.jpg',
            'category_id' => 1, // Clothing
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ],
        [
            'name' => 'Organic Tea Collection',
            'slug' => 'organic-tea-collection',
            'price' => 110000,
            'stock_quantity' => 15,
            'description' => 'Selection of 5 organic teas from local farms.',
            'image' => 'images/product6.jpg',
            'category_id' => 4, // Food & Beverage
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ],
        [
            'name' => 'Silk Scarf',
            'slug' => 'silk-scarf',
            'price' => 120000,
            'stock_quantity' => 6,
            'description' => 'Luxurious silk scarf with elegant design.',
            'image' => 'images/product7.jpg',
            'category_id' => 1, // Clothing (same as Handwoven Scarf)
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ],
        [
            'name' => 'Cotton Scarf',
            'slug' => 'cotton-scarf',
            'price' => 75000,
            'stock_quantity' => 10,
            'description' => 'Comfortable cotton scarf for everyday wear.',
            'image' => 'images/product8.jpg',
            'category_id' => 1, // Clothing (same as Handwoven Scarf)
            'is_active' => 1,
            'whatsappNumber' => '6281234567890'
        ]
    ];
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['slug'],
            $product['price'],
            $product['stock_quantity'],
            $product['description'],
            $product['image'],
            $product['category_id'],
            $product['is_active'],
            $product['whatsappNumber']
        ]);
    }
    
    echo "Sample products seeded successfully with standardized schema!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>