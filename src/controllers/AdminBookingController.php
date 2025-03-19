<?php
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Booking.php';

class AdminBookingController extends Controller {
    private $bookingModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = new Booking();
    }

    /**
     * Display all bookings
     */
    public function index() {
        $this->requireRole(ROLE_ADMIN);
        
        $filter = $_GET['filter'] ?? 'all';
        $bookings = $this->bookingModel->getAllBookingsWithDetails($filter);
        
        $this->render('admin/bookings/index', [
            'title' => 'Manage Bookings',
            'bookings' => $bookings,
            'filter' => $filter,
            'styles' => ['admin']
        ]);
    }

    /**
     * View booking details
     */
    public function view($bookingId) {
        $this->requireRole(ROLE_ADMIN);
        
        $booking = $this->bookingModel->getBookingDetails($bookingId);
        if (!$booking) {
            $this->redirect(APP_URL . '/admin/bookings');
        }

        $this->render('admin/bookings/view', [
            'title' => 'Booking Details',
            'booking' => $booking,
            'styles' => ['admin']
        ]);
    }

    /**
     * Update booking status
     */
    public function updateStatus($bookingId) {
        $this->requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = trim($_POST['status']);
            $notes = trim($_POST['notes'] ?? '');
            
            if ($this->bookingModel->updateStatus($bookingId, $status, $notes)) {
                $_SESSION['flash_message'] = 'Booking status updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update booking status';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/admin/bookings/view/' . $bookingId);
        }
    }

    /**
     * Get booking statistics
     */
    public function getStats() {
        $this->requireRole(ROLE_ADMIN);
        
        $stats = [
            'total' => $this->bookingModel->getBookingCount(),
            'pending' => $this->bookingModel->getBookingCount('pending'),
            'completed' => $this->bookingModel->getBookingCount('completed'),
            'cancelled' => $this->bookingModel->getBookingCount('cancelled'),
            'revenue' => $this->bookingModel->getTotalRevenue()
        ];

        header('Content-Type: application/json');
        echo json_encode($stats);
    }
}
