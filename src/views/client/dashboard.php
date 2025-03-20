<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | HomeSwift</title>
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/client.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            HomeSwift
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-wrapper">
                            <?php if (!empty($user['google_picture'])): ?>
                                <img src="<?= htmlspecialchars($user['google_picture']) ?>" 
                                     alt="Profile" 
                                     class="user-avatar"
                                     referrerpolicy="no-referrer"
                                     onerror="this.src='<?= APP_URL ?>/assets/images/default-avatar.png'">
                            <?php elseif (!empty($user['profile_picture'])): ?>
                                <img src="<?= APP_URL . '/' . ltrim($user['profile_picture'], '/') ?>" 
                                     alt="Profile" 
                                     class="user-avatar"
                                     onerror="this.src='<?= APP_URL ?>/assets/images/default-avatar.png'">
                            <?php else: ?>
                                <div class="user-avatar-placeholder">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
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
                                                <td><?= htmlspecialchars($booking['provider_name'] ?? $booking['business_name'] ?? 'N/A') ?></td>
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

        <!-- Available Services -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-concierge-bell me-2"></i>Available Services</h5>
                        <div>
                            <select class="form-select form-select-sm" id="categoryFilter">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="servicesContainer">
                            <?php foreach ($activeServices as $service): ?>
                                <div class="col-md-4 mb-4" data-category="<?= $service['category_id'] ?>">
                                    <div class="service-card">
                                        <?php if (!empty($service['image'])): ?>
                                            <img src="<?= APP_URL ?>/<?= $service['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($service['name']) ?>">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($service['name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars(substr($service['description'], 0, 100)) ?>...</p>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-info"><?= htmlspecialchars($service['category_name']) ?></span>
                                                <span class="text-primary fw-bold">$<?= number_format($service['price'], 2) ?></span>
                                            </div>
                                            <a href="<?= APP_URL ?>/booking/book/<?= $service['id'] ?>" class="btn btn-primary w-100">
                                                <i class="fas fa-calendar-plus me-1"></i> Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Category filter functionality
            document.getElementById('categoryFilter').addEventListener('change', function() {
                const selectedCategory = this.value;
                const services = document.querySelectorAll('#servicesContainer > div');
                
                services.forEach(service => {
                    if (!selectedCategory || service.dataset.category === selectedCategory) {
                        service.style.display = 'block';
                    } else {
                        service.style.display = 'none';
                    }
                });
            });
        </script>

    </div> <!-- Close container -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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