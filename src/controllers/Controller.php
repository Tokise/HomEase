<?php
/**
 * Base Controller
 * 
 * This class provides core controller functionality for the application.
 * It handles view rendering, JSON responses, redirects, and user authentication.
 */

// Check if we're in a linter environment and provide function declarations
if (!function_exists('extract')) {
    /**
     * @param array $array
     * @return int
     */
    function extract($array) { return 0; }
    function file_exists($filename) { return true; }
    function ob_start() { return true; }
    function ob_get_clean() { return ''; }
    function header($string) { return true; }
}

class Controller {
    /**
     * Constructor
     */
    public function __construct() {
        // Base constructor for common initialization
    }

    /**
     * Render a view
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     */
    protected function render($view, $data = []) {
        extract($data);
        
        $viewPath = SRC_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception("View '{$view}' not found");
        }
        
        ob_start();
        include $viewPath;
        echo ob_get_clean();
    }
    
    /**
     * Render a view without the layout
     */
    protected function renderPartial($view, $data = []) {
        extract($data);
        
        $viewPath = SRC_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception("View '{$view}' not found");
        }
        
        ob_start();
        include $viewPath;
        echo ob_get_clean();
    }
    
    /**
     * Render JSON response
     */
    protected function renderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to another page
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Get current user from session
     */
    protected function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            require_once SRC_PATH . '/models/User.php';
            $userModel = new User();
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user has a specific role
     */
    protected function hasRole($roleId) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $user = $this->getCurrentUser();
        return $user && $user['role_id'] == $roleId;
    }
    
    /**
     * Require authentication or redirect
     */
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect(APP_URL . '/auth/login');
        }
    }
    
    /**
     * Require specific role or redirect
     */
    protected function requireRole($roleId) {
        $this->requireAuth();
        
        if (!$this->hasRole($roleId)) {
            $this->redirect(APP_URL . '/error/forbidden');
        }
    }
} 