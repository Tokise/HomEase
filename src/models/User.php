<?php
/**
 * User Model
 */
class User {
    private $db;
    
    public function __construct() {
        require_once SRC_PATH . '/utils/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $id = $this->db->escapeString($id);
        $result = $this->db->query("SELECT * FROM users WHERE id = {$id} LIMIT 1");
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $email = $this->db->escapeString($email);
        $result = $this->db->query("SELECT * FROM users WHERE email = '{$email}' LIMIT 1");
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Find user by Google ID
     */
    public function findByGoogleId($googleId) {
        $googleId = $this->db->escapeString($googleId);
        $result = $this->db->query("SELECT * FROM users WHERE google_id = '{$googleId}' LIMIT 1");
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Create new user
     */
    public function create($userData) {
        try {
            // Begin transaction for data integrity
            $this->db->beginTransaction();
            
            $email = $this->db->escapeString($userData['email']);
            $firstName = $this->db->escapeString($userData['first_name']);
            $lastName = $this->db->escapeString($userData['last_name']);
            $googleId = isset($userData['google_id']) ? "'" . $this->db->escapeString($userData['google_id']) . "'" : 'NULL';
            $profilePicture = isset($userData['profile_picture']) ? "'" . $this->db->escapeString($userData['profile_picture']) . "'" : 'NULL';
            $password = isset($userData['password']) ? "'" . $this->db->escapeString($userData['password']) . "'" : 'NULL';
            $phone = isset($userData['phone_number']) ? "'" . $this->db->escapeString($userData['phone_number']) . "'" : 'NULL';
            $roleId = isset($userData['role_id']) ? (int)$userData['role_id'] : ROLE_CLIENT;
            
            $query = "INSERT INTO users (email, first_name, last_name, google_id, profile_picture, password, phone_number, role_id, created_at) 
                    VALUES ('{$email}', '{$firstName}', '{$lastName}', {$googleId}, {$profilePicture}, {$password}, {$phone}, {$roleId}, NOW())";
            
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("User create query: " . $query);
            }
            
            try {
                $result = $this->db->query($query);
                $newId = $this->db->getLastInsertId();
                
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("User created with ID: " . $newId);
                }
                
                if ($newId) {
                    // Commit transaction
                    $this->db->commit();
                    return $newId;
                } else {
                    // Rollback on failure
                    $this->db->rollback();
                    error_log("Failed to get insert ID after user creation");
                    return false;
                }
            } catch (Exception $e) {
                // Rollback on exception
                $this->db->rollback();
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("Error creating user: " . $e->getMessage());
                }
                return false;
            }
        } catch (Exception $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Exception in user creation: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Update existing user
     */
    public function update($id, $userData) {
        $sets = [];
        
        if (isset($userData['email'])) {
            $sets[] = "email = '" . $this->db->escapeString($userData['email']) . "'";
        }
        
        if (isset($userData['first_name'])) {
            $sets[] = "first_name = '" . $this->db->escapeString($userData['first_name']) . "'";
        }
        
        if (isset($userData['last_name'])) {
            $sets[] = "last_name = '" . $this->db->escapeString($userData['last_name']) . "'";
        }
        
        if (isset($userData['profile_picture'])) {
            $sets[] = "profile_picture = '" . $this->db->escapeString($userData['profile_picture']) . "'";
        }
        
        if (isset($userData['phone_number'])) {
            $sets[] = "phone_number = '" . $this->db->escapeString($userData['phone_number']) . "'";
        }
        
        if (isset($userData['address'])) {
            $sets[] = "address = '" . $this->db->escapeString($userData['address']) . "'";
        }
        
        if (isset($userData['city'])) {
            $sets[] = "city = '" . $this->db->escapeString($userData['city']) . "'";
        }
        
        if (isset($userData['state'])) {
            $sets[] = "state = '" . $this->db->escapeString($userData['state']) . "'";
        }
        
        if (isset($userData['postal_code'])) {
            $sets[] = "postal_code = '" . $this->db->escapeString($userData['postal_code']) . "'";
        }
        
        if (isset($userData['country'])) {
            $sets[] = "country = '" . $this->db->escapeString($userData['country']) . "'";
        }
        
        if (isset($userData['is_active'])) {
            $sets[] = "is_active = " . ($userData['is_active'] ? 'TRUE' : 'FALSE');
        }
        
        if (isset($userData['google_id'])) {
            $sets[] = "google_id = '" . $this->db->escapeString($userData['google_id']) . "'";
        }
        
        if (isset($userData['password'])) {
            $sets[] = "password = '" . $this->db->escapeString($userData['password']) . "'";
        }
        
        if (empty($sets)) {
            return false;
        }
        
        $query = "UPDATE users SET " . implode(', ', $sets) . ", updated_at = NOW() WHERE id = " . (int)$id;
        $this->db->query($query);
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Get all users
     */
    public function getAll() {
        $result = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    /**
     * Get users by role
     */
    public function getByRole($roleId) {
        $roleId = (int)$roleId;
        $result = $this->db->query("SELECT * FROM users WHERE role_id = {$roleId} ORDER BY id DESC");
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    /**
     * Delete a user
     */
    public function delete($id) {
        $id = (int)$id;
        $this->db->query("DELETE FROM users WHERE id = {$id}");
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Store remember me token
     */
    public function storeRememberToken($userId, $token, $expiry) {
        $userId = (int)$userId;
        $token = $this->db->escapeString($token);
        $expiry = date('Y-m-d H:i:s', $expiry);
        
        // Remove any existing tokens for this user
        $this->removeRememberToken($userId);
        
        // Store new token
        $query = "INSERT INTO user_tokens (user_id, token, expiry, created_at) 
                 VALUES ({$userId}, '{$token}', '{$expiry}', NOW())";
        
        return $this->db->query($query);
    }
    
    /**
     * Remove remember me token
     */
    public function removeRememberToken($userId) {
        $userId = (int)$userId;
        $query = "DELETE FROM user_tokens WHERE user_id = {$userId}";
        
        return $this->db->query($query);
    }
    
    /**
     * Get user by remember token
     */
    public function getUserByRememberToken($token) {
        $token = $this->db->escapeString($token);
        $now = date('Y-m-d H:i:s');
        
        $query = "SELECT u.* FROM users u 
                 JOIN user_tokens t ON u.id = t.user_id 
                 WHERE t.token = '{$token}' AND t.expiry > '{$now}' 
                 LIMIT 1";
        
        $result = $this->db->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Clean expired tokens
     */
    public function cleanExpiredTokens() {
        $now = date('Y-m-d H:i:s');
        $query = "DELETE FROM user_tokens WHERE expiry <= '{$now}'";
        
        return $this->db->query($query);
    }
} 