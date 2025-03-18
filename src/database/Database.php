<?php
/**
 * Database Connection Class
 * Implements singleton pattern for database connection
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Constructor - Connect to the database
     */
    private function __construct() {
        try {
            // First try to connect without database name
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );

            // Check if database exists
            $stmt = $this->connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
            $dbExists = $stmt->fetch();

            if (!$dbExists) {
                // Create database if it doesn't exist
                $this->connection->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Select the database
                $this->connection->exec("USE `" . DB_NAME . "`");
                
                // Import schema
                if (file_exists(ROOT_PATH . '/database/schema.sql')) {
                    $sql = file_get_contents(ROOT_PATH . '/database/schema.sql');
                    
                    // Split SQL into individual statements
                    $statements = array_filter(array_map('trim', explode(';', $sql)));
                    
                    foreach ($statements as $statement) {
                        if (!empty($statement)) {
                            try {
                                $this->connection->exec($statement);
                            } catch (PDOException $e) {
                                error_log("Error executing schema statement: " . $e->getMessage());
                                error_log("Statement: " . $statement);
                            }
                        }
                    }
                }
            }

            // Connect to the specific database
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}
    
    /**
     * Prevent unserializing of the instance
     */
    public function __wakeup() {}
} 