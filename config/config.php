<?php
/**
 * HomeSwift - Configuration File
 */

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file is missing. Please copy .env.example to .env and configure your environment.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse line
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            if (strpos($value, '"') === 0 || strpos($value, "'") === 0) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

// Load .env file
loadEnv(dirname(__DIR__) . '/.env');

// Helper function to get environment variables with fallback
function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}

// Application Constants
define('APP_NAME', env('APP_NAME', 'HomeSwift'));
define('APP_URL', env('APP_URL', 'http://localhost/HomeSwift'));
define('APP_ENV', env('APP_ENV', 'development'));
define('DEBUG_MODE', env('APP_DEBUG', 'true') === 'true');

// Role Constants - Main definition point
define('ROLE_ADMIN', 1);
define('ROLE_PROVIDER', 2); // Changed from ROLE_SERVICE_PROVIDER
define('ROLE_CLIENT', 3); // Will be used instead of ROLE_CUSTOMER

// Google Configuration
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI'));
define('GOOGLE_MAPS_API_KEY', env('GOOGLE_API_KEY'));

// Database Configuration
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'homeswift'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// Session Configuration
define('SESSION_LIFETIME', 86400); // 24 hours in seconds

// Email Configuration
define('MAIL_HOST', env('MAIL_HOST', 'smtp.example.com'));
define('MAIL_PORT', env('MAIL_PORT', 587));
define('MAIL_USERNAME', env('MAIL_USERNAME'));
define('MAIL_PASSWORD', env('MAIL_PASSWORD'));
define('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls'));
define('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS'));
define('MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'HomeSwift Support'));

// Error Handling
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Time Zone
date_default_timezone_set('UTC');