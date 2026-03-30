-- database/seed.sql

USE switchstore;

-- Sample users with real bcrypt hashes
-- Login: admin@keyforge.example / admin1234
-- Login: user@keyforge.example  / user1234
INSERT INTO users (username, email, password, role) VALUES
    ('admin',    'admin@keyforge.example', '$2y$12$BpS3I3viYhFl/cghr4sexOSDCLJGKWYXesNUiTzA2upCipl1ffonq',    'admin'),
    ('testuser', 'user@keyforge.example',  '$2y$12$HBcjkNxHHX4UZ68KaLAGs.hmwyYtNjVAC1qwPuf4M1Kd2KHYPp9IW', 'customer')
ON DUPLICATE KEY UPDATE username = username;

-- Sample products with existing images
INSERT INTO products
    (name, manufacturer, switch_type, actuation_force, bottom_out_force,
     travel_distance, pre_travel_distance, sound_profile, compatibility,
     description, price, stock_quantity, product_image)
VALUES
    ('MX Red', 'Cherry', 'linear', 45.0, 60.0, 4.0, 2.0, 'quiet',
     'MX-compatible PCBs',
     'The classic smooth linear switch. Ideal for gaming and fast typing with no tactile bump.',
     9.90, 150, 'switch1.png'),

    ('MX Brown', 'Cherry', 'tactile', 55.0, 60.0, 4.0, 2.0, 'quiet',
     'MX-compatible PCBs',
     'Gentle tactile bump with no audible click. A versatile all-rounder for typing and gaming.',
     9.90, 120, 'switch2.png'),

    ('MX Blue', 'Cherry', 'clicky', 60.0, 60.0, 4.0, 2.2, 'loud',
     'MX-compatible PCBs',
     'Satisfying audible click and tactile bump. The iconic typist switch.',
     9.90, 100, 'switch3.png'),

    ('Speed Silver', 'Cherry', 'linear', 45.0, 60.0, 3.4, 1.2, 'quiet',
     'MX-compatible PCBs',
     'Ultra-short pre-travel for fast actuation. Built for competitive gaming.',
     12.90, 80, 'switch4.png'),

    ('Gateron Yellow', 'Gateron', 'linear', 35.0, 50.0, 4.0, 2.0, 'silent',
     'MX-compatible PCBs',
     'Buttery smooth linear with a light spring. Popular for silent builds.',
     6.50, 200, 'switch5.png'),

    ('Topre 45g', 'Topre', 'tactile', 45.0, NULL, 4.0, NULL, 'medium',
     'Topre boards only',
     'Electro-capacitive rubber dome with a unique thocky tactile feel.',
     35.00, 40, 'switch6.png')
ON DUPLICATE KEY UPDATE name = name;