<?php
/**
 * Service Model
 */
require_once SRC_PATH . '/models/Model.php';

class Service extends Model {
    protected $table = 'services';
    
    public function __construct() {
        parent::__construct();
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
            $query = "SELECT s.*, u.first_name as provider_first_name, 
                            u.last_name as provider_last_name,
                            u.profile_picture as provider_picture,
                            u.rating as provider_rating,
                            c.name as category_name
                     FROM {$this->table} s
                     LEFT JOIN users u ON s.provider_id = u.id
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     WHERE s.category_id = :category_id
                     AND s.is_active = 1
                     ORDER BY u.rating DESC, s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching services by category: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create service
     */
    public function create($data) {
        try {
            $query = "INSERT INTO {$this->table} (provider_id, category_id, name, description, 
                                                price, duration, is_active, created_at, updated_at)
                     VALUES (:provider_id, :category_id, :name, :description, 
                            :price, :duration, :is_active, NOW(), NOW())";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':provider_id', $data['provider_id'], PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(':duration', $data['duration'], PDO::PARAM_INT);
            $stmt->bindValue(':is_active', 1, PDO::PARAM_INT);
            
            $success = $stmt->execute();
            
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
     * Basic search services
     */
    public function basicSearch($keyword) {
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
     * Get all services with their categories and providers
     */
    public function getAllWithDetails() {
        try {
            $query = "SELECT s.*, c.name as category_name, 
                            u.first_name as provider_first_name, 
                            u.last_name as provider_last_name
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     LEFT JOIN users u ON s.provider_id = u.id
                     ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching services: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get provider's services
     */
    public function getProviderServices($providerId) {
        try {
            $query = "SELECT s.*, c.name as category_name,
                            COALESCE((SELECT COUNT(*) FROM bookings b WHERE b.service_id = s.id), 0) as booking_count,
                            COALESCE((SELECT AVG(r.rating) FROM reviews r WHERE r.service_id = s.id), 0) as rating
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     WHERE s.provider_id = :provider_id
                     ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching provider services: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get provider's active services
     */
    public function getProviderActiveServices($providerId) {
        try {
            $query = "SELECT s.*, c.name as category_name
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     WHERE s.provider_id = :provider_id
                     AND s.is_active = 1
                     ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching services by category: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get featured services
     */
    public function getFeatured($limit = 6) {
        try {
            $query = "SELECT s.*, c.name as category_name,
                            u.first_name as provider_first_name,
                            u.last_name as provider_last_name,
                            u.profile_picture as provider_picture,
                            u.rating as provider_rating
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     LEFT JOIN users u ON s.provider_id = u.id
                     WHERE s.is_active = 1
                     ORDER BY u.rating DESC, s.booking_count DESC
                     LIMIT :limit";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching featured services: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top services
     */
    public function getTopServices($limit = 5) {
        try {
            $query = "SELECT s.*, c.name as category_name,
                            COUNT(b.id) as booking_count,
                            SUM(b.total_price) as total_revenue
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     LEFT JOIN bookings b ON s.id = b.service_id
                     WHERE s.is_active = 1
                     GROUP BY s.id
                     ORDER BY booking_count DESC
                     LIMIT :limit";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching top services: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Get all service categories
     */
    public function getAllCategories() {
        try {
            $query = "SELECT * FROM service_categories ORDER BY name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching service categories: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a service category
     */
    public function createCategory($data) {
        try {
            $query = "INSERT INTO service_categories (name, description, is_active, created_at, updated_at)
                     VALUES (:name, :description, 1, NOW(), NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating service category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a service category
     */
    public function updateCategory($categoryId, $data) {
        try {
            $query = "UPDATE service_categories 
                     SET name = :name,
                         description = :description,
                         is_active = :is_active,
                         updated_at = NOW()
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating service category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Search services
     */
    public function search($keyword, $categoryId = null) {
        try {
            $query = "SELECT s.*, c.name as category_name,
                            u.first_name as provider_first_name,
                            u.last_name as provider_last_name,
                            u.profile_picture as provider_picture,
                            u.rating as provider_rating
                     FROM {$this->table} s
                     LEFT JOIN service_categories c ON s.category_id = c.id
                     LEFT JOIN users u ON s.provider_id = u.id
                     WHERE s.is_active = 1
                     AND (s.name LIKE :keyword 
                          OR s.description LIKE :keyword 
                          OR c.name LIKE :keyword)";
            
            if ($categoryId) {
                $query .= " AND s.category_id = :category_id";
            }
            
            $query .= " ORDER BY u.rating DESC, s.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
            
            if ($categoryId) {
                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching services: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Add provider availability
     */
    public function addAvailability($providerId, $data) {
        try {
            $query = "INSERT INTO provider_availability 
                     (provider_id, day_of_week, start_time, end_time, is_available)
                     VALUES (:provider_id, :day_of_week, :start_time, :end_time, :is_available)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->bindParam(':day_of_week', $data['day_of_week'], PDO::PARAM_INT);
            $stmt->bindParam(':start_time', $data['start_time']);
            $stmt->bindParam(':end_time', $data['end_time']);
            $stmt->bindValue(':is_available', 1, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding availability: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get provider availability
     */
    public function getProviderAvailability($providerId) {
        try {
            $query = "SELECT * FROM provider_availability 
                     WHERE provider_id = :provider_id 
                     ORDER BY day_of_week, start_time";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':provider_id', $providerId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting availability: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check service availability for a specific date and time
     */
    public function checkAvailability($serviceId, $date, $time) {
        try {
            // First check if the provider is available on this day/time
            $query = "SELECT pa.* FROM provider_availability pa
                     JOIN services s ON s.provider_id = pa.provider_id
                     WHERE s.id = :service_id
                     AND pa.day_of_week = WEEKDAY(:date)
                     AND :time BETWEEN pa.start_time AND pa.end_time
                     AND pa.is_available = 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                return false; // Provider not available at this time
            }

            // Then check if there are any overlapping bookings
            $query = "SELECT COUNT(*) FROM bookings b
                     WHERE b.service_id = :service_id
                     AND b.booking_date = :date
                     AND b.status != 'cancelled'
                     AND :time BETWEEN b.start_time 
                     AND TIME(ADDTIME(b.start_time, SEC_TO_TIME(s.duration * 60)))";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->execute();
            
            return $stmt->fetchColumn() == 0;
        } catch (PDOException $e) {
            error_log("Error checking availability: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available time slots for a service on a specific date
     */
    public function getAvailableTimeSlots($serviceId, $date) {
        try {
            // Get service duration and provider availability
            $query = "SELECT s.duration, pa.start_time, pa.end_time 
                     FROM services s
                     JOIN provider_availability pa ON s.provider_id = pa.provider_id
                     WHERE s.id = :service_id
                     AND pa.day_of_week = WEEKDAY(:date)
                     AND pa.is_available = 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            
            $availability = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$availability) {
                return [];
            }

            // Get booked slots
            $query = "SELECT start_time 
                     FROM bookings 
                     WHERE service_id = :service_id 
                     AND booking_date = :date
                     AND status != 'cancelled'";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            
            $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Generate available time slots
            $slots = [];
            $current = strtotime($availability['start_time']);
            $end = strtotime($availability['end_time']);
            $duration = $availability['duration'] * 60; // Convert to seconds

            while ($current + $duration <= $end) {
                $timeSlot = date('H:i:s', $current);
                if (!in_array($timeSlot, $bookedSlots)) {
                    $slots[] = $timeSlot;
                }
                $current += $duration;
            }

            return $slots;
        } catch (PDOException $e) {
            error_log("Error getting available time slots: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available dates for the next 30 days
     */
    public function getAvailableDates($serviceId) {
        try {
            $dates = [];
            $start = strtotime('today');
            $end = strtotime('+30 days');
            
            while ($start <= $end) {
                $date = date('Y-m-d', $start);
                if ($this->hasAvailableSlots($serviceId, $date)) {
                    $dates[] = $date;
                }
                $start = strtotime('+1 day', $start);
            }
            
            return $dates;
        } catch (PDOException $e) {
            error_log("Error getting available dates: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if a service has available slots on a specific date
     */
    private function hasAvailableSlots($serviceId, $date) {
        try {
            // Get provider's availability for the day
            $dayOfWeek = date('w', strtotime($date));
            $query = "SELECT pa.* FROM provider_availability pa
                     JOIN services s ON s.provider_id = pa.provider_id
                     WHERE s.id = :service_id
                     AND pa.day_of_week = :day_of_week
                     AND pa.is_available = 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':day_of_week', $dayOfWeek, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error checking available slots: " . $e->getMessage());
            return false;
        }
    }
}