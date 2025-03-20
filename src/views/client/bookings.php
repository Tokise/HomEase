<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - HomeSwift</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/client.css">
</head>
<body>

 <!-- Navigation -->
 <nav class="navbar">
        <div class="container">
            HomeSwift
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-wrapper">
                            <?php if (!empty($user['google_picture'])): ?>
                                <img src="<?= htmlspecialchars($user['google_picture']) ?>" alt="Profile" class="user-avatar">
                            <?php elseif (!empty($user['profile_picture'])): ?>
                                <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($user['google_id'])): ?>
                                <span class="google-badge" title="Google Account">
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
                                                <td class="booking-total fw-medium">$<?= number_format($booking['total_price'], 2) ?></td>
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
/* Remove inline styles as they're now in client.css */
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
