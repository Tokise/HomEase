<?php
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/Booking.php';
require_once SRC_PATH . '/models/User.php';
require_once CONFIG_PATH . '/config.php';

class ServiceProviderController extends Controller {
    private $serviceModel;
    private $bookingModel;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->serviceModel = new Service();
        $this->bookingModel = new Booking();
        $this->userModel = new User();
    }

    /**
     * Display provider dashboard
     */
    public function dashboard() {
        // Require provider authentication
        $this->requireRole(ROLE_PROVIDER);

        $provider = $this->getCurrentUser();
        $upcomingBookings = $this->bookingModel->getProviderUpcomingBookings($provider['id']);
        $recentBookings = $this->bookingModel->getProviderRecentBookings($provider['id']);
        $activeServices = $this->serviceModel->getProviderActiveServices($provider['id']);

        $this->render('provider/dashboard', [
            'title' => 'Provider Dashboard',
            'provider' => $provider,
            'upcomingBookings' => $upcomingBookings,
            'recentBookings' => $recentBookings,
            'activeServices' => $activeServices,
            'styles' => ['dashboard']
        ]);
    }

    /**
     * Display services management page
     */
    public function services() {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        $services = $this->serviceModel->getProviderServices($provider['id']);
        
        $this->render('provider/services', [
            'title' => 'Manage Services',
            'services' => $services,
            'styles' => ['services']
        ]);
    }

    /**
     * Show add service form
     */
    public function add() {
        $this->requireRole(ROLE_PROVIDER);
        $categories = $this->serviceModel->getAllCategories();
        
        $this->render('provider/add-service', [
            'title' => 'Add New Service',
            'categories' => $categories,
            'styles' => ['services']
        ]);
    }

    /**
     * Create a new service
     */
    public function createService() {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            if (empty($_POST['name']) || empty($_POST['description']) || !isset($_POST['price']) || !isset($_POST['duration']) || !isset($_POST['category_id'])) {
                $_SESSION['flash_message'] = 'All fields are required';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/provider/services');
                return;
            }

            $serviceData = [
                'provider_id' => $_SESSION['user_id'],
                'name' => filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING),
                'description' => filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING),
                'price' => filter_var($_POST['price'], FILTER_VALIDATE_FLOAT) ? floatval($_POST['price']) : 0,
                'duration' => filter_var($_POST['duration'], FILTER_VALIDATE_INT) ? intval($_POST['duration']) : 0,
                'category_id' => filter_var($_POST['category_id'], FILTER_VALIDATE_INT) ? intval($_POST['category_id']) : 0
            ];

            // Validate numeric values
            if ($serviceData['price'] <= 0 || $serviceData['duration'] <= 0 || $serviceData['category_id'] <= 0) {
                $_SESSION['flash_message'] = 'Invalid price, duration, or category';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/provider/services');
                return;
            }

            if ($this->serviceModel->create($serviceData)) {
                $_SESSION['flash_message'] = 'Service created successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to create service';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/provider/services');
        }
    }

    /**
     * Update service details
     */
    public function updateService($serviceId) {
        $this->requireRole(ROLE_PROVIDER);
        
        // Verify service ownership
        $service = $this->serviceModel->findById($serviceId);
        if (!$service || $service['provider_id'] != $_SESSION['user_id']) {
            $_SESSION['flash_message'] = 'Unauthorized access';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/provider/services');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            if (empty($_POST['name']) || empty($_POST['description']) || !isset($_POST['price']) || !isset($_POST['duration'])) {
                $_SESSION['flash_message'] = 'All fields are required';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/provider/services');
                return;
            }

            $serviceData = [
                'name' => filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING),
                'description' => filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING),
                'price' => filter_var($_POST['price'], FILTER_VALIDATE_FLOAT) ? floatval($_POST['price']) : 0,
                'duration' => filter_var($_POST['duration'], FILTER_VALIDATE_INT) ? intval($_POST['duration']) : 0,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            // Validate numeric values
            if ($serviceData['price'] <= 0 || $serviceData['duration'] <= 0) {
                $_SESSION['flash_message'] = 'Invalid price or duration';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect(APP_URL . '/provider/services');
                return;
            }

            if ($this->serviceModel->update($serviceId, $serviceData)) {
                $_SESSION['flash_message'] = 'Service updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update service';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/provider/services');
        }
    }

    /**
     * Display bookings management page
     */
    public function bookings() {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        $bookings = $this->bookingModel->getProviderBookings($provider['id']);
        
        $this->render('provider/bookings', [
            'title' => 'Manage Bookings',
            'bookings' => $bookings,
            'styles' => ['bookings']
        ]);
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus($bookingId) {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = trim($_POST['status']);
            $notes = trim($_POST['notes'] ?? '');

            // Pass only the status, not the notes parameter as the model doesn't accept it
            if ($this->bookingModel->updateStatus($bookingId, $status)) {
                $_SESSION['flash_message'] = 'Booking status updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update booking status';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/provider/bookings');
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking($bookingId) {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');

            if ($this->bookingModel->cancelBooking($bookingId, 'provider', $reason)) {
                $_SESSION['flash_message'] = 'Booking cancelled successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to cancel booking';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/provider/bookings');
        }
    }

    /**
     * Confirm booking
     */
    public function confirmBooking($bookingId) {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($this->bookingModel->updateStatus($bookingId, 'confirmed')) {
                $response = [
                    'success' => true,
                    'message' => 'Booking confirmed successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to confirm booking'
                ];
            }
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['flash_message'] = $response['message'];
            $_SESSION['flash_type'] = $response['success'] ? 'success' : 'danger';
            $this->redirect(APP_URL . '/provider/bookings');
        }
    }

    /**
     * Complete booking
     */
    public function completeBooking($bookingId) {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($this->bookingModel->updateStatus($bookingId, 'completed')) {
                $response = [
                    'success' => true,
                    'message' => 'Booking marked as completed'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to complete booking'
                ];
            }
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $_SESSION['flash_message'] = $response['message'];
            $_SESSION['flash_type'] = $response['success'] ? 'success' : 'danger';
            $this->redirect(APP_URL . '/provider/bookings');
        }
    }

    /**
     * Display provider profile
     */
    public function profile() {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        
        $this->render('provider/profile', [
            'title' => 'Provider Profile',
            'provider' => $provider,
            'styles' => ['profile']
        ]);
    }

    /**
     * Update provider profile
     */
    public function updateProfile() {
        $this->requireRole(ROLE_PROVIDER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userData = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone_number' => trim($_POST['phone_number']),
                'address' => trim($_POST['address']),
                'business_name' => trim($_POST['business_name'] ?? ''),
                'business_description' => trim($_POST['business_description'] ?? '')
            ];

            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_picture'];
                $allowedTypes = ['image/jpeg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                // Verify MIME type using fileinfo
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedTypes)) {
                    $_SESSION['flash_message'] = 'Invalid file type. Please upload a JPG or PNG image.';
                    $_SESSION['flash_type'] = 'danger';
                    $this->redirect(APP_URL . '/provider/profile');
                    return;
                }

                if ($file['size'] > $maxSize) {
                    $_SESSION['flash_message'] = 'File is too large. Maximum size is 2MB.';
                    $_SESSION['flash_type'] = 'danger';
                    $this->redirect(APP_URL . '/provider/profile');
                    return;
                }

                $uploadDir = ROOT_PATH . '/public/uploads/profile/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('provider_') . '_' . time() . '.' . $extension;
                $uploadPath = $uploadDir . $fileName;

                // Secure file move
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $_SESSION['flash_message'] = 'Failed to upload profile picture';
                    $_SESSION['flash_type'] = 'danger';
                    $this->redirect(APP_URL . '/provider/profile');
                    return;
                }

                $userData['profile_picture'] = '/public/uploads/profile/' . $fileName;
            }

            if ($this->userModel->update($userId, $userData)) {
                $_SESSION['flash_message'] = 'Profile updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update profile';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/provider/profile');
        }
    }

    /**
     * Manage schedule availability
     */
    public function manageSchedule() {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle schedule update
            $availabilityData = [
                'day_of_week' => $_POST['day_of_week'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time']
            ];

            if ($this->serviceModel->addAvailability($provider['id'], $availabilityData)) {
                $_SESSION['flash_message'] = 'Schedule updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update schedule';
                $_SESSION['flash_type'] = 'danger';
            }
        }

        $availability = $this->serviceModel->getProviderAvailability($provider['id']);
        
        $this->render('provider/schedule', [
            'title' => 'Manage Schedule',
            'availability' => $availability,
            'styles' => ['schedule']
        ]);
    }

    /**
     * View calendar overview
     */
    public function calendar() {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        
        $date = $_GET['date'] ?? date('Y-m-d');
        $services = $this->serviceModel->getProviderActiveServices($provider['id']);
        $bookings = $this->bookingModel->getProviderBookingsByDate($provider['id'], $date);
        
        $this->render('provider/calendar', [
            'title' => 'Calendar Overview',
            'services' => $services,
            'bookings' => $bookings,
            'currentDate' => $date,
            'styles' => ['calendar']
        ]);
    }

    /**
     * Get available time slots (AJAX endpoint)
     */
    public function getTimeSlots() {
        $this->requireRole(ROLE_PROVIDER);
        
        header('Content-Type: application/json');
        
        if (!isset($_GET['service_id']) || !isset($_GET['date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $serviceId = filter_var($_GET['service_id'], FILTER_VALIDATE_INT);
        $date = filter_var($_GET['date'], FILTER_SANITIZE_STRING);

        if (!$serviceId || !strtotime($date)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }

        try {
            $slots = $this->serviceModel->getAvailableTimeSlots($serviceId, $date);
            echo json_encode(['success' => true, 'slots' => $slots]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch time slots']);
        }
    }

    /**
     * Get service overview statistics
     */
    public function getServiceStats($serviceId) {
        $this->requireRole(ROLE_PROVIDER);
        $provider = $this->getCurrentUser();
        
        // Ensure the service belongs to the provider
        $service = $this->serviceModel->findById($serviceId);
        if (!$service || $service['provider_id'] != $provider['id']) {
            return false;
        }

        $stats = [
            'total_bookings' => $this->bookingModel->getServiceBookingCount($serviceId),
            'completed_bookings' => $this->bookingModel->getServiceBookingCount($serviceId, 'completed'),
            'cancelled_bookings' => $this->bookingModel->getServiceBookingCount($serviceId, 'cancelled'),
            'total_revenue' => $this->bookingModel->getServiceRevenue($serviceId),
            'average_rating' => $this->bookingModel->getServiceAverageRating($serviceId)
        ];

        return $stats;
    }

    public function renderBookingTable($bookings) {
        ?>
        <div class="table-responsive">
            <table class="table table-hover booking-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                        <td><?= htmlspecialchars($booking['client_first_name'] . ' ' . $booking['client_last_name']) ?></td>
                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($booking['start_time'])) ?></td>
                            <td><span class="badge bg-<?= $this->getStatusBadgeClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
                            <td>$<?= number_format($booking['total_price'], 2) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= APP_URL ?>/provider/bookings/view/<?= $booking['id'] ?>" 
                                       class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($booking['status'] == 'pending'): ?>
                                        <a href="<?= APP_URL ?>/provider/bookings/confirm/<?= $booking['id'] ?>" 
                                           class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Confirm">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($booking['status'] == 'confirmed'): ?>
                                        <a href="<?= APP_URL ?>/provider/bookings/complete/<?= $booking['id'] ?>" 
                                           class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Mark Complete">
                                            <i class="fas fa-check-double"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
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
     */
    public function getStatusBadgeClass($status) {
        switch (strtolower($status)) {
            case 'pending':
                return 'warning';
            case 'confirmed':
                return 'info';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}