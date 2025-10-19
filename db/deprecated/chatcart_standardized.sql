-- Standardized MySQL database schema for ChatCart Web

-- Create database
CREATE DATABASE IF NOT EXISTS chatcart;
USE chatcart;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  password_hash varchar(255) NOT NULL,
  name varchar(100) DEFAULT NULL,
  phone varchar(20) DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  slug varchar(100) NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table with standardized field names
CREATE TABLE IF NOT EXISTS products (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  price decimal(10,2) NOT NULL,
  stock_quantity int(11) DEFAULT NULL,
  description text,
  image varchar(255) DEFAULT NULL,
  category_id int(11) DEFAULT NULL,
  is_active tinyint(1) DEFAULT 1,
  whatsappNumber varchar(20) DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY category_id (category_id),
  CONSTRAINT products_ibfk_1 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
  id int(11) NOT NULL AUTO_INCREMENT,
  customer_name varchar(100) NOT NULL,
  customer_phone varchar(20) NOT NULL,
  message_text text,
  total_amount decimal(10,2) DEFAULT NULL,
  status enum('pending','confirmed','shipped','cancelled') DEFAULT 'pending',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
  order_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  qty int(11) NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (order_id,product_id),
  KEY product_id (product_id),
  CONSTRAINT order_items_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
  CONSTRAINT order_items_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create wa_clicks table
CREATE TABLE IF NOT EXISTS wa_clicks (
  id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL,
  clicked_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY product_id (product_id),
  CONSTRAINT wa_clicks_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create log_activity table
CREATE TABLE IF NOT EXISTS log_activity (
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) DEFAULT NULL,
  action varchar(50) NOT NULL,
  description text,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY admin_id (admin_id),
  CONSTRAINT log_activity_ibfk_1 FOREIGN KEY (admin_id) REFERENCES admins (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO admins (username, password_hash, name, phone) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '6281234567890');

-- Insert default categories
INSERT INTO categories (name, slug) VALUES 
('Clothing', 'clothing'),
('Home & Kitchen', 'home-kitchen'),
('Beauty', 'beauty'),
('Food & Beverage', 'food-beverage');