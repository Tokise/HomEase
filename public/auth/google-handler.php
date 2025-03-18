<?php
// Start session and error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Define constants
    define('ROOT_PATH', dirname(dirname(__DIR__)));
    define('SRC_PATH', ROOT_PATH . '/src');

    // Load configurations
    require_once ROOT_PATH . '/config/config.php';

    // Load required files
    require_once SRC_PATH . '/database/Database.php';
    require_once SRC_PATH . '/controllers/Controller.php';
    require_once SRC_PATH . '/controllers/AuthController.php';
    require_once SRC_PATH . '/models/User.php';
    require_once ROOT_PATH . '/vendor/autoload.php';

    // Set response headers
    header('Content-Type: application/json');

    // Debug logging
    if (DEBUG_MODE) {
        error_log("Google Handler Started");
        error_log("POST Data: " . json_encode($_POST));
        error_log("Session Data: " . json_encode($_SESSION));
    }

    // Check for credential
    if (!isset($_POST['credential'])) {
        throw new Exception("No credential provided");
    }

    // Check for Google Client library
    if (!class_exists('Google_Client')) {
        throw new Exception("Google Client library not found. Please run: composer require google/apiclient");
    }

    // Initialize Google Client
    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
    
    // Verify the token
    $payload = $client->verifyIdToken($_POST['credential']);
    
    if (!$payload) {
        throw new Exception("Invalid token");
    }

    if (DEBUG_MODE) {
        error_log("Token Payload: " . json_encode($payload));
    }

    // Extract user data
    $email = $payload['email'] ?? '';
    $google_id = $payload['sub'] ?? '';
    $name = $payload['name'] ?? '';
    $picture = $payload['picture'] ?? null;
    $email_verified = $payload['email_verified'] ?? false;

    if (empty($email) || empty($google_id)) {
        throw new Exception("Required user data missing from Google response");
    }

    // Split name
    $name_parts = explode(' ', $name);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : '';

    // Initialize models and controllers
    $userModel = new User();
    $authController = new AuthController();

    // Check if user exists
    $user = $userModel->findByEmail($email);
    if (!$user) {
        $user = $userModel->findByGoogleId($google_id);
    }

    if (!$user) {
        if (DEBUG_MODE) {
            error_log("Creating new user: $email");
        }

        // Create new user
        $userData = [
            'email' => $email,
            'google_id' => $google_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'profile_picture' => $picture,
            'role_id' => ROLE_CLIENT,
            'email_verified' => $email_verified ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (DEBUG_MODE) {
            error_log("Attempting to create user with data: " . json_encode($userData));
        }

        $user_id = $userModel->create($userData);
        if (!$user_id) {
            throw new Exception("Failed to create user account");
        }

        $user = $userModel->findById($user_id);
        if (!$user) {
            throw new Exception("Failed to retrieve created user");
        }
    } else {
        if (DEBUG_MODE) {
            error_log("Updating existing user: " . $user['id']);
        }

        // Update user's Google information
        $updateData = [
            'google_id' => $google_id,
            'profile_picture' => $picture,
            'email_verified' => $email_verified ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!$userModel->update($user['id'], $updateData)) {
            error_log("Warning: Failed to update user Google information");
        }
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $authController->getRoleName($user['role_id']);
    $_SESSION['auth_type'] = 'google';

    // Get redirect URL based on role
    $redirect = $authController->getRedirectUrlByRole($user['role_id']);

    if (empty($redirect)) {
        $redirect = APP_URL . '/client/dashboard'; // Default redirect
    }

    if (DEBUG_MODE) {
        error_log("Login successful. User ID: " . $user['id']);
        error_log("User role: " . $_SESSION['user_role']);
        error_log("Redirecting to: $redirect");
        error_log("Session data: " . json_encode($_SESSION));
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Successfully signed in with Google',
        'redirect' => $redirect
    ]);

} catch (Exception $e) {
    error_log("Google Handler Error: " . $e->getMessage());
    if (DEBUG_MODE) {
        error_log("Error trace: " . $e->getTraceAsString());
    }

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => DEBUG_MODE ? $e->getMessage() : 'An error occurred during Google Sign-In',
        'debug' => DEBUG_MODE ? $e->getTraceAsString() : null
    ]);
} 