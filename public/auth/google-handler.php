<?php
// Start session and error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('SRC_PATH', ROOT_PATH . '/src');

// Load dependencies
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/config.php';
require_once SRC_PATH . '/models/User.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['credential'])) {
        error_log("Google Auth: Invalid request - Method: " . $_SERVER['REQUEST_METHOD'] . ", Credential present: " . !empty($_POST['credential']));
        throw new Exception('Invalid request method or missing credential');
    }

    error_log("Google Auth: Attempting to verify token with client ID: " . GOOGLE_CLIENT_ID);
    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
    
    try {
        $payload = $client->verifyIdToken($_POST['credential']);
        error_log("Google Auth: Token verification result: " . ($payload ? "Success" : "Failed"));
    } catch (Exception $e) {
        error_log("Google Auth: Token verification error - " . $e->getMessage());
        throw new Exception('Failed to verify Google token: ' . $e->getMessage());
    }
    
    if (!$payload) {
        error_log("Google Auth: Invalid token - no payload returned");
        throw new Exception('Invalid token');
    }

    // Log payload data (excluding sensitive info)
    error_log("Google Auth: Payload received - Email: " . ($payload['email'] ?? 'not set') . 
              ", Sub: " . ($payload['sub'] ?? 'not set') . 
              ", Name: " . ($payload['given_name'] ?? 'not set') . " " . ($payload['family_name'] ?? 'not set'));

    // Extract user data from payload
    $googleId = $payload['sub'];
    $email = $payload['email'];
    $firstName = $payload['given_name'] ?? '';
    $lastName = $payload['family_name'] ?? '';
    $picture = $payload['picture'] ?? null;

    $userModel = new User();
    
    // Try to find user by Google ID first
    error_log("Google Auth: Searching for user with Google ID: " . $googleId);
    $user = $userModel->findByGoogleId($googleId);
    
    if (!$user) {
        // If not found by Google ID, try email
        error_log("Google Auth: User not found by Google ID, searching by email: " . $email);
        $user = $userModel->findByEmail($email);
        
        if ($user) {
            // User exists but hasn't linked Google account
            error_log("Google Auth: Updating existing user with Google info - User ID: " . $user['id']);
            $userModel->updateGoogleInfo($user['id'], $googleId, ['picture' => $picture]);
        } else {
            // Create new user
            error_log("Google Auth: Creating new user with email: " . $email);
            $userData = [
                'email' => $email,
                'google_id' => $googleId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'google_picture' => $picture,
                'role_id' => 3, // Default to client role
                'email_verified' => true,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $userId = $userModel->create($userData);
            
            if (!$userId) {
                error_log("Google Auth: Failed to create user - " . print_r($userData, true));
                throw new Exception('Failed to create user account');
            }
            
            error_log("Google Auth: New user created with ID: " . $userId);
            $user = $userModel->findById($userId);
            
            if (!$user) {
                error_log("Google Auth: Failed to retrieve created user with ID: " . $userId);
                throw new Exception('Failed to retrieve created user');
            }
        }
    } else {
        error_log("Google Auth: Existing user found with Google ID - User ID: " . $user['id']);
        // Update existing user's Google info
        $userModel->updateGoogleInfo($user['id'], $googleId, ['picture' => $picture]);
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role_id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['auth_type'] = 'google';
    
    // Determine redirect URL based on role
    $redirectUrl = APP_URL;
    switch ($user['role_id']) {
        case ROLE_ADMIN:
            $redirectUrl .= '/admin/dashboard';
            break;
        case ROLE_PROVIDER:
            $redirectUrl .= '/provider/dashboard';
            break;
        case ROLE_CLIENT:
        default:
            $redirectUrl .= '/client/dashboard';
            break;
    }
    
    error_log("Google Auth: Login successful - User ID: " . $user['id'] . ", Redirecting to: " . $redirectUrl);
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'redirect' => $redirectUrl
    ]);

} catch (Exception $e) {
    error_log("Google Auth Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => DEBUG_MODE ? 
            'Authentication failed: ' . $e->getMessage() : 
            'Authentication failed. Please try again.'
    ]);
} 