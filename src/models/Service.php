<?php
/**
 * Service Model
 */
require_once SRC_PATH . '/database/Database.php';

class Service {
    private $db;
    private $table = 'services';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all services
     */
    public function getAll() {
        $sql = "SELECT s.*, c.name as category_name 
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.is_active = TRUE
                ORDER BY s.id DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get service by ID
     */
    public function findById($id) {
        $sql = "SELECT s.*, c.name as category_name 
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.id = :id 
                LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding service: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get services by category ID
     */
    public function getByCategory($categoryId) {
        $sql = "SELECT s.*, c.name as category_name 
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.category_id = :category_id AND s.is_active = TRUE
                ORDER BY s.name ASC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting services by category: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get featured services
     */
    public function getFeatured($limit = 6) {
        $sql = "SELECT s.*, c.name as category_name 
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.is_active = TRUE
                ORDER BY RAND()
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting featured services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get services for a specific provider
     */
    public function getProviderServices($providerId) {
        $providerId = (int)$providerId;
        $result = $this->db->query("
            SELECT s.id, s.name, s.category_id, c.name as category_name, ps.price, ps.duration
            FROM services s
            JOIN provider_services ps ON s.id = ps.service_id
            JOIN service_categories c ON s.category_id = c.id
            WHERE ps.provider_id = {$providerId} AND s.is_active = TRUE
            ORDER BY s.name ASC
        ");
        
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        return $services;
    }
    
    /**
     * Create a new service
     */
    public function create($serviceData) {
        $categoryId = (int)$serviceData['category_id'];
        $name = $this->db->escapeString($serviceData['name']);
        $description = $this->db->escapeString($serviceData['description'] ?? '');
        $price = (float)$serviceData['price'];
        $duration = (int)$serviceData['duration'];
        $image = isset($serviceData['image']) ? "'" . $this->db->escapeString($serviceData['image']) . "'" : 'NULL';
        $isActive = isset($serviceData['is_active']) ? ($serviceData['is_active'] ? 'TRUE' : 'FALSE') : 'TRUE';
        
        $query = "INSERT INTO services (category_id, name, description, price, duration, image, is_active) 
                  VALUES ({$categoryId}, '{$name}', '{$description}', {$price}, {$duration}, {$image}, {$isActive})";
        
        $this->db->query($query);
        return $this->db->getLastInsertId();
    }
    
    /**
     * Update an existing service
     */
    public function update($id, $serviceData) {
        $sets = [];
        
        if (isset($serviceData['category_id'])) {
            $sets[] = "category_id = " . (int)$serviceData['category_id'];
        }
        
        if (isset($serviceData['name'])) {
            $sets[] = "name = '" . $this->db->escapeString($serviceData['name']) . "'";
        }
        
        if (isset($serviceData['description'])) {
            $sets[] = "description = '" . $this->db->escapeString($serviceData['description']) . "'";
        }
        
        if (isset($serviceData['price'])) {
            $sets[] = "price = " . (float)$serviceData['price'];
        }
        
        if (isset($serviceData['duration'])) {
            $sets[] = "duration = " . (int)$serviceData['duration'];
        }
        
        if (isset($serviceData['image'])) {
            $sets[] = "image = '" . $this->db->escapeString($serviceData['image']) . "'";
        }
        
        if (isset($serviceData['is_active'])) {
            $sets[] = "is_active = " . ($serviceData['is_active'] ? 'TRUE' : 'FALSE');
        }
        
        if (empty($sets)) {
            return false;
        }
        
        $query = "UPDATE services SET " . implode(', ', $sets) . " WHERE id = " . (int)$id;
        $this->db->query($query);
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Delete a service
     */
    public function delete($id) {
        $id = (int)$id;
        $this->db->query("DELETE FROM services WHERE id = {$id}");
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Search services by name or description
     */
    public function search($searchTerm, $limit = 10) {
        $searchTerm = $this->db->escapeString($searchTerm);
        $limit = (int)$limit;
        
        $result = $this->db->query("
            SELECT s.*, c.name as category_name 
            FROM services s
            JOIN service_categories c ON s.category_id = c.id
            WHERE s.is_active = TRUE 
            AND (s.name LIKE '%{$searchTerm}%' OR s.description LIKE '%{$searchTerm}%')
            ORDER BY s.name ASC
            LIMIT {$limit}
        ");
        
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        return $services;
    }
} 