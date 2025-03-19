<?php
/**
 * Admin Controller
 */
require_once SRC_PATH . '/controllers/Controller.php';
require_once SRC_PATH . '/models/User.php';
require_once SRC_PATH . '/models/Service.php';
require_once SRC_PATH . '/models/Booking.php';

class AdminController extends Controller {
    private $userModel;
    private $serviceModel;
    private $bookingModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->serviceModel = new Service();
        $this->bookingModel = new Booking();
    }
    
    /**
     * Display admin dashboard
     */
    public function dashboard() {
        // Require admin authentication
        $this->requireRole(ROLE_ADMIN);

        // Get statistics
        $totalUsers = $this->userModel->getTotalUsers();
        $totalProviders = $this->userModel->getTotalProviders();
        $totalBookings = $this->bookingModel->getTotalBookings();
        $totalRevenue = $this->bookingModel->getTotalRevenue();
        $recentUsers = $this->userModel->getRecentUsers(5);
        $recentBookings = $this->bookingModel->getRecentBookings(5);

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'totalUsers' => $totalUsers,
            'totalProviders' => $totalProviders,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'recentUsers' => $recentUsers,
            'recentBookings' => $recentBookings,
            'styles' => ['dashboard']
        ]);
    }
    
    /**
     * Display users management page
     */
    public function users() {
        $this->requireRole(ROLE_ADMIN);
        
        $users = $this->userModel->getAllUsers();
        $this->render('admin/users', [
            'title' => 'Manage Users',
            'users' => $users,
            'styles' => ['users']
        ]);
    }
    
    /**
     * Display service providers management page
     */
    public function providers() {
        $this->requireRole(ROLE_ADMIN);
        
        $providers = $this->userModel->getAllProviders();
        $this->render('admin/providers', [
            'title' => 'Manage Service Providers',
            'providers' => $providers,
            'styles' => ['providers']
        ]);
    }
    
    /**
     * Update user status (active/inactive)
     */
    public function updateUserStatus($userId) {
        $this->requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = isset($_POST['is_active']) ? 1 : 0;
            
            if ($this->userModel->updateStatus($userId, $status)) {
                $_SESSION['flash_message'] = 'User status updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update user status';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    /**
     * Display service categories management
     */
    public function categories() {
        $this->requireRole(ROLE_ADMIN);
        
        $categories = $this->serviceModel->getAllCategories();
        $this->render('admin/categories', [
            'title' => 'Manage Service Categories',
            'categories' => $categories,
            'styles' => ['categories']
        ]);
    }
    
    /**
     * Create service category
     */
    public function createCategory() {
        $this->requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryData = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            if ($this->serviceModel->createCategory($categoryData)) {
                $_SESSION['flash_message'] = 'Category created successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to create category';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/admin/categories');
        }
    }
    
    /**
     * Update service category
     */
    public function updateCategory($categoryId) {
        $this->requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryData = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->serviceModel->updateCategory($categoryId, $categoryData)) {
                $_SESSION['flash_message'] = 'Category updated successfully';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Failed to update category';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/admin/categories');
        }
    }
    
    /**
     * Display bookings overview
     */
    public function bookings() {
        $this->requireRole(ROLE_ADMIN);
        
        $bookings = $this->bookingModel->getAllBookingsWithDetails();
        $this->render('admin/bookings', [
            'title' => 'Bookings Overview',
            'bookings' => $bookings,
            'styles' => ['bookings']
        ]);
    }
    
    /**
     * Display reports and analytics
     */
    public function reports() {
        $this->requireRole(ROLE_ADMIN);
        
        // Get various statistics and analytics data
        $monthlyBookings = $this->bookingModel->getMonthlyBookings();
        $monthlyRevenue = $this->bookingModel->getMonthlyRevenue();
        $topServices = $this->serviceModel->getTopServices();
        $topProviders = $this->userModel->getTopProviders();

        $this->render('admin/reports', [
            'title' => 'Reports & Analytics',
            'monthlyBookings' => $monthlyBookings,
            'monthlyRevenue' => $monthlyRevenue,
            'topServices' => $topServices,
            'topProviders' => $topProviders,
            'styles' => ['reports']
        ]);
    }
    
    /**
     * Display admin profile
     */
    public function profile() {
        $this->requireRole(ROLE_ADMIN);
        $admin = $this->getCurrentUser();
        
        $this->render('admin/profile', [
            'title' => 'Admin Profile',
            'admin' => $admin,
            'styles' => ['profile']
        ]);
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile() {
        $this->requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userData = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone_number' => trim($_POST['phone_number'])
            ];

            if ($this->userModel->update($userId, $userData)) {
                $_SESSION['flash_message'] = 'Profile updated successfully';
                $_SESSION['flash_type'] = 'success';
                
                // Update session name
                $_SESSION['user_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
            } else {
                $_SESSION['flash_message'] = 'Failed to update profile';
                $_SESSION['flash_type'] = 'danger';
            }
            
            $this->redirect(APP_URL . '/admin/profile');
        }
    }
} 