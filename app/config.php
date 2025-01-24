<?php
session_start();

function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception(".env file not found at $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split into key and value
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if they exist
        $value = trim($value, "\"'");

        // Set values to environment variables
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}


require_once __DIR__ . '/../database/db.php';

// Define base URL
define('BASE_URL', getenv('BASE_URL') ?:  'http://localhost/event-management/');


// Utility function to redirect
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit;
}
