<?php

abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        require_once __DIR__ . '/../database/Database.php';
        $this->db = Database::getInstance()->getConnection();
        
        if (!$this->db) {
            throw new Exception("Failed to connect to database");
        }
    }

    /**
     * Find a record by ID
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding record by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all records from table
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all records: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a new record
     */
    public function create($data) {
        try {
            $fields = array_keys($data);
            $values = array_values($data);
            $placeholders = array_fill(0, count($fields), '?');

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating record: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a record
     */
    public function update($id, $data) {
        try {
            $fields = array_keys($data);
            $values = array_values($data);
            $setClause = array_map(function($field) {
                return "$field = ?";
            }, $fields);

            $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE id = ?";
            
            $values[] = $id; // Add ID for WHERE clause
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating record: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a record
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting record: " . $e->getMessage());
            return false;
        }
    }
}
