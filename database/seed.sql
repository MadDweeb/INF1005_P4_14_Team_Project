-- database/seed.sql
--
-- Sample / seed data for development and testing.
-- Run AFTER schema.sql:
--   mysql -u root -p switchstore < database/seed.sql
--
-- IMPORTANT: Replace placeholder password hashes before use.
-- Generate real hashes in PHP:
--   echo password_hash('admin1234', PASSWORD_BCRYPT);

USE switchstore;

-- Sample users
-- TODO: Replace PLACEHOLDER_HASH values with real bcrypt hashes.
INSERT INTO users (username, email, password, role) VALUES
    ('admin',    'admin@keyforge.example', '$2y$12$REPLACE_WITH_REAL_HASH_ADMIN',    'admin'),
    ('testuser', 'user@keyforge.example',  '$2y$12$REPLACE_WITH_REAL_HASH_CUSTOMER', 'customer')
ON DUPLICATE KEY UPDATE username = username;

-- Sample products 
INSERT INTO products
    (name, manufacturer, switch_type, actuation_force, bottom_out_force,
     travel_distance, pre_travel_distance, sound_profile, compatibility,
     description, price, stock_quantity, product_image)
VALUES
    ('MX Red', 'Cherry', 'linear', 45.0, 60.0, 4.0, 2.0, 'quiet',
     'MX-compatible PCBs',
     'The classic smooth linear switch. Ideal for gaming and fast typing with no tactile bump.',
     9.90, 150, 'cherry-mx-red.webp'),

    ('MX Brown', 'Cherry', 'tactile', 55.0, 60.0, 4.0, 2.0, 'quiet',
     'MX-compatible PCBs',
     'Gentle tactile bump with no audible click. A versatile all-rounder for typing and gaming.',
     9.90, 120, 'cherry-mx-brown.webp'),

    ('MX Blue', 'Cherry', 'clicky', 60.0, 60.0, 4.0, 2.2, 'loud',
     'MX-compatible PCBs',
     'Satisfying audible click and tactile bump. The iconic typist switch.',
     9.90, 100, 'cherry-mx-blue.webp'),

    ('Speed Silver', 'Cherry', 'linear', 45.0, 60.0, 3.4, 1.2, 'quiet',
     'MX-compatible PCBs',
     'Ultra-short pre-travel for fast actuation. Built for competitive gaming.',
     12.90, 80, 'cherry-speed-silver.webp'),

    ('Gateron Yellow', 'Gateron', 'linear', 35.0, 50.0, 4.0, 2.0, 'silent',
     'MX-compatible PCBs',
     'Buttery smooth linear with a light spring. Popular for silent builds.',
     6.50, 200, 'gateron-yellow.webp'),

    ('Topre 45g', 'Topre', 'tactile', 45.0, NULL, 4.0, NULL, 'medium',
     'Topre boards only',
     'Electro-capacitive rubber dome with a unique thocky tactile feel.',
     35.00, 40, 'topre-45g.webp')
ON DUPLICATE KEY UPDATE name = name;