<?php
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Booking.php';
require_once SRC_PATH . '/models/Service.php';

class BookingController extends Controller {
    private $bookingModel;
    private $serviceModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = new Booking();
        $this->serviceModel = new Service();
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

        $availableDates = $this->serviceModel->getAvailableDates($serviceId);
        
        $this->render('booking/create', [
            'title' => 'Book Service',
            'service' => $service,
            'availableDates' => $availableDates,
            'styles' => ['booking']
        ]);
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
                'booking_date' => $_POST['booking_date'],
                'start_time' => $_POST['start_time'],
                'notes' => trim($_POST['notes'] ?? ''),
                'status' => 'pending'
            ];

            $bookingId = $this->bookingModel->create($bookingData);
            if ($bookingId) {
                $_SESSION['flash_message'] = 'Booking created successfully';
                $_SESSION['flash_type'] = 'success';
                $this->redirect(APP_URL . '/bookings/view/' . $bookingId);
            } else {
                $_SESSION['flash_message'] = 'Failed to create booking';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/services/book/' . $_POST['service_id']);
            }
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
