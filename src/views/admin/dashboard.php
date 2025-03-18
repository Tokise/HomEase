<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Admin Dashboard' ?> | HomEase</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Main Styles */
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
            --border-color: #e3e6f0;
            --sidebar-bg: #2d3436;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: var(--light-color);
        }

        /* Admin Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 230px;
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand {
            color: #fff;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
        }

        .sidebar-brand i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .sidebar-brand:hover {
            color: #fff;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0.5rem 0;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .sidebar-link i {
            margin-right: 0.85rem;
            width: 1.2rem;
            text-align: center;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar-link.active {
            background-color: rgba(78, 115, 223, 0.2);
            color: #fff;
            font-weight: 600;
            border-left-color: var(--primary-color);
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin: 0.75rem 1rem;
        }

        /* Content Area */
        .admin-content {
            flex: 1;
            margin-left: 230px;
            background-color: var(--light-color);
            overflow-x: hidden;
        }

        .admin-topbar {
            height: 60px;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .topbar-item {
            position: relative;
        }

        .topbar-link {
            color: #858796;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .topbar-link:hover {
            background-color: #f8f9fc;
            color: #3a3b45;
        }

        .topbar-divider {
            height: 0;
            margin: 0.5rem 0;
            border-right: 1px solid #e3e6f0;
            height: 2rem;
        }

        /* Dashboard Content */
        .admin-main {
            padding: 1.5rem;
            animation: fadeIn 0.3s ease-out;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            color: #5a5c69;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
            border: 2px solid #e3e6f0;
        }

        .user-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            font-size: 1rem;
            margin-right: 0.75rem;
        }

        .user-info {
            line-height: 1.2;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.1rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #858796;
        }

        /* Button Styles */
        .btn {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
        }

        #sidebarToggle {
            font-size: 1.2rem;
            color: #858796;
            background: transparent;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        #sidebarToggle:hover {
            color: var(--primary-color);
        }

        /* Font Weights */
        .fw-medium {
            font-weight: 500 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        /* Overview Stats Cards */
        .overview-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            transition: transform 0.2s;
            height: 100%;
        }

        .overview-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: rgba(78, 115, 223, 0.1);
            color: #4e73df;
            margin-right: 15px;
        }

        .icon-box.accent-2 {
            background-color: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
        }

        .icon-box.accent-3 {
            background-color: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
        }

        .overview-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #858796;
            margin-bottom: 5px;
        }

        .overview-card h2 {
            font-size: 24px;
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 0;
        }

        /* Card Styles */
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eaecf4;
            padding: 15px 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 0;
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            color: #858796;
            border-bottom: 2px solid #e3e6f0;
            padding: 12px 20px;
        }

        .table td {
            vertical-align: middle;
            padding: 12px 20px;
            border-bottom: 1px solid #e3e6f0;
        }

        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        /* Badge Styles */
        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 30px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            box-shadow: 0 2px 6px rgba(78,115,223,0.15);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #3a5fd0;
            border-color: #3a5fd0;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(78,115,223,0.25);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }
            
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            
            .admin-content {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar-toggle-btn {
                display: block;
            }
            
            .admin-sidebar.toggled {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-brand">
                <i class="fas fa-home"></i>
                <span>HomEase Admin</span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-link active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/users" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/services" class="sidebar-link">
                    <i class="fas fa-tools"></i>
                    <span>Services</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/categories" class="sidebar-link">
                    <i class="fas fa-th-large"></i>
                    <span>Categories</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/bookings" class="sidebar-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/payments" class="sidebar-link">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/reviews" class="sidebar-link">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </li>
            
            <div class="sidebar-divider"></div>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/settings" class="sidebar-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="sidebar-item">
                <a href="<?= APP_URL ?>/admin/logs" class="sidebar-link">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Content Area -->
    <div class="admin-content">
        <!-- Topbar -->
        <div class="admin-topbar">
            <button id="sidebarToggle" class="btn btn-link">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="topbar-menu">
                <li class="topbar-item">
                    <a href="#" class="topbar-link">
                        <i class="fas fa-bell"></i>
                        <?php if (isset($notificationCount) && $notificationCount > 0): ?>
                            <span class="badge bg-danger"><?= $notificationCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="topbar-item">
                    <a href="<?= APP_URL ?>" target="_blank" class="topbar-link" title="View Site">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </li>
                <li class="topbar-divider"></li>
                <li class="topbar-item">
                    <div class="user-profile">
                        <?php if (!empty($admin['profile_picture'])): ?>
                            <img src="<?= APP_URL ?>/<?= $admin['profile_picture'] ?>" alt="Admin" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                        <div class="user-info">
                            <h6 class="user-name"><?= isset($admin['first_name']) ? htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) : 'Admin User' ?></h6>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </li>
                <li class="topbar-item">
                    <a href="<?= APP_URL ?>/auth/logout" class="topbar-link" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="admin-main">
            <div class="container-fluid px-4">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <!-- Dashboard Header -->
                        <div class="page-heading">
                            <h1 class="page-title">Dashboard</h1>
                        </div>

                        <!-- Overview Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4 col-xl-3 col-sm-6 mb-3">
                                <div class="overview-card d-flex align-items-center">
                                    <div class="icon-box">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3>Bookings</h3>
                                        <h2><?= $bookingCount ?? 0 ?></h2>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-xl-3 col-sm-6 mb-3">
                                <div class="overview-card d-flex align-items-center">
                                    <div class="icon-box accent-2">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div>
                                        <h3>Services</h3>
                                        <h2><?= $serviceCount ?? 5 ?></h2>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-xl-3 col-sm-6 mb-3">
                                <div class="overview-card d-flex align-items-center">
                                    <div class="icon-box accent-3">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <h3>Providers</h3>
                                        <h2><?= $providerCount ?? 0 ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Users -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Recent Users</h5>
                                <a href="<?= APP_URL ?>/admin/users" class="btn btn-primary btn-sm">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($recentUsers)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center py-3">No recent users found</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($recentUsers as $user): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                                        <td>
                                                            <?php 
                                                            $roleClass = '';
                                                            switch($user['role_id']) {
                                                                case ROLE_ADMIN:
                                                                    $roleClass = 'danger';
                                                                    $roleName = 'Admin';
                                                                    break;
                                                                case ROLE_SERVICE_PROVIDER:
                                                                    $roleClass = 'info';
                                                                    $roleName = 'Provider';
                                                                    break;
                                                                default:
                                                                    $roleClass = 'success';
                                                                    $roleName = 'Client';
                                                            }
                                                            ?>
                                                            <span class="badge bg-<?= $roleClass ?>"><?= $roleName ?></span>
                                                        </td>
                                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Admin JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            document.querySelector('.admin-sidebar').classList.toggle('toggled');
        });
    }
    
    // Tooltips initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</body>
</html> 