<?php
/**
 * User Model
 */
class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        require_once SRC_PATH . '/database/Database.php';
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
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
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
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
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
            $stmt = $this->db->prepare("SELECT * FROM users WHERE google_id = ? LIMIT 1");
            $stmt->execute([$googleId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding user by Google ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new user
     */
    public function create($userData) {
        try {
            if (empty($userData)) {
                throw new Exception("No user data provided");
            }

            $fields = [];
            $values = [];
            $params = [];
            
            foreach ($userData as $field => $value) {
                if ($value !== null) {
                    $fields[] = $field;
                    $values[] = '?';
                    $params[] = $value;
                }
            }
            
            $sql = "INSERT INTO users (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $values) . ")";
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("SQL Insert: " . $sql);
                error_log("Insert params: " . json_encode($params));
            }
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare SQL statement: " . $this->db->error);
            }

            $result = $stmt->execute($params);
            
            if (!$result) {
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("SQL Error: " . json_encode($stmt->errorInfo()));
                }
                throw new Exception("Failed to execute SQL statement: " . implode(", ", $stmt->errorInfo()));
            }
            
            $lastId = $this->db->lastInsertId();
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Last insert ID: " . $lastId);
            }
            
            return $lastId;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("User data: " . json_encode($userData));
                error_log("Error trace: " . $e->getTraceAsString());
            }
            return false;
        }
    }
    
    /**
     * Update user
     */
    public function update($id, $userData) {
        try {
            if (empty($id) || empty($userData)) {
                throw new Exception("Missing user ID or update data");
            }

            $updates = [];
            $params = [];
            
            foreach ($userData as $field => $value) {
                if ($value !== null) {
                    $updates[] = "$field = ?";
                    $params[] = $value;
                }
            }
            
            $params[] = $id;
            
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("SQL Update: " . $sql);
                error_log("Update params: " . json_encode($params));
            }
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare SQL statement: " . $this->db->error);
            }

            $result = $stmt->execute($params);
            
            if (!$result) {
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("SQL Error: " . json_encode($stmt->errorInfo()));
                }
                throw new Exception("Failed to execute SQL statement: " . implode(", ", $stmt->errorInfo()));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("User ID: " . $id);
                error_log("User data: " . json_encode($userData));
                error_log("Error trace: " . $e->getTraceAsString());
            }
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
} 