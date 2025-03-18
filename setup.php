<?php
/**
 * HomEase Setup Script
 * This script activates the admin account and performs initial setup
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('SRC_PATH', ROOT_PATH . '/src');

// Load configuration
require_once 'config/config.php';

// Load database connection
require_once SRC_PATH . '/database/Database.php';
require_once SRC_PATH . '/models/User.php';

echo "=========================================\n";
echo "HomEase Setup Script\n";
echo "=========================================\n\n";

// Check database connection
try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if admin account exists
$userModel = new User();
$admin = $userModel->findByEmail('admin@homeease.com');

if (!$admin) {
    echo "❌ Admin account not found. Creating...\n";
    
    // Create admin account with properly hashed password
    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    echo "DEBUG: Generated password hash: $hashedPassword\n";
    
    $adminData = [
        'email' => 'admin@homeease.com',
        'password' => $hashedPassword,
        'first_name' => 'Admin',
        'last_name' => 'User',
        'role_id' => ROLE_ADMIN,
        'is_active' => true,
        'email_verified' => true
    ];
    
    $adminId = $userModel->create($adminData);
    
    if ($adminId) {
        echo "✅ Admin account created successfully (ID: $adminId)\n";
        echo "   Email: admin@homeease.com\n";
        echo "   Password: admin123\n";
        echo "   IMPORTANT: Change this password after first login!\n";
    } else {
        echo "❌ Failed to create admin account\n";
    }
} else {
    echo "✅ Admin account exists (ID: {$admin['id']})\n";
    
    // Update admin password if needed
    if (isset($argv[1]) && $argv[1] === '--reset-password') {
        $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
        echo "DEBUG: Generated new password hash for reset: $newPassword\n";
        $result = $userModel->update($admin['id'], ['password' => $newPassword]);
        
        if ($result) {
            echo "✅ Admin password reset to 'admin123'\n";
            echo "   IMPORTANT: Change this password after login!\n";
        } else {
            echo "❌ Failed to reset admin password\n";
        }
    }
}

// Create necessary directories
$directories = [
    'public/uploads',
    'public/uploads/profiles',
    'public/uploads/services',
    'logs'
];

foreach ($directories as $dir) {
    $path = ROOT_PATH . '/' . $dir;
    if (!file_exists($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
        }
    } else {
        echo "✅ Directory exists: $dir\n";
    }
}

echo "\n=========================================\n";
echo "Setup completed successfully!\n";
echo "Admin Dashboard: " . APP_URL . "/admin/dashboard\n";
echo "Admin Login: admin@homeease.com / admin123\n";
echo "=========================================\n"; 