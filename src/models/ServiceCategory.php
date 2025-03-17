<?php
/**
 * Service Category Model
 */
class ServiceCategory {
    private $db;
    
    public function __construct() {
        require_once SRC_PATH . '/utils/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all categories
     */
    public function getAll() {
        $result = $this->db->query("
            SELECT * FROM service_categories 
            WHERE is_active = TRUE 
            ORDER BY name ASC
        ");
        
        $categories = [];
        
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    /**
     * Get category by ID
     */
    public function findById($id) {
        $id = $this->db->escapeString($id);
        $result = $this->db->query("
            SELECT * FROM service_categories 
            WHERE id = {$id} 
            LIMIT 1
        ");
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Create a new category
     */
    public function create($categoryData) {
        $name = $this->db->escapeString($categoryData['name']);
        $description = $this->db->escapeString($categoryData['description'] ?? '');
        $icon = isset($categoryData['icon']) ? "'" . $this->db->escapeString($categoryData['icon']) . "'" : 'NULL';
        $isActive = isset($categoryData['is_active']) ? ($categoryData['is_active'] ? 'TRUE' : 'FALSE') : 'TRUE';
        
        $query = "INSERT INTO service_categories (name, description, icon, is_active) 
                  VALUES ('{$name}', '{$description}', {$icon}, {$isActive})";
        
        $this->db->query($query);
        return $this->db->getLastInsertId();
    }
    
    /**
     * Update an existing category
     */
    public function update($id, $categoryData) {
        $sets = [];
        
        if (isset($categoryData['name'])) {
            $sets[] = "name = '" . $this->db->escapeString($categoryData['name']) . "'";
        }
        
        if (isset($categoryData['description'])) {
            $sets[] = "description = '" . $this->db->escapeString($categoryData['description']) . "'";
        }
        
        if (isset($categoryData['icon'])) {
            $sets[] = "icon = '" . $this->db->escapeString($categoryData['icon']) . "'";
        }
        
        if (isset($categoryData['is_active'])) {
            $sets[] = "is_active = " . ($categoryData['is_active'] ? 'TRUE' : 'FALSE');
        }
        
        if (empty($sets)) {
            return false;
        }
        
        $query = "UPDATE service_categories SET " . implode(', ', $sets) . " WHERE id = " . (int)$id;
        $this->db->query($query);
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Delete a category
     */
    public function delete($id) {
        $id = (int)$id;
        
        // Check if there are services in this category
        $result = $this->db->query("SELECT COUNT(*) as count FROM services WHERE category_id = {$id}");
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            return false; // Cannot delete category with services
        }
        
        $this->db->query("DELETE FROM service_categories WHERE id = {$id}");
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Get categories with service count
     */
    public function getAllWithServiceCount() {
        $result = $this->db->query("
            SELECT c.*, COUNT(s.id) as service_count 
            FROM service_categories c
            LEFT JOIN services s ON c.id = s.category_id
            WHERE c.is_active = TRUE
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
        
        $categories = [];
        
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
} 