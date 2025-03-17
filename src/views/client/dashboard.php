<?php /* Header is already included by the Controller */ ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="welcome-title">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
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
                                <h3><?= count($upcomingBookings) ?></h3>
                                <p>Upcoming Bookings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="fas fa-history stat-icon"></i>
                            <div class="stat-info">
                                <h3><?= count($recentBookings) ?></h3>
                                <p>Recent Services</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="fas fa-star stat-icon"></i>
                            <div class="stat-info">
                                <h3><?= count($featuredServices) ?></h3>
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
                                            <td><?= htmlspecialchars($booking['provider_first_name'] . ' ' . $booking['provider_last_name']) ?></td>
                                            <td><span class="badge bg-<?= $this->getStatusBadgeClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
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
                                            <td><?= htmlspecialchars($booking['provider_first_name'] . ' ' . $booking['provider_last_name']) ?></td>
                                            <td><span class="badge bg-<?= $this->getStatusBadgeClass($booking['status']) ?>"><?= ucfirst($booking['status']) ?></span></td>
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
    <div class="row mt-4">
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
                                                <img src="<?= APP_URL ?>/public/assets/img/service-placeholder.jpg" alt="Service Placeholder">
                                            <?php endif; ?>
                                        </div>
                                        <div class="service-info">
                                            <h4><?= htmlspecialchars($service['name']) ?></h4>
                                            <p class="service-price">$<?= number_format($service['price'], 2) ?> / <?= htmlspecialchars($service['price_unit']) ?></p>
                                            <p class="service-provider">By: <?= htmlspecialchars($service['provider_name']) ?></p>
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

<style>
    .welcome-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .stats-container {
        margin-bottom: 25px;
    }
    
    .stat-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        display: flex;
        align-items: center;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-right: 15px;
        color: #4e73df;
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
        color: #4e73df;
    }
    
    .stat-info p {
        margin: 0;
        color: #6c757d;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.5rem;
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
    }
    
    .booking-table th {
        font-weight: 600;
        border-top: none;
    }
    
    .service-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s;
        height: 100%;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
    }
    
    .service-img {
        height: 150px;
        overflow: hidden;
    }
    
    .service-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .service-info {
        padding: 15px;
    }
    
    .service-info h4 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .service-price {
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 5px;
    }
    
    .service-provider {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 12px;
    }
</style>

<?php /* Footer is already included by the Controller */ ?> 