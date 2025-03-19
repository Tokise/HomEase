<?php
/**
 * User Model
 */
class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        require_once __DIR__ . '/../database/Database.php';
        $this->db = Database::getInstance()->getConnection();
        
        if (!$this->db) {
            throw new Exception("Failed to connect to database");
        }
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by Google ID
     */
    public function findByGoogleId($googleId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE google_id = ?");
            $stmt->execute([$googleId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding user by Google ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users
     * 
     * @return array Array of all users
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total number of users
     * 
     * @return int Total number of users
     */
    public function getTotalUsers() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total users: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get total number of service providers
     * 
     * @return int Total number of providers
     */
    public function getTotalProviders() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE role_id = ?");
            $stmt->execute([ROLE_PROVIDER]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total providers: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get recent users
     * 
     * @param int $limit Number of users to return
     * @return array Array of recent users
     */
    public function getRecentUsers($limit = 5) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting recent users: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all service providers
     * 
     * @return array Array of all service providers
     */
    public function getAllProviders() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role_id = ? ORDER BY created_at DESC");
            $stmt->execute([ROLE_PROVIDER]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all providers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get top service providers by rating
     * 
     * @param int $limit Number of providers to return
     * @return array Array of top providers
     */
    public function getTopProviders($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.*, 
                       COUNT(b.id) as booking_count, 
                       AVG(r.rating) as avg_rating
                FROM {$this->table} u
                LEFT JOIN services s ON u.id = s.provider_id
                LEFT JOIN bookings b ON s.id = b.service_id
                LEFT JOIN reviews r ON b.id = r.booking_id
                WHERE u.role_id = ?
                GROUP BY u.id
                ORDER BY avg_rating DESC, booking_count DESC
                LIMIT ?
            ");
            $stmt->bindValue(1, ROLE_PROVIDER, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting top providers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get the last database error
     * 
     * @return array|null The last error info or null if no error
     */
    public function getLastError() {
        if ($this->db) {
            return $this->db->errorInfo();
        }
        return null;
    }
    
    /**
     * Create a new user
     */
    public function create($data) {
        try {
            // Set default values for required fields
            $data['is_active'] = $data['is_active'] ?? 1;
            $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');
            
            // Prepare fields and values
            $fields = [];
            $values = [];
            $placeholders = [];
            
            foreach ($data as $field => $value) {
                if ($value !== null) {  // Only include non-null values
                    $fields[] = "`$field`"; // Escape field names
                    $values[] = $value;
                    $placeholders[] = '?';
                }
            }
            
            $fieldsStr = implode(', ', $fields);
            $placeholdersStr = implode(', ', $placeholders);
            
            $sql = "INSERT INTO {$this->table} ($fieldsStr) VALUES ($placeholdersStr)";
            
            if (DEBUG_MODE) {
                error_log("Creating user with SQL: " . $sql);
                error_log("Values: " . print_r($values, true));
            }
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($values);
            
            if ($success) {
                $userId = $this->db->lastInsertId();
                if (DEBUG_MODE) {
                    error_log("User created successfully with ID: $userId");
                }
                return $userId;
            }
            
            if (DEBUG_MODE) {
                error_log("Failed to create user. SQL Error: " . print_r($stmt->errorInfo(), true));
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            if (DEBUG_MODE) {
                error_log("Stack trace: " . $e->getTraceAsString());
                error_log("Data: " . print_r($data, true));
            }
            return false;
        }
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        try {
            $sets = [];
            $values = [];
            
            foreach ($data as $field => $value) {
                if ($value !== null) {  // Only update non-null values
                    $sets[] = "`$field` = ?";
                    $values[] = $value;
                }
            }
            
            $values[] = $id;  // Add ID for WHERE clause
            $setsStr = implode(', ', $sets);
            
            $sql = "UPDATE {$this->table} SET $setsStr WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            if (DEBUG_MODE) {
                error_log("Data: " . print_r($data, true));
            }
            return false;
        }
    }
    
    /**
     * Update Google information for user
     */
    public function updateGoogleInfo($id, $googleId, $googleData) {
        try {
            if (DEBUG_MODE) {
                error_log("Updating Google info for user $id with Google ID $googleId");
                error_log("Google data: " . print_r($googleData, true));
            }
            
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET google_id = ?, 
                    google_picture = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            return $stmt->execute([$googleId, $googleData['picture'] ?? null, $id]);
        } catch (PDOException $e) {
            error_log("Error updating Google info: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get users by role
     */
    public function getByRole($roleId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role_id = ? ORDER BY created_at DESC");
            $stmt->execute([$roleId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting users by role: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Store remember token
     */
    public function storeRememberToken($userId, $token, $expiry) {
        try {
            // First remove any existing tokens for this user
            $this->removeRememberToken($userId);
            
            $stmt = $this->db->prepare("INSERT INTO user_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $token, date('Y-m-d H:i:s', $expiry)]);
        } catch (PDOException $e) {
            error_log("Error storing remember token: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove remember token
     */
    public function removeRememberToken($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_tokens WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Error removing remember token: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user by remember token
     */
    public function getUserByRememberToken($token) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.* 
                FROM {$this->table} u 
                JOIN user_tokens t ON u.id = t.user_id 
                WHERE t.token = ? AND t.expiry > NOW()
                LIMIT 1
            ");
            $stmt->execute([$token]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error getting user by remember token: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clean expired tokens
     */
    public function cleanExpiredTokens() {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_tokens WHERE expiry <= NOW()");
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error cleaning expired tokens: " . $e->getMessage());
            return false;
        }
    }
}