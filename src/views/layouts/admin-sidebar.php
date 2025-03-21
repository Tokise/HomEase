<?php
// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentPath = trim($currentPath, '/');
$pathSegments = explode('/', $currentPath);

// Set current page based on URL
$currentPage = end($pathSegments);
if ($currentPage === 'admin' || $currentPage === 'dashboard') {
    $currentPage = 'dashboard';
}
?>
<!-- Admin Sidebar -->
<style>
    .admin-sidebar {
        width: 250px;
        background: #2d3436;
        color: #fff;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .sidebar-brand {
        height: 60px;
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        color: #fff;
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-brand:hover {
        color: #fff;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-menu {
        list-style: none;
        padding: 1rem 0;
        margin: 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    .sidebar-link i {
        width: 20px;
        margin-right: 0.75rem;
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }

    .sidebar-link span {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .sidebar-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border-left: 4px solid rgba(78, 115, 223, 0.5);
    }

    .sidebar-link:hover i {
        color: #4e73df;
    }

    .sidebar-link.active {
        color: #fff;
        background: rgba(78, 115, 223, 0.2);
        border-left: 4px solid #4e73df;
    }

    .sidebar-link.active:hover {
        background: rgba(78, 115, 223, 0.25);
    }

    .sidebar-link.active i {
        color: #4e73df;
    }

    .sidebar-divider {
        height: 0;
        margin: 1rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }

        .admin-sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<div class="admin-sidebar">
    <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-brand">
            HomeSwift Admin
    </a>
    
    <ul class="sidebar-menu">
        <li>
            <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/users" class="sidebar-link <?= ($currentPage === 'users') ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/services" class="sidebar-link <?= ($currentPage === 'services') ? 'active' : '' ?>">
                <i class="fas fa-tools"></i>
                <span>Services</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/categories" class="sidebar-link <?= ($currentPage === 'categories') ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i>
                <span>Categories</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/bookings" class="sidebar-link <?= ($currentPage === 'bookings') ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/payments" class="sidebar-link <?= ($currentPage === 'payments') ? 'active' : '' ?>">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/reviews" class="sidebar-link <?= ($currentPage === 'reviews') ? 'active' : '' ?>">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
        </li>
        
        <div class="sidebar-divider"></div>
        
        <li>
            <a href="<?= APP_URL ?>/admin/settings" class="sidebar-link <?= ($currentPage === 'settings') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/admin/logs" class="sidebar-link <?= ($currentPage === 'logs') ? 'active' : '' ?>">
                <i class="fas fa-history"></i>
                <span>Logs</span>
            </a>
        </li>
    </ul>
</div>

<script>
// Add console log to debug current page
console.log('Current Page:', '<?= $currentPage ?>');

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Debug active links
    const activeLinks = document.querySelectorAll('.sidebar-link.active');
    console.log('Active Links:', activeLinks.length);
});
</script> 