<?php
// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentPath = trim($currentPath, '/');
$pathSegments = explode('/', $currentPath);

// Set current page based on URL
$currentPage = end($pathSegments);
if ($currentPage === 'provider' || $currentPage === 'dashboard') {
    $currentPage = 'dashboard';
}
?>
<!-- Provider Sidebar -->
<style>
    .provider-sidebar {
        width: 250px;
        background: #233876;
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
        .provider-sidebar {
            transform: translateX(-100%);
        }

        .provider-sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<div class="provider-sidebar">
    <a href="<?= APP_URL ?>/provider/dashboard" class="sidebar-brand">
        Provider Portal
    </a>
    
    <ul class="sidebar-menu">
        <li>
            <a href="<?= APP_URL ?>/provider/dashboard" class="sidebar-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/services" class="sidebar-link <?= ($currentPage === 'services') ? 'active' : '' ?>">
                <i class="fas fa-tools"></i>
                <span>My Services</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/bookings" class="sidebar-link <?= ($currentPage === 'bookings') ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/calendar" class="sidebar-link <?= ($currentPage === 'calendar') ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Calendar</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/schedule" class="sidebar-link <?= ($currentPage === 'schedule') ? 'active' : '' ?>">
                <i class="fas fa-clock"></i>
                <span>Availability</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/reviews" class="sidebar-link <?= ($currentPage === 'reviews') ? 'active' : '' ?>">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/earnings" class="sidebar-link <?= ($currentPage === 'earnings') ? 'active' : '' ?>">
                <i class="fas fa-money-bill-wave"></i>
                <span>Earnings</span>
            </a>
        </li>
        
        <div class="sidebar-divider"></div>
        
        <li>
            <a href="<?= APP_URL ?>/provider/profile" class="sidebar-link <?= ($currentPage === 'profile') ? 'active' : '' ?>">
                <i class="fas fa-user-cog"></i>
                <span>Profile</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/provider/settings" class="sidebar-link <?= ($currentPage === 'settings') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        
        <li>
            <a href="<?= APP_URL ?>/auth/logout" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<script>
// Add console log to debug current page
console.log('Current Page:', '<?= $currentPage ?>');

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.provider-sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            if (window.innerWidth <= 768) {
                const mainContent = document.querySelector('.main-content');
                if (mainContent) {
                    mainContent.style.marginLeft = sidebar.classList.contains('show') ? '250px' : '0';
                }
            }
        });
    }

    // Debug active links
    const activeLinks = document.querySelectorAll('.sidebar-link.active');
    console.log('Active Links:', activeLinks.length);
});
</script> 