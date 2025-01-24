<?php
session_start();

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception(".env file not found at $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments and empty lines
        if (empty(trim($line)) || strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split into key and value, ensuring '=' exists
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        list($key, $value) = $parts;
        $key = trim($key);
        $value = trim($value, "\"'");  // Remove any quotes

        // Set environment variables
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Load environment variables from .env file
try {
    loadEnv(__DIR__ . '/../database/.env');
} catch (Exception $e) {
    die($e->getMessage());
}

// Require database connection
require_once __DIR__ . '/../database/db.php';

// Define BASE_URL with a fallback value
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/event-management-system/');

// Utility function to redirect users to a URL
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit;
}
