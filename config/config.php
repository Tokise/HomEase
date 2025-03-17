<?php
/**
 * HomEase - Configuration File
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'homeasedb');
define('DB_USER', 'root');
define('DB_PASS', '');

// Google API Configuration
define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');
define('GOOGLE_REDIRECT_URI', 'http://localhost/HomEase/public/auth/google-callback');

// Google Maps API Configuration
define('GOOGLE_MAPS_API_KEY', 'YOUR_GOOGLE_MAPS_API_KEY');

// Application Settings
define('APP_NAME', 'HomEase');
define('APP_URL', 'http://localhost/HomEase');
define('DEBUG_MODE', true);

// Session Configuration
define('SESSION_LIFETIME', 86400); // 24 hours in seconds

// Email Configuration (if needed)
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'no-reply@homeease.com');
define('MAIL_PASSWORD', 'YOUR_EMAIL_PASSWORD');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'no-reply@homeease.com');
define('MAIL_FROM_NAME', 'HomEase Support');

// User Roles
define('ROLE_ADMIN', 1);
define('ROLE_MANAGER', 2);
define('ROLE_CLIENT', 3);

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