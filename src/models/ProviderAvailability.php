<?php
require_once SRC_PATH . '/models/Model.php';

class ProviderAvailability extends Model {
    protected $table = 'provider_availability';

    public function getAvailableTimeSlots($providerId, $date) {
        // Default time slots
        $defaultSlots = [
            '09:00:00', '10:00:00', '11:00:00', '13:00:00', 
            '14:00:00', '15:00:00', '16:00:00', '17:00:00'
        ];

        // Get day of week (1=Monday, 7=Sunday)
        $dayOfWeek = date('N', strtotime($date));

        try {
            // Get provider's availability for this day
            $stmt = $this->db->prepare("
                SELECT start_time, end_time 
                FROM {$this->table}
                WHERE provider_id = ? AND day_of_week = ?
            ");
            $stmt->execute([$providerId, $dayOfWeek]);
            $availability = $stmt->fetch();

            if (!$availability) {
                return $defaultSlots; // Return default slots if no specific availability
            }

            // Filter slots based on provider's availability
            return array_filter($defaultSlots, function($slot) use ($availability) {
                return $slot >= $availability['start_time'] && 
                       $slot <= $availability['end_time'];
            });
        } catch (PDOException $e) {
            error_log("Error getting available time slots: " . $e->getMessage());
            return $defaultSlots;
        }
    }
}
