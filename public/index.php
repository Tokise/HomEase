<?php
/**
 * HomEase - Home Services Platform
 * Main Entry Point
 */

// Define application path constants
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Load Composer's autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Load .env and configuration
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Start session
session_start();

try {
    // Debug information
    if (DEBUG_MODE) {
        error_log("Request URI: " . $_SERVER['REQUEST_URI']);
        error_log("Script Name: " . $_SERVER['SCRIPT_NAME']);
        error_log("PHP Self: " . $_SERVER['PHP_SELF']);
    }

    // Get the request URI
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Remove query string if present
    if (($pos = strpos($requestUri, '?')) !== false) {
        $requestUri = substr($requestUri, 0, $pos);
    }
    
    // Remove base path from request URI
    $basePath = '/HomEase';
    $requestUri = str_replace($basePath, '', $requestUri);
    $requestUri = str_replace('/public', '', $requestUri);
    $requestUri = str_replace('/index.php', '', $requestUri);
    
    // Clean up the request URI
    $path = trim($requestUri, '/');
    
    if (DEBUG_MODE) {
        error_log("Processed Path: " . $path);
    }
    
    // Handle empty path (root URL)
    if (empty($path)) {
        require_once SRC_PATH . '/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        exit;
    }
    
    // Split the path into segments
    $segments = explode('/', $path);
    
    // Get controller, action, and parameters
    $controller = !empty($segments[0]) ? strtolower($segments[0]) : 'home';
    $action = isset($segments[1]) ? strtolower($segments[1]) : 'index';
    $params = array_slice($segments, 2);

    // Debug logging
    if (DEBUG_MODE) {
        error_log("Route Debug - Controller: " . $controller);
        error_log("Route Debug - Action: " . $action);
        error_log("Route Debug - Params: " . implode(', ', $params));
    }

    // Load the base Controller class
    require_once SRC_PATH . '/controllers/Controller.php';

    // Construct controller class name and file path
    $controllerClass = ucfirst($controller) . 'Controller';
    $controllerFile = SRC_PATH . '/controllers/' . $controllerClass . '.php';

    if (DEBUG_MODE) {
        error_log("Loading controller file: " . $controllerFile);
    }

    // Check if controller file exists
    if (!file_exists($controllerFile)) {
        throw new Exception("Controller not found: " . $controllerClass);
    }

    // Load the controller file
    require_once $controllerFile;

    // Check if controller class exists
    if (!class_exists($controllerClass)) {
        throw new Exception("Controller class not found: " . $controllerClass);
    }

    // Create controller instance
    $controllerInstance = new $controllerClass();

    // Check if the action method exists
    if (!method_exists($controllerInstance, $action)) {
        throw new Exception("Action not found: " . $action . " in " . $controllerClass);
    }

    // Call the controller action with parameters
    call_user_func_array([$controllerInstance, $action], $params);

} catch (Exception $e) {
    // Log the error
    error_log("HomEase Error: " . $e->getMessage());
    
    if (DEBUG_MODE) {
        error_log("Stack trace: " . $e->getTraceAsString());
        // Display error in development
        echo "<h1>Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // Load and display error page
        require_once SRC_PATH . '/controllers/ErrorController.php';
        $errorController = new ErrorController();
        $errorController->serverError();
    }
} 