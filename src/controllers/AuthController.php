<?php
/**
 * Authentication Controller
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display login page
     */
    public function login() {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            $this->redirect(APP_URL);
            return;
        }
        
        $this->render('auth/login', [
            'title' => 'Login | HomeSwift',
            'styles' => ['auth']
        ]);
    }
    
    /**
     * Process login form submission
     */
    public function processLogin() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderJson([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            $this->renderJson([
                'success' => false,
                'message' => 'Please enter both email and password.'
            ]);
            return;
        }
        
        try {
            $user = $this->userModel->findByEmail($email);
            
            if (!$user) {
                $this->renderJson([
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ]);
                return;
            }

            // Check if this is a Google Sign-In user
            if ($user['google_id'] && !$user['password']) {
                $this->renderJson([
                    'success' => false,
                    'message' => 'This account uses Google Sign-In. Please login with Google.'
                ]);
                return;
            }
            
            // Verify password for non-Google users
            if (!$user['password'] || !password_verify($password, $user['password'])) {
                $this->renderJson([
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ]);
                return;
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_id'];
            $_SESSION['auth_type'] = 'manual';
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                
                if ($this->userModel->storeRememberToken($user['id'], $token, $expiry)) {
                    setcookie('remember_token', $token, $expiry, '/', '', true, true);
                }
            }

            // Get redirect URL based on role
            $redirectUrl = $this->getRedirectUrlByRole($user['role_id']);
            
            $this->renderJson([
                'success' => true,
                'message' => 'Welcome back, ' . $user['first_name'] . '!',
                'redirect' => $redirectUrl
            ]);
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->renderJson([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.'
            ]);
        }
    }
    
    /**
     * Display registration page
     */
    public function register() {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            $this->redirect(APP_URL);
            return;
        }
        
        $this->render('auth/register', [
            'title' => 'Register | HomeSwift',
            'styles' => ['auth']
        ]);
    }
    
    /**
     * Process registration form submission
     */
    public function processRegister() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderJson([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
            return;
        }

        // Check if it's an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            $this->renderJson([
                'success' => false,
                'message' => 'Invalid request type'
            ]);
            return;
        }
        
        // Get form data
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone_number'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Detailed validation
        $errors = [];
        
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        } elseif (strlen($firstName) > 50) {
            $errors[] = 'First name cannot exceed 50 characters';
        }
        
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        } elseif (strlen($lastName) > 50) {
            $errors[] = 'Last name cannot exceed 50 characters';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } elseif (strlen($email) > 100) {
            $errors[] = 'Email cannot exceed 100 characters';
        }

        if (!empty($phone) && !preg_match('/^[0-9+\-\(\)\s]{10,20}$/', $phone)) {
            $errors[] = 'Invalid phone number format';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        try {
            // Check if email already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                $this->renderJson([
                    'success' => false,
                    'message' => 'Email already in use. Please log in or use a different email address.'
                ]);
                return;
            }
            
            // If there are validation errors
            if (!empty($errors)) {
                $this->renderJson([
                    'success' => false,
                    'message' => implode('<br>', $errors)
                ]);
                return;
            }
            
            // Hash password with strong options
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            
            // Create user data array
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone_number' => $phone,
                'password' => $hashedPassword,
                'role_id' => ROLE_CLIENT,
                'is_active' => 1,
                'email_verified' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Create user and get ID
            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                // Set session variables
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = ROLE_CLIENT;
                $_SESSION['auth_type'] = 'manual';
                
                $this->renderJson([
                    'success' => true,
                    'message' => 'Welcome to HomeSwift, ' . $firstName . '! Your account has been created successfully.',
                    'redirect' => APP_URL . '/client/dashboard'
                ]);
            } else {
                error_log("Failed to create user in database");
                $this->renderJson([
                    'success' => false,
                    'message' => 'Failed to create account. Please try again later.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $this->renderJson([
                'success' => false,
                'message' => DEBUG_MODE ? 
                    'Error: ' . $e->getMessage() : 
                    'An error occurred during registration. Please try again later.'
            ]);
        }
    }
    
    /**
     * Process logout
     */
    public function logout() {
        // Clear session
        session_unset();
        session_destroy();
        
        // Clear remember me cookie if exists
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        // Redirect to login page
        $this->redirect(APP_URL . '/auth/login');
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get role name from role ID
     */
    public function getRoleName($roleId) {
        switch ($roleId) {
            case ROLE_ADMIN:
                return 'Administrator';
            case ROLE_PROVIDER:
                return 'Service Provider';
            case ROLE_CLIENT:
                return 'Client';
            default:
                return 'Unknown';
        }
    }
    
    /**
     * Get redirect URL based on role ID
     */
    public function getRedirectUrlByRole($roleId) {
        switch ($roleId) {
            case ROLE_ADMIN:
                return APP_URL . '/admin/dashboard';
            case ROLE_PROVIDER:
                return APP_URL . '/provider/dashboard';
            case ROLE_CLIENT:
            default:
                return APP_URL . '/client/dashboard';
        }
    }
} 