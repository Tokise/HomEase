<?php
/**
 * HomeSwift Setup Script
 * This script initializes the database and activates admin/provider accounts
 */

// Define application path constants
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');
define('DB_PATH', ROOT_PATH . '/database');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Database connection
try {
    // Create PDO connection
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";charset=utf8", 
        DB_USER, 
        DB_PASS, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    echo "Connected to MySQL successfully.<br>";
    
    // Check if database exists, create if not
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".DB_NAME."'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        echo "Database does not exist. Creating database...<br>";
        $query = "CREATE DATABASE IF NOT EXISTS ".DB_NAME." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $pdo->exec($query);
        echo "Database '".DB_NAME."' created successfully.<br>";
    } else {
        echo "Database '".DB_NAME."' already exists.<br>";
    }
    
    // Select the database
    $pdo->exec("USE ".DB_NAME);
    
    // Run the complete schema SQL file
    echo "Initializing database schema...<br>";
    $sql = file_get_contents(DB_PATH . '/schema.sql');
    $pdo->exec($sql);
    echo "Database schema initialized successfully.<br>";
    
    // Create roles constants if not defined in config.php
    if (!defined('ROLE_ADMIN')) define('ROLE_ADMIN', 1);
    if (!defined('ROLE_PROVIDER')) define('ROLE_PROVIDER', 2);
    if (!defined('ROLE_CLIENT')) define('ROLE_CLIENT', 3);
    
    // Explicitly create and activate admin account
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, role_id, email_verified, is_active) 
                          VALUES ('admin@homeswift.com', ?, 'Admin', 'User', ?, 1, 1) 
                          ON DUPLICATE KEY UPDATE 
                          password = VALUES(password), 
                          role_id = VALUES(role_id), 
                          email_verified = 1, 
                          is_active = 1");
    $stmt->execute([$adminPassword, ROLE_ADMIN]);
    
    // Check if admin exists and is active
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = ? AND email = 'admin@homeswift.com' AND is_active = 1 AND email_verified = 1 LIMIT 1");
    $stmt->execute([ROLE_ADMIN]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "Admin account activated and ready to use.<br>";
    } else {
        echo "Failed to activate admin account. Please check the database.<br>";
    }
    
    // Explicitly create and activate provider account
    $providerPassword = password_hash('provider123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, phone_number, role_id, email_verified, is_active, business_name, business_description) 
                          VALUES ('provider@homeswift.com', ?, 'John', 'Provider', '123-456-7890', ?, 1, 1, 'Johns Cleaners', 'Professional cleaning services for your home and office.') 
                          ON DUPLICATE KEY UPDATE 
                          password = VALUES(password), 
                          role_id = VALUES(role_id), 
                          email_verified = 1, 
                          is_active = 1");
    $stmt->execute([$providerPassword, ROLE_PROVIDER]);
    
    // Check if provider exists and is active
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = ? AND email = 'provider@homeswift.com' AND is_active = 1 AND email_verified = 1 LIMIT 1");
    $stmt->execute([ROLE_PROVIDER]);
    $provider = $stmt->fetch();
    
    if ($provider) {
        echo "Provider account activated and ready to use.<br>";
        
        // First, ensure service categories exist
        $stmt = $pdo->prepare("INSERT IGNORE INTO service_categories (id, name, description, icon) VALUES 
            (1, 'Cleaning', 'Professional home cleaning services', 'fa-broom'),
            (2, 'Plumbing', 'Expert plumbing repair and installation', 'fa-faucet'),
            (3, 'Electrical', 'Electrical repair and installation services', 'fa-bolt'),
            (4, 'Gardening', 'Garden maintenance and landscaping', 'fa-leaf'),
            (5, 'Painting', 'Interior and exterior painting services', 'fa-paint-roller')");
        $stmt->execute();
        
        // Delete any existing services for this provider to avoid duplicates
        $stmt = $pdo->prepare("DELETE FROM services WHERE provider_id = ?");
        $stmt->execute([$provider['id']]);
        
        // Create sample services for the provider with is_active = 1
        $stmt = $pdo->prepare("INSERT INTO services (provider_id, category_id, name, description, price, duration, is_active) VALUES 
            (?, 1, 'Basic House Cleaning', 'General cleaning of your home including dusting, vacuuming, and mopping.', 75.00, 120, 1),
            (?, 1, 'Deep Cleaning', 'Thorough cleaning of all areas including hard to reach places and appliances.', 150.00, 240, 1),
            (?, 1, 'Office Cleaning', 'Professional cleaning services for offices and commercial spaces.', 200.00, 180, 1)");
        $stmt->execute([$provider['id'], $provider['id'], $provider['id']]);
        
        // Verify services were created
        $stmt = $pdo->prepare("SELECT COUNT(*) AS service_count FROM services WHERE provider_id = ? AND is_active = 1");
        $stmt->execute([$provider['id']]);
        $serviceCount = $stmt->fetch();
        
        if ($serviceCount['service_count'] > 0) {
            echo "Provider has " . $serviceCount['service_count'] . " active services configured.<br>";
        } else {
            echo "Warning: Failed to create active services for the provider.<br>";
        }
    } else {
        echo "Failed to activate provider account. Please check the database.<br>";
    }
    
    // Double-check both accounts are properly activated
    $stmt = $pdo->prepare("SELECT email, role_id, is_active, email_verified FROM users WHERE email IN ('admin@homeswift.com', 'provider@homeswift.com')");
    $stmt->execute();
    $accounts = $stmt->fetchAll();
    
    $allActive = true;
    foreach ($accounts as $account) {
        if (!$account['is_active'] || !$account['email_verified']) {
            $allActive = false;
            echo "Warning: Account {$account['email']} is not fully activated.<br>";
        }
    }
    
    if ($allActive) {
        echo "<br><strong>Setup completed successfully! All accounts are activated.</strong><br>";
        echo "<p>Admin login: admin@homeswift.com / Password: admin123</p>";
        echo "<p>Provider login: provider@homeswift.com / Password: provider123</p>";
        echo "<p><a href='index.php'>Go to Homepage</a></p>";
    } else {
        echo "<br><strong>Setup completed with warnings. Please check the messages above.</strong><br>";
    }
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
} 