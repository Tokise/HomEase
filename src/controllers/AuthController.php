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
        
        $googleAuthUrl = $this->getGoogleAuthUrl();
        
        $this->render('auth/login', [
            'title' => 'Login | HomEase',
            'googleAuthUrl' => $googleAuthUrl,
            'styles' => ['auth']
        ]);
    }
    
    /**
     * Process login form submission
     */
    public function processLogin() {
        // Check if the form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/login');
            return;
        }
        
        // Get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate inputs
        if (empty($email) || empty($password)) {
            $_SESSION['flash_message'] = 'Please enter both email and password.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }
        
        // Check if user exists
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'Invalid email or password.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            $_SESSION['flash_message'] = 'Invalid email or password.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $this->getRoleName($user['role_id']);
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expiry = time() + (30 * 24 * 60 * 60); // 30 days
            
            // Store token in database
            $this->userModel->storeRememberToken($user['id'], $token, $expiry);
            
            // Set cookie
            setcookie('remember_token', $token, $expiry, '/', '', false, true);
        }
        
        $_SESSION['flash_message'] = 'Welcome back, ' . $user['first_name'] . '!';
        $_SESSION['flash_type'] = 'success';
        
        // Redirect based on role
        $this->redirectBasedOnRole($user['role_id']);
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
        
        $googleAuthUrl = $this->getGoogleAuthUrl();
        
        $this->render('auth/register', [
            'title' => 'Register | HomEase',
            'googleAuthUrl' => $googleAuthUrl,
            'styles' => ['auth']
        ]);
    }
    
    /**
     * Process registration form submission
     */
    public function processRegister() {
        // Check if the form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/register');
            return;
        }
        
        // Get form data
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Basic validation
        $errors = [];
        
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        // Check if email already exists
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            $errors[] = 'Email already in use. Please log in or use a different email address.';
        }
        
        // If there are errors, redirect back with error messages
        if (!empty($errors)) {
            $_SESSION['flash_message'] = implode('<br>', $errors);
            $_SESSION['flash_type'] = 'danger';
            $_SESSION['form_data'] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone
            ];
            $this->redirect(APP_URL . '/auth/register');
            return;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user data array
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phone,
            'password' => $hashedPassword,
            'role_id' => ROLE_CLIENT // Default role for new users
        ];
        
        // Create user and get ID
        if (DEBUG_MODE) {
            error_log("Attempting to create new user via registration: " . json_encode($userData));
        }
        
        $userId = $this->userModel->create($userData);
        
        if ($userId) {
            if (DEBUG_MODE) {
                error_log("User created successfully with ID: " . $userId);
            }
            
            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'client'; // Default role
            
            $_SESSION['flash_message'] = 'Registration successful! Welcome to HomEase, ' . $firstName . '!';
            $_SESSION['flash_type'] = 'success';
            
            // Redirect to client dashboard
            $this->redirect(APP_URL . '/client/dashboard');
        } else {
            if (DEBUG_MODE) {
                error_log("Failed to create user during registration");
            }
            
            $_SESSION['flash_message'] = 'Registration failed. Please try again.';
            $_SESSION['flash_type'] = 'danger';
            $_SESSION['form_data'] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone
            ];
            $this->redirect(APP_URL . '/auth/register');
        }
    }
    
    /**
     * Generate Google Auth URL
     */
    private function getGoogleAuthUrl() {
        $redirectUri = GOOGLE_REDIRECT_URI;
        $clientId = GOOGLE_CLIENT_ID;
        
        // Define the scopes
        $scopes = [
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ];
        
        // Build the authorization URL
        $authUrl = 'https://accounts.google.com/o/oauth2/auth?';
        $authUrl .= 'client_id=' . urlencode($clientId);
        $authUrl .= '&redirect_uri=' . urlencode($redirectUri);
        $authUrl .= '&response_type=code';
        $authUrl .= '&scope=' . urlencode(implode(' ', $scopes));
        $authUrl .= '&access_type=offline';
        $authUrl .= '&prompt=consent';
        
        return $authUrl;
    }
    
    /**
     * Process Google OAuth callback
     */
    public function googleCallback() {
        if (DEBUG_MODE) {
            error_log("Google callback received: " . $_SERVER['REQUEST_URI']);
            error_log("GET params: " . json_encode($_GET));
        }
        
        try {
            if (!isset($_GET['code'])) {
                if (DEBUG_MODE) {
                    error_log("No authorization code received from Google");
                }
                throw new Exception('Invalid authorization request');
            }
            
            $code = $_GET['code'];
            
            // Get access token using the authorization code
            try {
                $tokenData = $this->getAccessToken($code);
                
                if (DEBUG_MODE) {
                    error_log("Token data: " . json_encode($tokenData));
                }
                
                if (!isset($tokenData['access_token'])) {
                    if (DEBUG_MODE && isset($tokenData['error'])) {
                        error_log("Token error: " . $tokenData['error'] . " - " . ($tokenData['error_description'] ?? 'No description'));
                    }
                    throw new Exception('Failed to get access token');
                }
                
                // Get user info from Google
                $userInfo = $this->getUserInfo($tokenData['access_token']);
                
                if (DEBUG_MODE) {
                    error_log("User info received from Google: " . json_encode($userInfo));
                }
                
                if (!isset($userInfo['email'])) {
                    throw new Exception('Failed to get user email from Google');
                }
                
                // Check if user exists
                $user = $this->userModel->findByEmail($userInfo['email']);
                
                // Handle existing user
                if ($user) {
                    if (DEBUG_MODE) {
                        error_log("User found in database: " . json_encode($user));
                    }
                    
                    // Update Google ID if needed
                    if (empty($user['google_id']) && isset($userInfo['sub'])) {
                        $result = $this->userModel->update($user['id'], [
                            'google_id' => $userInfo['sub'],
                            'profile_picture' => $userInfo['picture'] ?? $user['profile_picture']
                        ]);
                        
                        if (DEBUG_MODE) {
                            error_log("User update result: " . ($result ? "Success" : "Failed"));
                        }
                    }
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $this->getRoleName($user['role_id']);
                    
                    $_SESSION['flash_message'] = 'Welcome back, ' . $user['first_name'] . '!';
                    $_SESSION['flash_type'] = 'success';
                    
                    if (DEBUG_MODE) {
                        error_log("User logged in: " . $user['email'] . " (ID: " . $user['id'] . ")");
                        error_log("Redirecting to dashboard based on role: " . $_SESSION['user_role']);
                    }
                    
                    // Redirect to appropriate dashboard
                    $this->redirectBasedOnRole($user['role_id']);
                } 
                // Create new user
                else {
                    // Extract user data from Google response
                    $userData = [
                        'email' => $userInfo['email'],
                        'first_name' => $userInfo['given_name'] ?? 'User',
                        'last_name' => $userInfo['family_name'] ?? 'Name',
                        'google_id' => $userInfo['sub'] ?? null,
                        'profile_picture' => $userInfo['picture'] ?? null,
                        'role_id' => ROLE_CLIENT, // Default to client role
                        'phone_number' => null, // Add required fields with defaults
                        'password' => null  // Add password as null for Google users
                    ];
                    
                    if (DEBUG_MODE) {
                        error_log("Creating new user: " . json_encode($userData));
                    }
                    
                    // Create user in database
                    $userId = $this->userModel->create($userData);
                    
                    if ($userId) {
                        if (DEBUG_MODE) {
                            error_log("New user created with ID: " . $userId);
                        }
                        
                        // Set session variables
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
                        $_SESSION['user_email'] = $userData['email'];
                        $_SESSION['user_role'] = 'client'; // Default role for new users
                        
                        $_SESSION['flash_message'] = 'Welcome to HomEase, ' . $userData['first_name'] . '!';
                        $_SESSION['flash_type'] = 'success';
                        
                        if (DEBUG_MODE) {
                            error_log("New user logged in: " . $userData['email'] . " (ID: " . $userId . ")");
                            error_log("Redirecting to client dashboard");
                        }
                        
                        // Redirect to client dashboard
                        $this->redirect(APP_URL . '/client/dashboard');
                    } else {
                        throw new Exception('Failed to create user account');
                    }
                }
            } catch (Exception $e) {
                if (DEBUG_MODE) {
                    error_log("Error during token exchange: " . $e->getMessage());
                }
                throw $e;
            }
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                error_log("Google auth error: " . $e->getMessage());
                error_log($e->getTraceAsString());
            }
            $_SESSION['flash_message'] = 'Authentication error: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
        }
    }
    
    /**
     * Exchange authorization code for access token
     */
    private function getAccessToken($code) {
        $clientId = GOOGLE_CLIENT_ID;
        $clientSecret = GOOGLE_CLIENT_SECRET;
        $redirectUri = GOOGLE_REDIRECT_URI;
        
        $postData = [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_POST, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    /**
     * Get user info from Google
     */
    private function getUserInfo($accessToken) {
        $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        
        $response = curl_exec($ch);
        
        if (DEBUG_MODE) {
            error_log("Google user info response: " . $response);
        }
        
        if (curl_errno($ch)) {
            if (DEBUG_MODE) {
                error_log("cURL error in getUserInfo: " . curl_error($ch));
            }
            throw new Exception("Failed to fetch user information from Google");
        }
        
        curl_close($ch);
        
        $userInfo = json_decode($response, true);
        
        // Ensure the Google ID is properly used
        // Google's API returns "sub" as the unique identifier
        if (isset($userInfo['sub']) && !isset($userInfo['id'])) {
            $userInfo['id'] = $userInfo['sub'];
        }
        
        return $userInfo;
    }
    
    /**
     * Get role name from role ID
     */
    private function getRoleName($roleId) {
        switch ($roleId) {
            case ROLE_ADMIN:
                return 'admin';
            case ROLE_MANAGER:
                return 'manager';
            case ROLE_CLIENT:
            default:
                return 'client';
        }
    }
    
    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($roleId) {
        switch ($roleId) {
            case ROLE_ADMIN:
                $this->redirect(APP_URL . '/admin/dashboard');
                break;
            case ROLE_MANAGER:
                $this->redirect(APP_URL . '/manager/dashboard');
                break;
            case ROLE_CLIENT:
            default:
                $this->redirect(APP_URL . '/client/dashboard');
                break;
        }
    }
    
    /**
     * Log out the user
     */
    public function logout() {
        // Clear the remember me cookie if it exists
        if (isset($_COOKIE['remember_token'])) {
            // Remove token from database
            if (isset($_SESSION['user_id'])) {
                $this->userModel->removeRememberToken($_SESSION['user_id']);
            }
            
            // Expire the cookie
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        
        // Clear the session
        session_unset();
        session_destroy();
        
        // Start a new session for flash message
        session_start();
        
        $_SESSION['flash_message'] = 'You have been logged out successfully.';
        $_SESSION['flash_type'] = 'info';
        
        $this->redirect(APP_URL);
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        // Check if user is logged in via session
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        
        // Check if user has a remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $user = $this->userModel->getUserByRememberToken($token);
            
            if ($user) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $this->getRoleName($user['role_id']);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Debug method to check Google callback
     */
    public function checkGoogleCallback() {
        header('Content-Type: text/html; charset=utf-8');
        echo '<h1>Google Callback Debug</h1>';
        echo '<p>This page confirms that the Google callback route is working.</p>';
        echo '<p>Current URL: ' . $_SERVER['REQUEST_URI'] . '</p>';
        echo '<p>GET Parameters:</p>';
        echo '<pre>';
        var_dump($_GET);
        echo '</pre>';
        
        if (isset($_GET['code'])) {
            echo '<p style="color: green;">✓ Authorization code is present: ' . htmlspecialchars($_GET['code']) . '</p>';
            
            try {
                // Test the token exchange
                echo '<h2>Testing token exchange:</h2>';
                $tokenData = $this->getAccessToken($_GET['code']);
                echo '<pre>';
                var_dump($tokenData);
                echo '</pre>';
                
                if (isset($tokenData['access_token'])) {
                    echo '<p style="color: green;">✓ Access token received successfully</p>';
                    
                    // Test user info retrieval
                    echo '<h2>Testing user info retrieval:</h2>';
                    $userInfo = $this->getUserInfo($tokenData['access_token']);
                    echo '<pre>';
                    var_dump($userInfo);
                    echo '</pre>';
                    
                    if (isset($userInfo['email'])) {
                        echo '<p style="color: green;">✓ User info received successfully</p>';
                        
                        // Check if user exists
                        $user = $this->userModel->findByEmail($userInfo['email']);
                        if ($user) {
                            echo '<p style="color: green;">✓ User found in database: ' . htmlspecialchars($userInfo['email']) . '</p>';
                        } else {
                            echo '<p style="color: orange;">⚠ User not found in database, would create new account for: ' . htmlspecialchars($userInfo['email']) . '</p>';
                        }
                    } else {
                        echo '<p style="color: red;">✗ Failed to get user email</p>';
                    }
                } else {
                    echo '<p style="color: red;">✗ Failed to get access token</p>';
                }
            } catch (Exception $e) {
                echo '<p style="color: red;">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p style="color: red;">✗ Authorization code is missing</p>';
        }
        exit;
    }
} 