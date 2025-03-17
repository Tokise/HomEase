<?php
/**
 * Service Provider Model
 */
class ServiceProvider {
    private $db;
    
    public function __construct() {
        require_once SRC_PATH . '/utils/Database.php';
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all active service providers
     */
    public function getAllActive() {
        $result = $this->db->query("
            SELECT * FROM service_providers 
            WHERE is_active = TRUE 
            ORDER BY business_name ASC
        ");
        
        $providers = [];
        
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }
        
        return $providers;
    }
    
    /**
     * Get provider by ID
     */
    public function findById($id) {
        $id = $this->db->escapeString($id);
        $result = $this->db->query("
            SELECT * FROM service_providers 
            WHERE id = {$id} 
            LIMIT 1
        ");
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Get providers by service category
     */
    public function getByCategory($categoryId) {
        $categoryId = (int)$categoryId;
        $result = $this->db->query("
            SELECT DISTINCT sp.* 
            FROM service_providers sp
            JOIN provider_services ps ON sp.id = ps.provider_id
            JOIN services s ON ps.service_id = s.id
            WHERE s.category_id = {$categoryId} AND sp.is_active = TRUE
            ORDER BY sp.business_name ASC
        ");
        
        $providers = [];
        
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }
        
        return $providers;
    }
    
    /**
     * Find providers within a radius (in km) of coordinates
     * Uses the Haversine formula to calculate distance
     */
    public function findInRadius($lat, $lng, $radius = 10, $categoryId = null) {
        $lat = (float)$lat;
        $lng = (float)$lng;
        $radius = (float)$radius;
        
        // Haversine formula SQL
        $haversine = "
            (6371 * acos(
                cos(radians({$lat})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$lng})) + 
                sin(radians({$lat})) * sin(radians(latitude))
            ))";
        
        $categoryFilter = '';
        if ($categoryId !== null) {
            $categoryId = (int)$categoryId;
            $categoryFilter = "
                AND sp.id IN (
                    SELECT DISTINCT ps.provider_id 
                    FROM provider_services ps
                    JOIN services s ON ps.service_id = s.id
                    WHERE s.category_id = {$categoryId}
                )
            ";
        }
        
        $query = "
            SELECT sp.*, {$haversine} as distance
            FROM service_providers sp
            WHERE sp.is_active = TRUE AND sp.latitude IS NOT NULL AND sp.longitude IS NOT NULL
            {$categoryFilter}
            HAVING distance <= {$radius}
            ORDER BY distance ASC
        ";
        
        $result = $this->db->query($query);
        
        $providers = [];
        
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }
        
        return $providers;
    }
    
    /**
     * Get provider services
     */
    public function getServices($providerId) {
        $providerId = (int)$providerId;
        $result = $this->db->query("
            SELECT s.*, ps.price as provider_price, ps.duration as provider_duration 
            FROM services s
            JOIN provider_services ps ON s.id = ps.service_id
            WHERE ps.provider_id = {$providerId}
            ORDER BY s.name ASC
        ");
        
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        return $services;
    }
    
    /**
     * Create a new service provider
     */
    public function create($providerData) {
        $userId = (int)$providerData['user_id'];
        $businessName = $this->db->escapeString($providerData['business_name']);
        $description = $this->db->escapeString($providerData['description'] ?? '');
        $address = $this->db->escapeString($providerData['address'] ?? '');
        $lat = isset($providerData['latitude']) && $providerData['latitude'] !== '' ? (float)$providerData['latitude'] : 'NULL';
        $lng = isset($providerData['longitude']) && $providerData['longitude'] !== '' ? (float)$providerData['longitude'] : 'NULL';
        $phone = $this->db->escapeString($providerData['phone'] ?? '');
        $website = isset($providerData['website']) ? "'" . $this->db->escapeString($providerData['website']) . "'" : 'NULL';
        $logo = isset($providerData['logo']) ? "'" . $this->db->escapeString($providerData['logo']) . "'" : 'NULL';
        $isActive = isset($providerData['is_active']) ? ($providerData['is_active'] ? 'TRUE' : 'FALSE') : 'TRUE';
        
        $latValue = $lat === 'NULL' ? 'NULL' : $lat;
        $lngValue = $lng === 'NULL' ? 'NULL' : $lng;
        
        $query = "INSERT INTO service_providers 
                 (user_id, business_name, description, address, latitude, longitude, phone, website, logo, is_active) 
                 VALUES 
                 ({$userId}, '{$businessName}', '{$description}', '{$address}', {$latValue}, {$lngValue}, '{$phone}', {$website}, {$logo}, {$isActive})";
        
        $this->db->query($query);
        return $this->db->getLastInsertId();
    }
    
    /**
     * Update an existing service provider
     */
    public function update($id, $providerData) {
        $sets = [];
        
        if (isset($providerData['user_id'])) {
            $sets[] = "user_id = " . (int)$providerData['user_id'];
        }
        
        if (isset($providerData['business_name'])) {
            $sets[] = "business_name = '" . $this->db->escapeString($providerData['business_name']) . "'";
        }
        
        if (isset($providerData['description'])) {
            $sets[] = "description = '" . $this->db->escapeString($providerData['description']) . "'";
        }
        
        if (isset($providerData['address'])) {
            $sets[] = "address = '" . $this->db->escapeString($providerData['address']) . "'";
        }
        
        if (isset($providerData['latitude']) && $providerData['latitude'] !== '') {
            $sets[] = "latitude = " . (float)$providerData['latitude'];
        } elseif (array_key_exists('latitude', $providerData) && ($providerData['latitude'] === '' || $providerData['latitude'] === null)) {
            $sets[] = "latitude = NULL";
        }
        
        if (isset($providerData['longitude']) && $providerData['longitude'] !== '') {
            $sets[] = "longitude = " . (float)$providerData['longitude'];
        } elseif (array_key_exists('longitude', $providerData) && ($providerData['longitude'] === '' || $providerData['longitude'] === null)) {
            $sets[] = "longitude = NULL";
        }
        
        if (isset($providerData['phone'])) {
            $sets[] = "phone = '" . $this->db->escapeString($providerData['phone']) . "'";
        }
        
        if (isset($providerData['website'])) {
            $sets[] = "website = '" . $this->db->escapeString($providerData['website']) . "'";
        }
        
        if (isset($providerData['logo'])) {
            $sets[] = "logo = '" . $this->db->escapeString($providerData['logo']) . "'";
        }
        
        if (isset($providerData['is_active'])) {
            $sets[] = "is_active = " . ($providerData['is_active'] ? 'TRUE' : 'FALSE');
        }
        
        if (empty($sets)) {
            return false;
        }
        
        $query = "UPDATE service_providers SET " . implode(', ', $sets) . " WHERE id = " . (int)$id;
        $this->db->query($query);
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Update provider location
     */
    public function updateLocation($id, $lat, $lng) {
        $id = (int)$id;
        $lat = (float)$lat;
        $lng = (float)$lng;
        
        $query = "UPDATE service_providers SET latitude = {$lat}, longitude = {$lng} WHERE id = {$id}";
        $this->db->query($query);
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Delete a service provider
     */
    public function delete($id) {
        $id = (int)$id;
        $this->db->query("DELETE FROM service_providers WHERE id = {$id}");
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * Get the highest rated providers
     */
    public function getTopRated($limit = 10) {
        $limit = (int)$limit;
        $result = $this->db->query("
            SELECT sp.*, AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
            FROM service_providers sp
            JOIN reviews r ON sp.id = r.provider_id
            WHERE sp.is_active = TRUE
            GROUP BY sp.id
            HAVING review_count >= 3
            ORDER BY avg_rating DESC, review_count DESC
            LIMIT {$limit}
        ");
        
        $providers = [];
        
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }
        
        return $providers;
    }
} 