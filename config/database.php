<?php
/*
 * config/database.php
 *
 * TODO: Set up the database connection here.
 *
 * Steps:
 *   1. Copy .env.example to .env and fill in your database credentials.
 *   2. Create a PDO connection using the credentials from .env.
 *   3. Set $pdo to the connection object, or null if the connection fails.
 *
 * Example connection (uncomment and adapt once your DB is ready):
 *
 *   $host = $_ENV['DB_HOST'] ?? 'localhost';
 *   $name = $_ENV['DB_NAME'] ?? 'switchstore';
 *   $user = $_ENV['DB_USER'] ?? 'root';
 *   $pass = $_ENV['DB_PASS'] ?? '';
 *
 *   try {
 *       $pdo = new PDO(
 *           "mysql:host=$host;dbname=$name;charset=utf8mb4",
 *           $user,
 *           $pass,
 *           [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
 *       );
 *   } catch (PDOException $e) {
 *       error_log('DB connection failed: ' . $e->getMessage());
 *       $pdo = null;
 *   }
 */

// TODO: Remove this placeholder once the connection is implemented.
$pdo = null;
