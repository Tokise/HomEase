<?php
/**
 * Provider Dashboard Template
 * Main dashboard view for service providers
 */

$title = "Dashboard";
include '../layouts/provider-header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= APP_URL ?>/provider/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/bookings">
                            <i class="fas fa-calendar-check me-2"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/services">
                            <i class="fas fa-tools me-2"></i>
                            My Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/reviews">
                            <i class="fas fa-star me-2"></i>
                            Reviews
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/earnings">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Earnings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/profile">
                            <i class="fas fa-user-cog me-2"></i>
                            Profile Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Provider Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.location.href='<?= APP_URL ?>/provider/services/add'">
                            <i class="fas fa-plus"></i> Add New Service
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Pending Bookings</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pending_bookings ?? 0 ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Earnings (Monthly)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($monthly_earnings ?? 0, 2) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Completion Rate</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $completion_rate ?? 0 ?>%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar" 
                                                    style="width: <?= $completion_rate ?? 0 ?>%" 
                                                    aria-valuenow="<?= $completion_rate ?? 0 ?>" aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Average Rating</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $average_rating ?? '0.0' ?> <i class="fas fa-star text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Bookings</h6>
                    <a href="<?= APP_URL ?>/provider/bookings" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($upcoming_bookings)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No upcoming bookings found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcoming_bookings as $booking): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($booking['client_name']) ?></td>
                                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                            <td><?= date('h:i A', strtotime($booking['booking_time'])) ?></td>
                                            <td>
                                                <?php if ($booking['status'] == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($booking['status'] == 'confirmed'): ?>
                                                    <span class="badge bg-primary">Confirmed</span>
                                                <?php elseif ($booking['status'] == 'completed'): ?>
                                                    <span class="badge bg-success">Completed</span>
                                                <?php elseif ($booking['status'] == 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= APP_URL ?>/provider/bookings/view/<?= $booking['id'] ?>" 
                                                       class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($booking['status'] == 'pending'): ?>
                                                        <a href="<?= APP_URL ?>/provider/bookings/confirm/<?= $booking['id'] ?>" 
                                                           class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Confirm">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($booking['status'] == 'confirmed'): ?>
                                                        <a href="<?= APP_URL ?>/provider/bookings/complete/<?= $booking['id'] ?>" 
                                                           class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Mark Completed">
                                                            <i class="fas fa-check-double"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                                                        <a href="<?= APP_URL ?>/provider/bookings/cancel/<?= $booking['id'] ?>" 
                                                           class="btn btn-sm btn-danger cancel-booking" data-id="<?= $booking['id'] ?>"
                                                           data-bs-toggle="tooltip" title="Cancel">
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
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reviews</h6>
                    <a href="<?= APP_URL ?>/provider/reviews" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_reviews)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No reviews found yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($recent_reviews as $review): ?>
                                <div class="col-lg-6 mb-4">
                                    <div class="card border-left-info">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <h6 class="font-weight-bold"><?= htmlspecialchars($review['client_name']) ?></h6>
                                                    <small class="text-muted"><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
                                                </div>
                                                <div>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= $review['rating']): ?>
                                                            <i class="fas fa-star text-warning"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star text-warning"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <p class="mb-1"><?= htmlspecialchars($review['comment']) ?></p>
                                            <small class="text-muted">Service: <?= htmlspecialchars($review['service_name']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../layouts/provider-footer.php'; ?> 