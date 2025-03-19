<?php
/**
 * Home Controller
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/ServiceCategory.php';

class HomeController extends Controller {
    private $serviceModel;
    private $categoryModel;
    
    public function __construct() {
        try {
            $this->serviceModel = new Service();
            $this->categoryModel = new ServiceCategory();
        } catch (Exception $e) {
            // Log error but don't stop execution
            error_log("Error initializing models: " . $e->getMessage());
        }
    }
    
    /**
     * Display home page
     */
    public function index() {
        try {
            // Check if this is a preview request
            $isPreview = isset($_GET['preview']) && $_GET['preview'] === 'true';
            
            // Store original session data if in preview mode
            $originalSession = null;
            if ($isPreview) {
                $originalSession = $_SESSION;
                session_write_close();
                session_start();
                $_SESSION = array(); // Clear session data for preview
            }
            
            // Only redirect if not in preview mode
            if (!$isPreview && $this->isAuthenticated()) {
                $user = $this->getCurrentUser();
                if (!$user) {
                    // If session exists but user not found, clear session
                    session_unset();
                    session_destroy();
                    session_start();
                    $this->redirect(APP_URL);
                    return;
                }
                
                // Redirect based on role
                switch ($user['role_id']) {
                    case ROLE_ADMIN:
                        $this->redirect(APP_URL . '/admin/dashboard');
                        break;
                    case ROLE_PROVIDER:
                        $this->redirect(APP_URL . '/provider/dashboard');
                        break;
                    case ROLE_CLIENT:
                    default:
                        $this->redirect(APP_URL . '/client/dashboard');
                        break;
                }
                return;
            }
            
            // Make sure any debug routing information is cleared
            if (ob_get_length()) {
                ob_clean();
            }

            // Get featured services and categories if available
            $featuredServices = [];
            $categories = [];
            
            try {
                if ($this->serviceModel && $this->categoryModel) {
                    $featuredServices = $this->serviceModel->getFeatured(6);
                    $categories = $this->categoryModel->getAll();
                }
            } catch (Exception $e) {
                error_log("Error fetching services/categories: " . $e->getMessage());
            }
            
            // Render the landing page
            $this->render('home/landing', [
                'title' => 'Home Services Made Easy - HomeSwift',
                'featuredServices' => $featuredServices,
                'categories' => $categories,
                'isPreview' => $isPreview
            ]);
            
            // Restore original session if in preview mode
            if ($isPreview && $originalSession) {
                session_write_close();
                session_start();
                $_SESSION = $originalSession;
            }
            
        } catch (Exception $e) {
            error_log("Error in HomeController::index: " . $e->getMessage());
            $this->render('home/landing', [
                'title' => 'Home Services Made Easy - HomeSwift',
                'error' => 'An error occurred while loading the page.'
            ]);
        }
    }
    
    /**
     * Services homepage
     */
    public function services() {
        // Get featured services
        $featuredServices = $this->serviceModel->getFeatured(6);
        
        // Get all service categories
        $categories = $this->categoryModel->getAll();
        
        $this->render('home/index', [
            'title' => 'Home Services Made Easy',
            'featuredServices' => $featuredServices,
            'categories' => $categories
        ]);
    }
    
    /**
     * About page
     */
    public function about() {
        $this->render('home/about', [
            'title' => 'About Us'
        ]);
    }
    
    /**
     * Contact page
     */
    public function contact() {
        $this->render('home/contact', [
            'title' => 'Contact Us'
        ]);
    }
    
    /**
     * Process contact form
     */
    public function processContact() {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/contact');
            return;
        }
        
        // Validate inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        
        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['flash_message'] = 'Please fill in all required fields.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/contact');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_message'] = 'Please enter a valid email address.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/contact');
            return;
        }
        
        // In a real application, you would send an email here
        // For now, we'll just simulate it
        
        $_SESSION['flash_message'] = 'Thank you for your message! We will get back to you soon.';
        $_SESSION['flash_type'] = 'success';
        $this->redirect(APP_URL . '/contact');
    }
    
    /**
     * Terms of Service page
     */
    public function termsOfService() {
        $this->render('home/terms', [
            'title' => 'Terms of Service'
        ]);
    }
    
    /**
     * Privacy Policy page
     */
    public function privacyPolicy() {
        $this->render('home/privacy', [
            'title' => 'Privacy Policy'
        ]);
    }
} 