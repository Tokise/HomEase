<!-- Admin Sidebar -->
<div class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-brand">
            <i class="fas fa-home"></i>
            <span>HomEase Admin</span>
        </a>
    </div>
    
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/dashboard" class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/users" class="sidebar-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/services" class="sidebar-link <?= $currentPage === 'services' ? 'active' : '' ?>">
                <i class="fas fa-tools"></i>
                <span>Services</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/categories" class="sidebar-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i>
                <span>Categories</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/bookings" class="sidebar-link <?= $currentPage === 'bookings' ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/payments" class="sidebar-link <?= $currentPage === 'payments' ? 'active' : '' ?>">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/reviews" class="sidebar-link <?= $currentPage === 'reviews' ? 'active' : '' ?>">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
        </li>
        
        <div class="sidebar-divider"></div>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/settings" class="sidebar-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="<?= APP_URL ?>/admin/logs" class="sidebar-link <?= $currentPage === 'logs' ? 'active' : '' ?>">
                <i class="fas fa-history"></i>
                <span>Logs</span>
            </a>
        </li>
    </ul>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 60px 0 0;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.sidebar .nav-link {
    font-weight: 500;
    color: rgba(255, 255, 255, 0.75);
    padding: 0.7rem 1rem;
    font-size: 0.9rem;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.sidebar .nav-link:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-left-color: rgba(255, 255, 255, 0.5);
}

.sidebar .nav-link.active {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-left-color: #4e73df;
}

.sidebar .nav-link i {
    margin-right: 0.5rem;
    font-size: 1rem;
    opacity: 0.75;
}

.sidebar .nav-link.active i {
    opacity: 1;
}

.sidebar-heading {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1rem;
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 0;
        padding-top: 60px;
    }
}
</style> 