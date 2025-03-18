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
        // Check if user is logged in and is an admin
        $this->authCheck();
        
        $this->userModel = new User();
        $this->serviceModel = new Service();
        $this->bookingModel = new Booking();
    }
    
    /**
     * Authentication check for admin
     */
    private function authCheck() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You need to be logged in to access this page.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            exit;
        }
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != ROLE_ADMIN) {
            $_SESSION['flash_message'] = 'You do not have permission to access the admin area.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/auth/login');
            exit;
        }
    }
    
    /**
     * Display admin dashboard
     */
    public function dashboard() {
        // Get counts for dashboard statistics
        $userCount = count($this->userModel->getAll());
        $clientCount = count($this->userModel->getByRole(ROLE_CLIENT));
        $providerCount = count($this->userModel->getByRole(ROLE_SERVICE_PROVIDER));
        $serviceCount = count($this->serviceModel->getAll());
        $bookingCount = count($this->bookingModel->getAll());
        
        // Get recent bookings
        $recentBookings = $this->bookingModel->getRecent(5);
        
        // Get recent users
        $recentUsers = $this->userModel->getRecent(5);
        
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard | HomEase',
            'userCount' => $userCount,
            'clientCount' => $clientCount,
            'providerCount' => $providerCount,
            'serviceCount' => $serviceCount,
            'bookingCount' => $bookingCount,
            'recentBookings' => $recentBookings,
            'recentUsers' => $recentUsers
        ]);
    }
    
    /**
     * Manage users
     */
    public function users() {
        $users = $this->userModel->getAll();
        
        $this->render('admin/users', [
            'title' => 'Manage Users | Admin Dashboard',
            'users' => $users
        ]);
    }
    
    /**
     * View user details
     */
    public function viewUser($id) {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/admin/users');
            return;
        }
        
        $this->render('admin/view-user', [
            'title' => 'User Details | Admin Dashboard',
            'user' => $user
        ]);
    }
    
    /**
     * Edit user
     */
    public function editUser($id) {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/admin/users');
            return;
        }
        
        $this->render('admin/edit-user', [
            'title' => 'Edit User | Admin Dashboard',
            'user' => $user
        ]);
    }
    
    /**
     * Process edit user form
     */
    public function processEditUser($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users');
            return;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/admin/users');
            return;
        }
        
        // Get form data
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $roleId = (int)($_POST['role_id'] ?? ROLE_CLIENT);
        $isActive = isset($_POST['is_active']) ? true : false;
        
        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email)) {
            $_SESSION['flash_message'] = 'Please fill in all required fields.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/admin/edit-user/' . $id);
            return;
        }
        
        // Update user data
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phone,
            'role_id' => $roleId,
            'is_active' => $isActive
        ];
        
        // Update password if provided
        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        // Update user
        $result = $this->userModel->update($id, $userData);
        
        if ($result) {
            $_SESSION['flash_message'] = 'User updated successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to update user.';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect(APP_URL . '/admin/users');
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id) {
        // Prevent self-deletion
        if ((int)$id === (int)$_SESSION['user_id']) {
            $_SESSION['flash_message'] = 'You cannot delete your own account.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect(APP_URL . '/admin/users');
            return;
        }
        
        $result = $this->userModel->delete($id);
        
        if ($result) {
            $_SESSION['flash_message'] = 'User deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Failed to delete user.';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect(APP_URL . '/admin/users');
    }
    
    /**
     * Manage services
     */
    public function services() {
        $services = $this->serviceModel->getAll();
        
        $this->render('admin/services', [
            'title' => 'Manage Services | Admin Dashboard',
            'services' => $services
        ]);
    }
    
    /**
     * Manage bookings
     */
    public function bookings() {
        $bookings = $this->bookingModel->getAll();
        
        $this->render('admin/bookings', [
            'title' => 'Manage Bookings | Admin Dashboard',
            'bookings' => $bookings
        ]);
    }
    
    /**
     * View system settings
     */
    public function settings() {
        $this->render('admin/settings', [
            'title' => 'System Settings | Admin Dashboard'
        ]);
    }
} 