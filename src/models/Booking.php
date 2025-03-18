<?php
/**
 * Booking Model
 * Handles database operations for bookings
 */
require_once SRC_PATH . '/database/Database.php';

class Booking {
    private $db;
    private $table = 'bookings';

    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        
        if (!$this->db) {
            throw new Exception("Failed to connect to database");
        }
    }

    /**
     * Get all bookings
     */
    public function getAll() {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       s.price as service_price,
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       u.email as client_email,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name,
                       pu.email as provider_email
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                ORDER BY b.booking_date DESC, b.start_time DESC
            ";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting all bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get booking by ID
     */
    public function findById($id) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       s.price as service_price,
                       s.description as service_description,
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       u.email as client_email,
                       u.phone_number as client_phone,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name,
                       pu.email as provider_email,
                       pu.phone_number as provider_phone
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE b.id = ?
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding booking by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get bookings by client ID
     */
    public function getByClientId($clientId) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       s.price as service_price,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name,
                       pu.email as provider_email
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE b.client_id = ?
                ORDER BY b.booking_date DESC, b.start_time DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$clientId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting bookings by client ID: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get bookings by provider ID
     */
    public function getByProviderId($providerId) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       s.price as service_price,
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       u.email as client_email,
                       u.phone_number as client_phone
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                WHERE b.provider_id = ?
                ORDER BY b.booking_date DESC, b.start_time DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$providerId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting bookings by provider ID: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create booking
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
            error_log("Error creating booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update booking
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
            error_log("Error updating booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete booking
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent bookings
     */
    public function getRecent($limit = 5) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                ORDER BY b.created_at DESC
                LIMIT ?
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting recent bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get bookings by date range
     */
    public function getByDateRange($startDate, $endDate) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE b.booking_date BETWEEN ? AND ?
                ORDER BY b.booking_date ASC, b.start_time ASC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting bookings by date range: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get bookings by status
     */
    public function getByStatus($status) {
        try {
            $sql = "
                SELECT b.*, 
                       s.name as service_name, 
                       u.first_name as client_first_name, 
                       u.last_name as client_last_name,
                       p.user_id as provider_user_id,
                       pu.first_name as provider_first_name,
                       pu.last_name as provider_last_name
                FROM {$this->table} b
                JOIN users u ON b.client_id = u.id
                JOIN services s ON b.service_id = s.id
                JOIN service_providers p ON b.provider_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE b.status = ?
                ORDER BY b.booking_date DESC, b.start_time DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting bookings by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all bookings by client ID
     * 
     * @param int $clientId Client ID
     * @return array Bookings
     */
    public function getBookingsByClient($clientId) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as provider_first_name,
                u.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.provider_id = u.id
                WHERE b.client_id = :client_id
                ORDER BY b.booking_date DESC, b.start_time DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log error
            error_log("Error getting client bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get upcoming bookings by client ID
     * 
     * @param int $clientId Client ID
     * @param int $limit Number of bookings to return
     * @return array Bookings
     */
    public function getUpcomingBookingsByClient($clientId, $limit = 5) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as provider_first_name,
                u.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.provider_id = u.id
                WHERE b.client_id = :client_id
                AND (b.booking_date > CURDATE() OR 
                    (b.booking_date = CURDATE() AND b.start_time >= CURTIME()))
                ORDER BY b.booking_date ASC, b.start_time ASC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log error
            error_log("Error getting upcoming client bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent bookings by client ID
     * 
     * @param int $clientId Client ID
     * @param int $limit Number of bookings to return
     * @return array Bookings
     */
    public function getRecentBookingsByClient($clientId, $limit = 5) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as provider_first_name,
                u.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.provider_id = u.id
                WHERE b.client_id = :client_id
                AND (b.booking_date < CURDATE() OR 
                    (b.booking_date = CURDATE() AND b.start_time < CURTIME()))
                ORDER BY b.booking_date DESC, b.start_time DESC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log error
            error_log("Error getting recent client bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all bookings by provider ID
     * 
     * @param int $providerId Provider ID
     * @return array Bookings
     */
    public function getBookingsByProvider($providerId) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as client_first_name,
                u.last_name as client_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.client_id = u.id
                WHERE b.provider_id = :provider_id
                ORDER BY b.booking_date DESC, b.start_time DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log error
            error_log("Error getting provider bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get upcoming bookings by provider ID
     * 
     * @param int $providerId Provider ID
     * @param int $limit Number of bookings to return
     * @return array Bookings
     */
    public function getUpcomingBookingsByProvider($providerId, $limit = 5) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as client_first_name,
                u.last_name as client_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.client_id = u.id
                WHERE b.provider_id = :provider_id
                AND (b.booking_date > CURDATE() OR 
                    (b.booking_date = CURDATE() AND b.start_time >= CURTIME()))
                ORDER BY b.booking_date ASC, b.start_time ASC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log error
            error_log("Error getting upcoming provider bookings: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status New status
     * @return bool Success or failure
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // Log error
            error_log("Error updating booking status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update payment status
     * 
     * @param int $id Booking ID
     * @param string $status New payment status
     * @return bool Success or failure
     */
    public function updatePaymentStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET payment_status = :status, updated_at = NOW() WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // Log error
            error_log("Error updating payment status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get upcoming bookings for a user
     * 
     * @param int $userId User ID
     * @param int $limit Limit number of results
     * @return array Array of upcoming bookings
     */
    public function getUpcomingBookings($userId, $limit = 5) {
        $sql = "SELECT b.*, 
                s.name as service_name,
                p.first_name as provider_first_name,
                p.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users p ON b.provider_id = p.id
                WHERE b.client_id = :user_id
                AND (b.booking_date > CURDATE() 
                    OR (b.booking_date = CURDATE() AND b.start_time >= CURTIME()))
                AND b.status != 'cancelled'
                ORDER BY b.booking_date ASC, b.start_time ASC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting upcoming bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent bookings for a user
     * 
     * @param int $userId User ID
     * @param int $limit Limit number of results
     * @return array Array of recent bookings
     */
    public function getRecentBookings($userId, $limit = 5) {
        $sql = "SELECT b.*, 
                s.name as service_name,
                p.first_name as provider_first_name,
                p.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users p ON b.provider_id = p.id
                WHERE b.client_id = :user_id
                AND (b.booking_date < CURDATE() 
                    OR (b.booking_date = CURDATE() AND b.start_time < CURTIME()))
                ORDER BY b.booking_date DESC, b.start_time DESC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all bookings for a user
     * 
     * @param int $userId User ID
     * @return array Array of all bookings
     */
    public function getAllBookings($userId) {
        $sql = "SELECT b.*, 
                s.name as service_name,
                p.first_name as provider_first_name,
                p.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users p ON b.provider_id = p.id
                WHERE b.client_id = :user_id
                ORDER BY b.booking_date DESC, b.start_time DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all bookings: " . $e->getMessage());
            return [];
        }
    }
} 