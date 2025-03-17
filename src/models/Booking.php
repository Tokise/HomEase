<?php

namespace App\Models;

use App\Database\Database;
use PDO;

/**
 * Booking Model
 * Handles database operations for bookings
 */
class Booking {
    private $db;
    private $table = 'bookings';

    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Create a new booking
     * 
     * @param array $data Booking data
     * @return int|bool The ID of the created booking or false on failure
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (
                    client_id, 
                    service_id, 
                    provider_id, 
                    booking_date, 
                    start_time, 
                    end_time, 
                    status, 
                    total_amount, 
                    payment_status, 
                    notes, 
                    created_at
                ) VALUES (
                    :client_id, 
                    :service_id, 
                    :provider_id, 
                    :booking_date, 
                    :start_time, 
                    :end_time, 
                    :status, 
                    :total_amount, 
                    :payment_status, 
                    :notes, 
                    NOW()
                )";
        
        try {
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':client_id', $data['client_id'], PDO::PARAM_INT);
            $stmt->bindValue(':service_id', $data['service_id'], PDO::PARAM_INT);
            $stmt->bindValue(':provider_id', $data['provider_id'], PDO::PARAM_INT);
            $stmt->bindValue(':booking_date', $data['booking_date']);
            $stmt->bindValue(':start_time', $data['start_time']);
            $stmt->bindValue(':end_time', $data['end_time']);
            $stmt->bindValue(':status', $data['status'] ?? 'pending');
            $stmt->bindValue(':total_amount', $data['total_amount'], PDO::PARAM_STR);
            $stmt->bindValue(':payment_status', $data['payment_status'] ?? 'unpaid');
            $stmt->bindValue(':notes', $data['notes'] ?? null);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Log error
            error_log("Error creating booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a booking
     * 
     * @param int $id Booking ID
     * @param array $data Booking data
     * @return bool Success or failure
     */
    public function update($id, $data) {
        $setClause = [];
        $params = [':id' => $id];
        
        // Build set clause dynamically based on provided data
        foreach ($data as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        $setClauseStr = implode(', ', $setClause);
        
        $sql = "UPDATE {$this->table} SET {$setClauseStr}, updated_at = NOW() WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // Log error
            error_log("Error updating booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a booking
     * 
     * @param int $id Booking ID
     * @return bool Success or failure
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // Log error
            error_log("Error deleting booking: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a booking by ID
     * 
     * @param int $id Booking ID
     * @return array|bool Booking data or false if not found
     */
    public function findById($id) {
        $sql = "SELECT b.*, 
                s.name as service_name, 
                u.first_name as client_first_name,
                u.last_name as client_last_name,
                p.first_name as provider_first_name,
                p.last_name as provider_last_name
                FROM {$this->table} b
                JOIN services s ON b.service_id = s.id
                JOIN users u ON b.client_id = u.id
                JOIN users p ON b.provider_id = p.id
                WHERE b.id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            return $booking ?: false;
        } catch (\PDOException $e) {
            // Log error
            error_log("Error finding booking: " . $e->getMessage());
            return false;
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
} 