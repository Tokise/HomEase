<?php
/**
 * Google OAuth Callback Handler
 */

// Define application paths
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Start a session if none exists
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enable detailed error reporting for debugging
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Log the request details
    error_log("Google callback request received at: " . $_SERVER['REQUEST_URI']);
    error_log("GET params: " . json_encode($_GET));
}

try {
    // Require the AuthController
    require_once SRC_PATH . '/controllers/AuthController.php';
    
    // Process the callback
    $authController = new AuthController();
    $authController->googleCallback();
} catch (Exception $e) {
    // Log the error
    error_log("Error in Google callback: " . $e->getMessage());
    error_log($e->getTraceAsString());
    
    // Redirect to login with error message
    $_SESSION['flash_message'] = 'Authentication error: ' . $e->getMessage();
    $_SESSION['flash_type'] = 'danger';
    
    // Redirect to login page
    header('Location: ' . APP_URL . '/auth/login');
    exit;
}

// End of file 