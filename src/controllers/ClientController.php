<?php
/**
 * Client Controller
 * Handles client-specific actions and pages
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/User.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/Booking.php';

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
        $this->bookingModel = new Booking();
    }

    /**
     * Display client dashboard
     */
    public function dashboard() {
        // Require client authentication
        $this->requireRole(ROLE_CLIENT);

        // Get user data
        $user = $this->getCurrentUser();
        
        // Get user's bookings and featured services
        $upcomingBookings = $this->bookingModel->getUpcomingBookings($user['id']);
        $recentBookings = $this->bookingModel->getRecentBookings($user['id']);
        $featuredServices = $this->serviceModel->getFeatured(4);

        // Render dashboard view
        $this->render('client/dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'upcomingBookings' => $upcomingBookings,
            'recentBookings' => $recentBookings,
            'featuredServices' => $featuredServices,
            'styles' => ['dashboard']
        ]);
    }

    /**
     * Display client profile
     */
    public function profile() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] != ROLE_CLIENT) {
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
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] != ROLE_CLIENT) {
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
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone_number' => trim($_POST['phone_number'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];

        // Handle profile picture upload if present
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            // Validate file type and size
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['flash_message'] = 'Invalid file type. Please upload a JPG or PNG image.';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/client/profile');
                return;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['flash_message'] = 'File is too large. Maximum size is 2MB.';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/client/profile');
                return;
            }

            // Create upload directory if it doesn't exist
            $uploadDir = ROOT_PATH . '/public/uploads/profile/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('profile_') . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $fileName;

            // Delete old profile picture if exists
            $currentUser = $this->userModel->findById($userId);
            if (!empty($currentUser['profile_picture'])) {
                $oldFile = ROOT_PATH . $currentUser['profile_picture'];
                if (file_exists($oldFile) && is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Upload new file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $userData['profile_picture'] = '/public/uploads/profile/' . $fileName;
            } else {
                error_log("Failed to move uploaded file from {$file['tmp_name']} to {$uploadPath}");
                $_SESSION['flash_message'] = 'Failed to upload profile picture. Please try again.';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/client/profile');
                return;
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
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] != ROLE_CLIENT) {
            $_SESSION['flash_message'] = 'You must be logged in as a client to access this page';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        // Get user data
        $user = $this->getCurrentUser();
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            return;
        }

        $viewData = [
            'user' => $user,
            'bookings' => $this->bookingModel->getAllBookings($_SESSION['user_id']),
            'title' => 'My Bookings',
            'styles' => ['bookings'],
            'scripts' => ['bookings']
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
                            <td>$<?= number_format($booking['total_price'], 2) ?></td>
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

    /**
     * Get Bootstrap badge class based on booking status
     * 
     * @param string $status Booking status
     * @return string Bootstrap badge class
     */
    public function getBookingStatusClass($status) {
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
     * Remove profile picture
     */
    public function removeProfilePicture() {
        // Check if user is logged in and is a client
        if (!$this->isUserLoggedIn() || $_SESSION['user_role'] != ROLE_CLIENT) {
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
            $this->redirect(APP_URL . '/client/profile');
            return;
        }

        // Delete the profile picture file if it exists
        if (!empty($user['profile_picture'])) {
            $filePath = ROOT_PATH . $user['profile_picture'];
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }

            // Update user record to remove profile picture reference
            $this->userModel->update($userId, ['profile_picture' => null]);
            
            $_SESSION['flash_message'] = 'Profile picture removed successfully';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'No profile picture to remove';
            $_SESSION['flash_type'] = 'info';
        }

        $this->redirect(APP_URL . '/client/profile');
    }
} 