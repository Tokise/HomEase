<?php
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Booking.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/User.php';
require_once SRC_PATH . '/models/ProviderAvailability.php';

class BookingController extends Controller {
    private $bookingModel;
    private $serviceModel;
    private $userModel;
    private $availabilityModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = new Booking();
        $this->serviceModel = new Service();
        $this->userModel = new User();
        $this->availabilityModel = new ProviderAvailability();
    }

    /**
     * Display booking form
     */
    public function book($serviceId) {
        $this->requireRole(ROLE_CLIENT);
        
        $service = $this->serviceModel->findById($serviceId);
        if (!$service) {
            $this->redirect(APP_URL . '/services');
        }

        // Get provider details
        $provider = $this->userModel->findById($service['provider_id']);
        
        // Get default time slots
        $timeSlots = [
            '09:00:00', '10:00:00', '11:00:00', '13:00:00', 
            '14:00:00', '15:00:00', '16:00:00', '17:00:00'
        ];

        $this->render('booking/create', [
            'title' => 'Book Service',
            'service' => $service,
            'provider' => $provider,
            'timeSlots' => $timeSlots,
            'user' => $this->getCurrentUser(),
            'styles' => ['booking']
        ]);
    }

    /**
     * Get available time slots for a given date
     */
    public function getTimeSlots() {
        if (!isset($_GET['service_id']) || !isset($_GET['date'])) {
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $serviceId = $_GET['service_id'];
        $date = $_GET['date'];

        // Default time slots (you can fetch these from provider availability)
        $timeSlots = [
            '09:00:00', '10:00:00', '11:00:00', '13:00:00', 
            '14:00:00', '15:00:00', '16:00:00', '17:00:00'
        ];

        // Remove already booked slots
        $bookedSlots = $this->bookingModel->getBookedSlots($serviceId, $date);
        $availableSlots = array_diff($timeSlots, $bookedSlots);

        echo json_encode(['slots' => array_values($availableSlots)]);
    }

    /**
     * Process booking creation
     */
    public function create() {
        $this->requireRole(ROLE_CLIENT);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingData = [
                'client_id' => $_SESSION['user_id'],
                'service_id' => $_POST['service_id'],
                'provider_id' => $_POST['provider_id'],
                'booking_date' => $_POST['booking_date'],
                'start_time' => $_POST['start_time'],
                'total_price' => $_POST['total_price'],
                'notes' => trim($_POST['notes'] ?? ''),
                'status' => 'pending'
            ];

            $bookingId = $this->bookingModel->create($bookingData);
            if ($bookingId) {
                $_SESSION['flash_message'] = 'Booking created successfully';
                $_SESSION['flash_type'] = 'success';
                $this->redirect(APP_URL . '/client/bookings');
            } else {
                $_SESSION['flash_message'] = 'Failed to create booking';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/booking/book/' . $_POST['service_id']);
            }
        } else {
            $this->redirect(APP_URL . '/client/dashboard');
        }
    }

    /**
     * View booking details
     */
    public function view($bookingId) {
        $this->requireRole(ROLE_CLIENT);
        
        $booking = $this->bookingModel->getBookingDetails($bookingId);
        if (!$booking || $booking['client_id'] != $_SESSION['user_id']) {
            $this->redirect(APP_URL . '/bookings');
        }

        $this->render('booking/view', [
            'title' => 'Booking Details',
            'booking' => $booking,
            'styles' => ['booking']
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel($bookingId) {
        $this->requireRole(ROLE_CLIENT);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');
            
            if ($this->bookingModel->cancelBooking($bookingId, 'client', $reason)) {
                $_SESSION['flash_message'] = 'Booking cancelled successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to cancel booking';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/bookings');
        }
    }
}
