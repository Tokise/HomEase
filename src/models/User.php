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
     * Create a new user
     */
    public function create($data) {
        try {
            // Prepare fields and values
            $fields = [];
            $values = [];
            $placeholders = [];
            
            error_log("Creating user with data: " . print_r($data, true));
            
            foreach ($data as $field => $value) {
                if ($value !== null) {  // Only include non-null values
                    $fields[] = $field;
                    $values[] = $value;
                    $placeholders[] = '?';
                    
                    if ($field === 'password') {
                        error_log("Password hash length: " . strlen($value));
                    }
                }
            }
            
            $fieldsStr = implode(', ', $fields);
            $placeholdersStr = implode(', ', $placeholders);
            
            $sql = "INSERT INTO {$this->table} ($fieldsStr) VALUES ($placeholdersStr)";
            error_log("SQL: $sql");
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($values);
            
            if ($success) {
                $userId = $this->db->lastInsertId();
                error_log("User created successfully with ID: $userId");
                return $userId;
            }
            
            error_log("Failed to create user. SQL Error: " . print_r($stmt->errorInfo(), true));
            return false;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            error_log("Data: " . print_r($data, true));
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
            error_log("Error updating user: " . $e->getMessage());
            error_log("Data: " . print_r($data, true));
            return false;
        }
    }
    
    /**
     * Update Google information for user
     */
    public function updateGoogleInfo($id, $googleId, $googleData) {
        try {
            // Log the update operation for debugging
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
     * Get all users
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
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
            $stmt = $this->db->prepare("SELECT * FROM users WHERE role_id = ? ORDER BY created_at DESC");
            $stmt->execute([$roleId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting users by role: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
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
            return $stmt->execute([$userId, $token, $expiry]);
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
                FROM users u 
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
    
    /**
     * Get recent users
     */
    public function getRecent($limit = 5) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting recent users: " . $e->getMessage());
            return [];
        }
    }
} 