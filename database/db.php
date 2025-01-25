<?php
require_once __DIR__ . '/../app/config.php';

// Load environment variables
loadEnv(__DIR__ . '/../database/.env');

// Get environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'event_management';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
