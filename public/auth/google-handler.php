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
        throw new Exception('Invalid request method or missing credential');
    }

    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
    $payload = $client->verifyIdToken($_POST['credential']);
    
    if (!$payload) {
        throw new Exception('Invalid token');
    }

    // Extract user data from payload
    $googleId = $payload['sub'];
    $email = $payload['email'];
    $firstName = $payload['given_name'] ?? '';
    $lastName = $payload['family_name'] ?? '';
    $picture = $payload['picture'] ?? null;

    $userModel = new User();
    
    // Try to find user by Google ID first
    $user = $userModel->findByGoogleId($googleId);
    
    if (!$user) {
        // If not found by Google ID, try email
        $user = $userModel->findByEmail($email);
        
        if ($user) {
            // User exists but hasn't linked Google account
            $userModel->updateGoogleInfo($user['id'], $googleId, ['picture' => $picture]);
        } else {
            // Create new user
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
                throw new Exception('Failed to create user account');
            }
            
            $user = $userModel->findById($userId);
            
            if (!$user) {
                throw new Exception('Failed to retrieve created user');
            }
        }
    } else {
        // Update existing user's Google info
        $userModel->updateGoogleInfo($user['id'], $googleId, ['picture' => $picture]);
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role_id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    
    // Determine redirect URL based on role
    $redirectUrl = $user['role_id'] === 1 ? APP_URL . '/admin/dashboard' : APP_URL . '/client/dashboard';
    
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
        'message' => 'Authentication failed: ' . $e->getMessage()
    ]);
} 