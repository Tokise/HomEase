<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | HomEase</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/assets/css/dashboard.css" rel="stylesheet">
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
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($user['first_name']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/client/profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/client/bookings"><i class="fas fa-calendar me-2"></i>My Bookings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="welcome-title">Welcome, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                <p class="text-muted">Manage your home services bookings and discover new services</p>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Dashboard Statistics -->
            <div class="col-md-12">
                <div class="stats-container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="fas fa-calendar-check stat-icon"></i>
                                <div class="stat-info">
                                    <h3><?= count($upcomingBookings ?? []) ?></h3>
                                    <p>Upcoming Bookings</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="fas fa-history stat-icon"></i>
                                <div class="stat-info">
                                    <h3><?= count($recentBookings ?? []) ?></h3>
                                    <p>Recent Services</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="fas fa-star stat-icon"></i>
                                <div class="stat-info">
                                    <h3><?= count($featuredServices ?? []) ?></h3>
                                    <p>Recommended For You</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Upcoming Bookings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-calendar-alt me-2"></i> Upcoming Bookings</h5>
                        <a href="<?= APP_URL ?>/client/bookings" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($upcomingBookings)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover booking-table">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Provider</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($upcomingBookings as $booking): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                                <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                                <td><?= date('h:i A', strtotime($booking['start_time'])) ?></td>
                                                <td><?= htmlspecialchars($booking['provider_name']) ?></td>
                                                <td><span class="badge bg-<?= $this->getBookingStatusClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                You don't have any upcoming bookings. 
                                <a href="<?= APP_URL ?>/services" class="alert-link">Explore services</a> to book your first appointment.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-history me-2"></i> Recent Bookings</h5>
                        <a href="<?= APP_URL ?>/client/bookings" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentBookings)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover booking-table">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Date</th>
                                            <th>Provider</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentBookings as $booking): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                                <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                                <td><?= htmlspecialchars($booking['provider_name']) ?></td>
                                                <td><span class="badge bg-<?= $this->getBookingStatusClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
                                                <td>
                                                    <?php if ($booking['status'] === 'completed'): ?>
                                                        <a href="<?= APP_URL ?>/reviews/add/<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary">Review</a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-secondary" disabled>Review</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                You don't have any recent bookings.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Services -->
        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-star me-2"></i> Recommended Services</h5>
                        <a href="<?= APP_URL ?>/services" class="btn btn-sm btn-outline-primary">View All Services</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($featuredServices)): ?>
                            <div class="row">
                                <?php foreach ($featuredServices as $service): ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="service-card">
                                            <div class="service-img">
                                                <?php if (!empty($service['image'])): ?>
                                                    <img src="<?= APP_URL ?>/<?= $service['image'] ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                                                <?php else: ?>
                                                    <img src="<?= APP_URL ?>/assets/img/service-placeholder.jpg" alt="Service Placeholder">
                                                <?php endif; ?>
                                            </div>
                                            <div class="service-info">
                                                <h4><?= htmlspecialchars($service['name']) ?></h4>
                                                <p class="service-category"><?= htmlspecialchars($service['category_name']) ?></p>
                                                <a href="<?= APP_URL ?>/services/view/<?= $service['id'] ?>" class="btn btn-primary btn-sm w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                No featured services available at the moment.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<style>
:root {
    --primary-color: #4e73df;
    --text-primary: #333;
    --text-secondary: #6c757d;
    --bg-light: #f8f9fc;
    --border-color: #e3e6f0;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--bg-light);
    color: var(--text-primary);
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
}

.dropdown-toggle::after {
    margin-left: 0.5rem;
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

.dropdown-divider {
    margin: 0.5rem 0;
}

.welcome-title {
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 2rem;
}

.stats-container {
    margin-bottom: 25px;
}

.stat-card {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 20px;
    display: flex;
    align-items: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.stat-icon {
    font-size: 2.5rem;
    margin-right: 15px;
    color: var(--primary-color);
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(78, 115, 223, 0.1);
    border-radius: 50%;
}

.stat-info h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: var(--primary-color);
}

.stat-info p {
    margin: 0;
    color: var(--text-secondary);
    font-weight: 500;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    background-color: var(--bg-light);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 1.25rem;
}

.card-header h5 {
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

.booking-table th {
    font-weight: 600;
    border-top: none;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.booking-table td {
    font-size: 0.95rem;
    vertical-align: middle;
}

.service-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.service-img {
    height: 160px;
    overflow: hidden;
}

.service-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.service-info {
    padding: 1.25rem;
    background: #fff;
}

.service-info h4 {
    font-size: 1.1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.service-category {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 15px;
    font-weight: 500;
}

.btn {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
}

.alert {
    border-radius: 10px;
    font-weight: 500;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 6px;
}
</style>

<?php
// Helper function for booking status badge class
function getBookingStatusClass($status) {
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
?>

<?php /* Footer is already included by the Controller */ ?> 