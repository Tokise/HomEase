<?php
/**
 * Service Category Model
 */
class ServiceCategory {
    private $db;
    private $pdo;
    
    public function __construct() {
        require_once SRC_PATH . '/database/Database.php';
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }
    
    /**
     * Get all categories
     */
    public function getAll() {
        try {
            $stmt = $this->pdo->query("
                SELECT * FROM service_categories 
                WHERE is_active = TRUE 
                ORDER BY name ASC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get category by ID
     */
    public function findById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM service_categories 
                WHERE id = :id 
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding category by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new category
     */
    public function create($categoryData) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO service_categories (name, description, icon, is_active) 
                VALUES (:name, :description, :icon, :is_active)
            ");
            
            $stmt->execute([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'] ?? '',
                'icon' => $categoryData['icon'] ?? null,
                'is_active' => isset($categoryData['is_active']) ? $categoryData['is_active'] : true
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating category: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing category
     */
    public function update($id, $categoryData) {
        try {
            $updates = [];
            $params = ['id' => $id];
            
            if (isset($categoryData['name'])) {
                $updates[] = "name = :name";
                $params['name'] = $categoryData['name'];
            }
            
            if (isset($categoryData['description'])) {
                $updates[] = "description = :description";
                $params['description'] = $categoryData['description'];
            }
            
            if (isset($categoryData['icon'])) {
                $updates[] = "icon = :icon";
                $params['icon'] = $categoryData['icon'];
            }
            
            if (isset($categoryData['is_active'])) {
                $updates[] = "is_active = :is_active";
                $params['is_active'] = $categoryData['is_active'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE service_categories 
                SET " . implode(', ', $updates) . " 
                WHERE id = :id
            ");
            
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating category: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a category
     */
    public function delete($id) {
        try {
            // Check if there are services in this category
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count 
                FROM services 
                WHERE category_id = :id
            ");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            
            if ($row['count'] > 0) {
                return false; // Cannot delete category with services
            }
            
            $stmt = $this->pdo->prepare("
                DELETE FROM service_categories 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get categories with service count
     */
    public function getAllWithServiceCount() {
        try {
            $stmt = $this->pdo->query("
                SELECT c.*, COUNT(s.id) as service_count 
                FROM service_categories c
                LEFT JOIN services s ON c.id = s.category_id
                WHERE c.is_active = TRUE
                GROUP BY c.id
                ORDER BY c.name ASC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting categories with service count: " . $e->getMessage());
            return [];
        }
    }
} 