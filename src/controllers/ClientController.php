<?php
/**
 * Client Controller
 * Handles client-specific actions and pages
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/User.php';
require_once SRC_PATH . '/models/Service.php';

class ClientController extends Controller {
    private $userModel;
    private $serviceModel;
    private $bookingModel;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->serviceModel = new Service();
        
        // Create Booking model with empty data for now
        // In a real implementation, you would integrate with the actual Booking model
        $this->bookingModel = new stdClass();
        $this->bookingModel->upcomingBookings = [];
        $this->bookingModel->recentBookings = [];
        $this->bookingModel->allBookings = [];
    }

    /**
     * Display client dashboard
     */
    public function dashboard() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] !== 'client') {
            $_SESSION['flash_message'] = 'You must be logged in as a client to access this page';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['flash_message'] = 'User not found';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        // Get featured services
        $featuredServices = $this->serviceModel->getFeatured(4);

        // Render dashboard view
        $viewData = [
            'user' => $user,
            'upcomingBookings' => $this->bookingModel->upcomingBookings,
            'recentBookings' => $this->bookingModel->recentBookings,
            'featuredServices' => $featuredServices,
            'title' => 'Client Dashboard',
            'css' => ['dashboard.css']
        ];

        $this->render('client/dashboard', $viewData);
    }

    /**
     * Display client profile
     */
    public function profile() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] !== 'client') {
            $_SESSION['flash_message'] = 'You must be logged in as a client to access this page';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['flash_message'] = 'User not found';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        $viewData = [
            'user' => $user,
            'title' => 'My Profile',
            'css' => ['profile.css']
        ];

        $this->render('client/profile', $viewData);
    }

    /**
     * Update client profile
     */
    public function updateProfile() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] !== 'client') {
            $_SESSION['flash_message'] = 'You must be logged in as a client to access this page';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/client/profile');
            return;
        }

        $userId = $_SESSION['user_id'];
        
        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];

        // Handle profile picture upload if present
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/uploads/profile/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($_FILES['profile_picture']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                $userData['profile_picture'] = '/public/uploads/profile/' . $fileName;
            }
        }

        // Update user data
        if ($this->userModel->update($userId, $userData)) {
            $_SESSION['flash_message'] = 'Profile updated successfully';
            $_SESSION['flash_type'] = 'success';
            
            // Update session name if changed
            if (isset($userData['first_name']) || isset($userData['last_name'])) {
                $user = $this->userModel->findById($userId);
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            }
        } else {
            $_SESSION['flash_message'] = 'Failed to update profile';
            $_SESSION['flash_type'] = 'danger';
        }

        $this->redirect(APP_URL . '/client/profile');
    }

    /**
     * Display bookings history
     */
    public function bookings() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] !== 'client') {
            $_SESSION['flash_message'] = 'You must be logged in as a client to access this page';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        $viewData = [
            'bookings' => $this->bookingModel->allBookings,
            'title' => 'My Bookings',
            'css' => ['bookings.css']
        ];

        $this->render('client/bookings', $viewData);
    }

    /**
     * Helper function to get Bootstrap badge class based on booking status
     * 
     * @param string $status Booking status
     * @return string Bootstrap badge class
     */
    public function getStatusBadgeClass($status) {
        switch (strtolower($status)) {
            case 'pending':
                return 'warning';
            case 'confirmed':
                return 'info';
            case 'in progress':
                return 'primary';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    private function isUserLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Helper method to render a bookings table
     * 
     * @param array $bookings Array of booking data
     */
    public function renderBookingsTable($bookings) {
        ?>
        <div class="table-responsive">
            <table class="table table-hover booking-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th class="booking-date">Date</th>
                        <th class="booking-time">Time</th>
                        <th>Provider</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($booking['start_time'])) ?></td>
                            <td><?= htmlspecialchars($booking['provider_first_name'] . ' ' . $booking['provider_last_name']) ?></td>
                            <td><span class="badge bg-<?= $this->getStatusBadgeClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
                            <td>$<?= number_format($booking['total_amount'], 2) ?></td>
                            <td class="booking-actions">
                                <?php if ($booking['status'] !== 'cancelled' && $booking['status'] !== 'completed'): ?>
                                    <?php 
                                        $bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
                                        $now = time();
                                        $canCancel = ($bookingDateTime - $now) > (24 * 60 * 60); // Can cancel if more than 24 hours notice
                                    ?>
                                    <?php if ($canCancel): ?>
                                        <a href="<?= APP_URL ?>/client/cancelBooking/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot cancel with less than 24 hours notice">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    <?php endif; ?>
                                    
                                    <a href="<?= APP_URL ?>/client/viewBooking/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                <?php elseif ($booking['status'] === 'completed'): ?>
                                    <a href="<?= APP_URL ?>/reviews/add/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-star"></i> Review
                                    </a>
                                    <a href="<?= APP_URL ?>/client/viewBooking/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                <?php else: ?>
                                    <a href="<?= APP_URL ?>/client/viewBooking/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
} 