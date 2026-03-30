-- database/schema.sql
-- Run to create the required tables:
-- /'mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS switchstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE switchstore;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL COMMENT 'bcrypt hash - NEVER store plaintext',
    role       ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    product_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(150) NOT NULL,
    manufacturer        VARCHAR(100) NOT NULL,
    switch_type         ENUM('linear', 'tactile', 'clicky') NOT NULL,
    actuation_force     DECIMAL(5,1) NULL COMMENT 'Actuation force in grams (gf)',
    bottom_out_force    DECIMAL(5,1) NULL COMMENT 'Bottom-out force in grams (gf)',
    travel_distance     DECIMAL(4,1) NULL COMMENT 'Total travel distance in mm',
    pre_travel_distance DECIMAL(4,1) NULL COMMENT 'Pre-travel (actuation point) in mm',
    sound_profile       ENUM('silent', 'quiet', 'medium', 'loud') NULL,
    compatibility       VARCHAR(255) NULL COMMENT 'PCB/plate compatibility notes',
    description         TEXT         NULL,
    price               DECIMAL(8,2) NOT NULL,
    stock_quantity      INT UNSIGNED NOT NULL DEFAULT 0,
    product_image       VARCHAR(255) NULL DEFAULT 'placeholder.webp',
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_switch_type (switch_type),
    INDEX idx_price       (price)
) ENGINE=InnoDB;

-- Cart items table
CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED NOT NULL,
    product_id   INT UNSIGNED NOT NULL,
    quantity     INT UNSIGNED NOT NULL DEFAULT 1,
    added_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart_item (user_id, product_id),
    FOREIGN KEY (user_id)    REFERENCES users(user_id)       ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    order_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status       ENUM('pending','processing','shipped','delivered','cancelled')
                 NOT NULL DEFAULT 'pending',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    INDEX idx_user_id (user_id),
    INDEX idx_status  (status)
) ENGINE=InnoDB;

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id      INT UNSIGNED  NOT NULL,
    product_id    INT UNSIGNED  NOT NULL,
    product_name  VARCHAR(150)  NOT NULL COMMENT 'Snapshot of name at time of purchase',
    unit_price    DECIMAL(8,2)  NOT NULL COMMENT 'Snapshot of price at time of purchase',
    quantity      INT UNSIGNED  NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(order_id)     ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB;