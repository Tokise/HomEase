<?php
/**
 * Database Connection Utility
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        try {
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("Database connection failed: " . $this->connection->connect_error);
            }
            
            // Set charset to UTF-8
            $this->connection->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get database instance (Singleton pattern)
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
     * Execute a query and return result
     */
    public function query($sql) {
        try {
            $result = $this->connection->query($sql);
            if (!$result) {
                $error = "Query failed: " . $this->connection->error . " in query: " . $sql;
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log($error);
                }
                throw new Exception($error);
            }
            return $result;
        } catch (Exception $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Database query error: " . $e->getMessage());
            }
            throw $e;
        }
    }
    
    /**
     * Prepare a statement
     */
    public function prepare($sql) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }
        return $stmt;
    }
    
    /**
     * Escape string for safe insertion into database
     */
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
    
    /**
     * Get the ID of the last inserted row
     */
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Get the number of affected rows
     */
    public function getAffectedRows() {
        return $this->connection->affected_rows;
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Close the database connection
     */
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
            self::$instance = null;
        }
    }
    
    /**
     * Destructor to ensure connection is properly closed
     */
    public function __destruct() {
        $this->closeConnection();
    }
} 