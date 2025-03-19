<?php
require_once 'config/config.php';
require_once 'src/database/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Activate both admin and provider accounts
    $stmt = $db->prepare("UPDATE users SET is_active = 1, email_verified = 1 WHERE email IN (?, ?)");
    $result = $stmt->execute(['admin@homeswift.com', 'provider@homeswift.com']);
    
    if ($result) {
        echo "Accounts activated successfully!\n";
        
        // Verify the activation
        $stmt = $db->prepare("SELECT email, role_id, email_verified, is_active FROM users WHERE email IN (?, ?)");
        $stmt->execute(['admin@homeswift.com', 'provider@homeswift.com']);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            echo "\nAccount details for {$user['email']}:\n";
            echo "Role ID: {$user['role_id']}\n";
            echo "Email verified: " . ($user['email_verified'] ? 'Yes' : 'No') . "\n";
            echo "Is active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "Failed to activate accounts.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 