<?php
/**
 * Service Model
 */
class Service {
    private $db;
    private $table = 'services';
    
    public function __construct() {
        require_once __DIR__ . '/../database/Database.php';
        $this->db = Database::getInstance()->getConnection();
        
        if (!$this->db) {
            throw new Exception("Failed to connect to database");
        }
    }
    
    /**
     * Get all services
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT s.*, c.name as category_name
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                ORDER BY s.name ASC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get active services
     */
    public function getActive() {
        try {
            $stmt = $this->db->query("
                SELECT s.*, c.name as category_name
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.is_active = 1
                ORDER BY s.name ASC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting active services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get service by ID
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT s.*, c.name as category_name
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding service by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get services by category
     */
    public function getByCategory($categoryId) {
        try {
            $stmt = $this->db->prepare("
                SELECT s.*, c.name as category_name
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE s.category_id = ? AND s.is_active = 1
                ORDER BY s.name ASC
            ");
            $stmt->execute([$categoryId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting services by category: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create service
     */
    public function create($data) {
        try {
            // Prepare fields and values
            $fields = [];
            $values = [];
            $placeholders = [];
            
            foreach ($data as $field => $value) {
                if ($value !== null) {  // Only include non-null values
                    $fields[] = $field;
                    $values[] = $value;
                    $placeholders[] = '?';
                }
            }
            
            $fieldsStr = implode(', ', $fields);
            $placeholdersStr = implode(', ', $placeholders);
            
            $sql = "INSERT INTO {$this->table} ($fieldsStr) VALUES ($placeholdersStr)";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($values);
            
            if ($success) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update service
     */
    public function update($id, $data) {
        try {
            $sets = [];
            $values = [];
            
            foreach ($data as $field => $value) {
                if ($value !== null) {  // Only update non-null values
                    $sets[] = "$field = ?";
                    $values[] = $value;
                }
            }
            
            $values[] = $id;  // Add ID for WHERE clause
            $setsStr = implode(', ', $sets);
            
            $sql = "UPDATE {$this->table} SET $setsStr WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete service
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get service categories
     */
    public function getCategories() {
        try {
            $stmt = $this->db->query("SELECT * FROM service_categories WHERE is_active = 1 ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting service categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search services
     */
    public function search($keyword) {
        try {
            $keyword = "%$keyword%";
            $stmt = $this->db->prepare("
                SELECT s.*, c.name as category_name
                FROM {$this->table} s
                JOIN service_categories c ON s.category_id = c.id
                WHERE (s.name LIKE ? OR s.description LIKE ?) AND s.is_active = 1
                ORDER BY s.name ASC
            ");
            $stmt->execute([$keyword, $keyword]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error searching services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get featured services
     * 
     * @param int $limit Number of services to return
     * @return array Featured services
     */
    public function getFeatured($limit = 4) {
        try {
            // First check if is_featured column exists
            try {
                $stmt = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE 'is_featured'");
                $hasFeaturedColumn = $stmt->fetch() !== false;
            } catch (PDOException $e) {
                $hasFeaturedColumn = false;
                error_log("Error checking for is_featured column: " . $e->getMessage());
            }
            
            // If the column exists, use it to get featured services
            if ($hasFeaturedColumn) {
                $stmt = $this->db->prepare("
                    SELECT s.*, c.name as category_name, 
                           (SELECT AVG(rating) FROM reviews WHERE service_id = s.id) as avg_rating
                    FROM {$this->table} s
                    JOIN service_categories c ON s.category_id = c.id
                    WHERE s.is_active = 1 AND s.is_featured = 1
                    ORDER BY avg_rating DESC, s.name ASC
                    LIMIT ?
                ");
                $stmt->execute([$limit]);
                $featured = $stmt->fetchAll();
            } else {
                // If the column doesn't exist, just get the most recently added services
                $featured = [];
            }
            
            // If we don't have enough featured services, get the most popular ones
            if (count($featured) < $limit) {
                $neededMore = $limit - count($featured);
                $existingIds = array_column($featured, 'id');
                $notInClause = count($existingIds) > 0 
                    ? "AND s.id NOT IN (" . implode(',', array_fill(0, count($existingIds), '?')) . ")" 
                    : "";
                
                $sql = "
                    SELECT s.*, c.name as category_name
                    FROM {$this->table} s
                    JOIN service_categories c ON s.category_id = c.id
                    WHERE s.is_active = 1 $notInClause
                    ORDER BY s.created_at DESC, s.name ASC
                    LIMIT ?
                ";
                
                $stmt = $this->db->prepare($sql);
                
                $params = $existingIds;
                $params[] = $neededMore;
                $stmt->execute($params);
                $popular = $stmt->fetchAll();
                
                $featured = array_merge($featured, $popular);
            }
            
            return $featured;
        } catch (PDOException $e) {
            error_log("Error getting featured services: " . $e->getMessage());
            return [];
        }
    }
} 