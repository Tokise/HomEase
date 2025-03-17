<?php
/**
 * HomEase - Home Services Platform
 * Main Entry Point
 */

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

// Route handling
$request = $_SERVER['REQUEST_URI'];
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$path = str_replace($basePath, '', $request);

// Handle URL parameters
$queryString = '';
if (strpos($path, '?') !== false) {
    list($path, $queryString) = explode('?', $path, 2);
}

// Debug for routing - comment out in production
if (DEBUG_MODE) {
    echo "<pre>Request: " . htmlspecialchars($request) . "\n";
    echo "Base Path: " . htmlspecialchars($basePath) . "\n";
    echo "Path: " . htmlspecialchars($path) . "\n";
    
    // Check for controller/action parameters in query string
    if (!empty($queryString)) {
        parse_str($queryString, $queryParams);
        if (isset($queryParams['controller']) && isset($queryParams['action'])) {
            echo "Controller from query: " . htmlspecialchars($queryParams['controller']) . "\n";
            echo "Action from query: " . htmlspecialchars($queryParams['action']) . "\n";
        }
    }
}

// Extract route segments
$routeSegments = explode('/', trim($path, '/'));

// Debug continued
if (DEBUG_MODE) {
    echo "Route Segments: ";
    print_r($routeSegments);
    echo "</pre>";
}

// Extract controller and action from query string if present
$controller = 'home'; // Default controller
$action = 'index';    // Default action

if (!empty($queryString)) {
    parse_str($queryString, $queryParams);
    if (isset($queryParams['controller'])) {
        $controller = $queryParams['controller'];
    }
    if (isset($queryParams['action'])) {
        $action = $queryParams['action'];
    }
} else {
    // Otherwise get from URL segments
    // If accessing directly as /HomEase/ or /HomEase/public/ 
    if (empty($routeSegments) || (count($routeSegments) == 1 && strtolower($routeSegments[0]) === 'homeease')) {
        // Use defaults (already set)
    } else {
        // Get controller and action from URL
        $controller = !empty($routeSegments[0]) ? $routeSegments[0] : 'home';
        $action = isset($routeSegments[1]) ? $routeSegments[1] : 'index';
    }
}

// Remove any potential dangerous characters
$controller = preg_replace('/[^a-zA-Z0-9_]/', '', $controller);
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

// Parameters are everything after the controller and action
$params = array_slice($routeSegments, 2);

// Load and execute controller
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = SRC_PATH . '/controllers/' . $controllerClass . '.php';

// Make sure any debug output is cleared if we're going to render a page
if (ob_get_length()) {
    ob_clean();
}

// Check if ErrorController.php exists first, so we can use it for error handling
$errorControllerFile = SRC_PATH . '/controllers/ErrorController.php';
$errorControllerExists = file_exists($errorControllerFile);

try {
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            
            if (method_exists($controllerInstance, $action)) {
                call_user_func_array([$controllerInstance, $action], $params);
            } else {
                // Action not found
                if ($errorControllerExists) {
                    require_once $errorControllerFile;
                    $errorController = new ErrorController();
                    $errorController->notFound();
                } else {
                    // Fallback error if ErrorController doesn't exist
                    echo "<h1>404 Not Found</h1><p>The requested action '{$action}' was not found.</p>";
                }
            }
        } else {
            // Controller class doesn't exist in the file
            if ($errorControllerExists) {
                require_once $errorControllerFile;
                $errorController = new ErrorController();
                $errorController->notFound();
            } else {
                echo "<h1>404 Not Found</h1><p>Controller class '{$controllerClass}' was not found.</p>";
            }
        }
    } else {
        // Controller file doesn't exist
        if ($errorControllerExists) {
            require_once $errorControllerFile;
            $errorController = new ErrorController();
            $errorController->notFound();
        } else {
            echo "<h1>404 Not Found</h1><p>Controller file for '{$controller}' was not found.</p>";
        }
    }
} catch (Exception $e) {
    // Handle any exceptions
    if ($errorControllerExists) {
        require_once $errorControllerFile;
        $errorController = new ErrorController();
        $errorController->serverError();
    } else {
        echo "<h1>500 Server Error</h1><p>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} 