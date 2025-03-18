<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - HomEase</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

 <!-- Navigation -->
 <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= APP_URL ?>">
                <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase" height="40">
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-wrapper">
                            <?php if (!empty($user['google_picture'])): ?>
                                <img src="<?= htmlspecialchars($user['google_picture']) ?>" alt="Profile" class="user-avatar">
                            <?php elseif (!empty($user['profile_picture'])): ?>
                                <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar-placeholder" style="background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);">
                                    <i class="fas fa-user" style="font-size: 18px; color: white;"></i>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($user['google_id'])): ?>
                                <span style="
                                    position: absolute;
                                    bottom: -2px;
                                    right: -2px;
                                    background: #fff;
                                    border-radius: 50%;
                                    width: 16px;
                                    height: 16px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 10px;
                                    color: #4285F4;
                                    border: 1px solid #e0e0e0;
                                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                                    title="Google Account">
                                    <i class="fab fa-google"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="ms-2"><?= htmlspecialchars($user['first_name']) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/client/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/client/profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

<div class="bookings-container">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">My Bookings</h2>
                    <a href="<?= APP_URL ?>/services" class="btn btn-primary btn-animate">
                        <i class="fas fa-plus me-2"></i>Book New Service
                    </a>
                </div>

                <!-- Booking Filters -->
                <div class="card filter-card mb-4">
                <div class="card-body">
                        <form id="filterForm" class="row g-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <div class="select-wrapper">
                                    <select id="status" class="form-select custom-select">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    <i class="fas fa-chevron-down select-icon"></i>
                                </div>
                        </div>
                            <div class="col-md-4">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <div class="input-wrapper">
                                    <input type="date" id="dateRange" class="form-control custom-input">
                                    <i class="fas fa-calendar input-icon"></i>
                                </div>
                        </div>
                            <div class="col-md-4">
                                <label for="searchService" class="form-label">Search Service</label>
                                <div class="input-wrapper">
                                    <input type="text" id="searchService" class="form-control custom-input" placeholder="Search by service name...">
                                    <i class="fas fa-search input-icon"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card bookings-card">
                    <div class="card-body">
                        <?php if (!empty($bookings)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover custom-table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Service</th>
                                            <th>Provider</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td class="booking-id fw-medium">#<?= htmlspecialchars($booking['id']) ?></td>
                                                <td class="service-name fw-medium"><?= htmlspecialchars($booking['service_name']) ?></td>
                                                <td class="provider-name"><?= htmlspecialchars($booking['provider_name'] ?? $booking['provider_first_name'] . ' ' . $booking['provider_last_name']) ?></td>
                                                <td class="booking-date"><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                                <td class="booking-time"><?= date('h:i A', strtotime($booking['start_time'])) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $this->getBookingStatusClass($booking['status']) ?> status-badge">
                                                        <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                                                    </span>
                                                </td>
                                                <td class="booking-total fw-medium">$<?= number_format($booking['total_amount'], 2) ?></td>
                                                <td class="booking-actions">
                                                    <div class="action-buttons">
                                                        <a href="<?= APP_URL ?>/client/viewBooking/<?= $booking['id'] ?>" 
                                                           class="btn btn-sm btn-info view-booking" 
                                                           title="View Details"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if ($booking['status'] === 'pending'): ?>
                                                            <a href="<?= APP_URL ?>/client/cancelBooking/<?= $booking['id'] ?>" 
                                                               class="btn btn-sm btn-danger cancel-booking" 
                                                               onclick="return confirm('Are you sure you want to cancel this booking?');" 
                                                               title="Cancel Booking"
                                                               data-bs-toggle="tooltip">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                            <?php endif; ?>
                        </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div>
                            <?php else: ?>
                            <div class="text-center py-5 empty-state">
                                <img src="<?= APP_URL ?>/assets/img/no-bookings.svg" alt="No bookings" class="mb-4 no-bookings-img">
                                <h3 class="no-bookings-title">No Bookings Found</h3>
                                <p class="no-bookings-text">You haven't made any bookings yet. Start by booking a service!</p>
                                <a href="<?= APP_URL ?>/services" class="btn btn-primary btn-lg btn-animate">
                                    <i class="fas fa-plus me-2"></i>Book a Service
                                </a>
                            </div>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade custom-modal" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<style>
/* Variables */
:root {
    --primary-color: #4e73df;
    --primary-hover: #2e59d9;
    --secondary-color: #858796;
    --success-color: #1cc88a;
    --info-color: #36b9cc;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --light-color: #f8f9fc;
    --bg-light: #f8f9fc;
    --dark-color: #5a5c69;
    --border-color: #e3e6f0;
    --shadow-color: rgba(58, 59, 69, 0.15);
    --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    --transition: all 0.3s ease;
}

/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light-color);
    color: var(--dark-color);
    line-height: 1.6;
}

.bookings-container {
    padding: 2rem 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
}

.section-title {
    color: var(--dark-color);
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 0;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 2px;
}

/* Card Styles */
.card {
    border: none;
    box-shadow: var(--card-shadow);
    border-radius: 1rem;
    transition: var(--transition);
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
}

.filter-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
}

.filter-card .card-body {
    padding: 2rem;
}

.filter-card .form-label {
    color: white;
    font-weight: 500;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    opacity: 0.9;
}

/* Form Controls */
.input-wrapper, .select-wrapper {
    position: relative;
}

.input-wrapper .input-icon,
.select-wrapper .select-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary-color);
    pointer-events: none;
    transition: var(--transition);
}

.custom-input, .custom-select {
    height: 3.25rem;
    border-radius: 0.75rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    font-weight: 500;
    transition: var(--transition);
    background-color: rgba(255, 255, 255, 0.9);
}

.custom-input:focus, .custom-select:focus {
    border-color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    background-color: white;
}

.custom-input:focus + .input-icon,
.custom-select:focus + .select-icon {
    color: var(--primary-color);
}

/* Table Styles */
.custom-table {
    margin-bottom: 0;
}

.custom-table th {
    font-weight: 600;
    background-color: var(--light-color);
    border-bottom: 2px solid var(--border-color);
    padding: 1.25rem 1rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--dark-color);
}

.custom-table td {
    vertical-align: middle;
    padding: 1.25rem 1rem;
    border-bottom: 1px solid var(--border-color);
    font-size: 0.95rem;
}

.custom-table tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

/* Badge Styles */
.status-badge {
    padding: 0.6rem 1rem;
    font-weight: 500;
    font-size: 0.85rem;
    border-radius: 2rem;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Button Styles */
.btn {
    border-radius: 0.75rem;
    font-weight: 500;
    transition: var(--transition);
    letter-spacing: 0.3px;
}

.btn-animate {
    position: relative;
    overflow: hidden;
}

.btn-animate::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.1);
    transform: translateX(-100%);
    transition: var(--transition);
}

.btn-animate:hover::before {
    transform: translateX(0);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
}

.btn-sm {
    padding: 0.5rem 0.85rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-start;
    align-items: center;
}

.view-booking, .cancel-booking {
    transition: var(--transition);
}

.view-booking:hover, .cancel-booking:hover {
    transform: translateY(-2px);
}

/* Empty State Styles */
.empty-state {
    padding: 3rem 0;
}

.no-bookings-img {
    max-width: 250px;
    margin-bottom: 2rem;
    opacity: 0.8;
    transition: var(--transition);
}

.empty-state:hover .no-bookings-img {
    transform: scale(1.05);
}

.no-bookings-title {
    color: var(--dark-color);
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.75rem;
}

.no-bookings-text {
    color: var(--secondary-color);
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Modal Styles */
.custom-modal .modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
}

.custom-modal .modal-header {
    background-color: var(--light-color);
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
}

.custom-modal .modal-title {
    color: var(--dark-color);
        font-weight: 600;
    font-size: 1.25rem;
}

.custom-modal .modal-body {
    padding: 2rem;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .section-title {
        font-size: 1.5rem;
    }
    
    .card {
        margin-bottom: 1.5rem;
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    .custom-table td, 
    .custom-table th {
        padding: 1rem 0.75rem;
    }

    .status-badge {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-sm {
        width: 100%;
        padding: 0.5rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0);
    }
}

.bookings-container {
    animation: fadeIn 0.5s ease-out;
}

/* Loading Animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.navbar {
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 0.75rem 0;
}

.navbar-brand img {
    height: 40px;
    width: auto;
}

.dropdown-toggle {
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.dropdown-toggle:hover {
    background-color: rgba(0,0,0,0.05);
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 10px;
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
}

.dropdown-item:hover {
    background-color: var(--bg-light);
}


.status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.status-dot.online {
    background-color: var(--success-color);
}

.user-name {
    font-weight: 500;
    color: var(--dark-color);
}

.user-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Responsive Header Styles */
@media (max-width: 991px) {
    .navbar-collapse {
        background: #ffffff;
        padding: 1rem;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }

    .nav-link {
        padding: 0.75rem 1rem;
    }

    .user-dropdown {
        width: 100%;
        justify-content: flex-start;
        margin-top: 0.5rem;
    }

    .dropdown-menu {
        position: static !important;
        box-shadow: none;
        padding: 0;
        margin-top: 0.5rem;
        border: 1px solid var(--border-color);
    }
    }

.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
}

.user-avatar-placeholder {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover, #2e59d9) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.google-badge {
    position: absolute;
    bottom: -2px;
    right: -2px;
    background: #fff;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #4285F4;
    border: 1px solid #e0e0e0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Initialize tooltips -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

</body>
</html>
