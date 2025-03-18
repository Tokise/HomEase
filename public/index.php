<?php
/**
 * HomEase - Home Services Platform
 * Main Entry Point
 */

// Enable error reporting based on environment
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Add function declarations for linter
if (!function_exists('dirname')) {
    /**
     * @param string $path
     * @return string
     */
    function dirname($path) { return ''; }
    function str_replace($search, $replace, $subject) { return ''; }
    function explode($delimiter, $string) { return []; }
    function trim($string, $charlist = '') { return ''; }
    function array_slice($array, $offset, $length = null) { return []; }
    function ucfirst($string) { return ''; }
    function file_exists($filename) { return true; }
    function call_user_func_array($callback, $args) { return null; }
}

// Define application path constants
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Start session
session_start();

try {
    // Get the request URI and script name
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove base path from request URI
    $basePath = '/HomEase';
    $requestUri = str_replace($basePath, '', $requestUri);
    $requestUri = str_replace('/public', '', $requestUri);
    $requestUri = str_replace('/index.php', '', $requestUri);
    
    // Clean up the request URI
    $path = trim($requestUri, '/');
    
    // Handle empty path (root URL)
    if (empty($path)) {
        $controller = 'home';
        $action = 'index';
        $params = [];
    } else {
        // Split the path into segments
        $segments = explode('/', $path);
        
        // Get controller, action, and parameters
        $controller = !empty($segments[0]) ? strtolower($segments[0]) : 'home';
        $action = isset($segments[1]) ? strtolower($segments[1]) : 'index';
        $params = array_slice($segments, 2);
    }

    // Debug logging
    if (DEBUG_MODE) {
        error_log("Route Debug - URI: " . $requestUri);
        error_log("Route Debug - Controller: " . $controller);
        error_log("Route Debug - Action: " . $action);
        error_log("Route Debug - Params: " . implode(', ', $params));
    }

    // Load the base Controller class
    require_once SRC_PATH . '/controllers/Controller.php';

    // Construct controller class name and file path
    $controllerClass = ucfirst($controller) . 'Controller';
    $controllerFile = SRC_PATH . '/controllers/' . $controllerClass . '.php';

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

    // Find the correct method name (case-insensitive)
    $methods = get_class_methods($controllerInstance);
    $methodFound = false;
    $correctMethodName = '';

    foreach ($methods as $method) {
        if (strtolower($method) === $action) {
            $methodFound = true;
            $correctMethodName = $method;
            break;
        }
    }

    if (!$methodFound) {
        throw new Exception("Action not found: " . $action . " in " . $controllerClass);
    }

    // Call the controller action with parameters
    call_user_func_array([$controllerInstance, $correctMethodName], $params);

} catch (Exception $e) {
    // Log the error
    error_log("HomEase Error: " . $e->getMessage());
    
    if (DEBUG_MODE) {
        error_log("Stack trace: " . $e->getTraceAsString());
    }
    
    // Load and display error page
    require_once SRC_PATH . '/controllers/ErrorController.php';
    $errorController = new ErrorController();
    $errorController->serverError();
} 