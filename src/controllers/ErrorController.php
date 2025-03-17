<?php
/**
 * Error Controller
 */
require_once SRC_PATH . '/controllers/Controller.php';

class ErrorController extends Controller {
    /**
     * 404 Not Found page
     */
    public function notFound() {
        http_response_code(404);
        $this->render('errors/not-found', [
            'title' => '404 - Page Not Found'
        ]);
    }
    
    /**
     * 403 Forbidden page
     */
    public function forbidden() {
        http_response_code(403);
        $this->render('errors/forbidden', [
            'title' => '403 - Forbidden'
        ]);
    }
    
    /**
     * 500 Server Error page
     */
    public function serverError() {
        http_response_code(500);
        $this->render('errors/server-error', [
            'title' => '500 - Server Error'
        ]);
    }
} 