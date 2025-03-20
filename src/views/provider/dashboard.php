<?php
/**
 * Provider Dashboard Template
 * Main dashboard view for service providers
 */

$title = "Dashboard";
include SRC_PATH . '/views/layouts/provider-header.php';
?>

<div class="container-fluid px-4">  <!-- Changed from container-fluid to container-fluid px-4 -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <!-- Removed border-bottom class -->
                <h1 class="h2 mb-0">Provider Dashboard</h1>  <!-- Added mb-0 -->
                <div class="btn-toolbar mb-0">  <!-- Changed mb-2 mb-md-0 to mb-0 -->
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='<?= APP_URL ?>/provider/services/add'">
                        <i class="fas fa-plus"></i> Add New Service
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">  <!-- Added g-3 for better gap control -->
        <div class="col-md-3">  <!-- Removed mb-3 class -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary text-uppercase mb-1">PENDING BOOKINGS</h6>
                            <h4 class="mb-0 font-weight-bold"><?= $pending_bookings ?? 0 ?></h4>
                        </div>
                        <div>
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">  <!-- Removed mb-3 class -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success text-uppercase mb-1">EARNINGS (MONTHLY)</h6>
                            <h4 class="mb-0 font-weight-bold">$<?= number_format($monthly_earnings ?? 0, 2) ?></h4>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">  <!-- Removed mb-3 class -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info text-uppercase mb-1">COMPLETION RATE</h6>
                            <h4 class="mb-0 font-weight-bold"><?= $completion_rate ?? 0 ?>%</h4>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: <?= $completion_rate ?? 0 ?>%" 
                                     aria-valuenow="<?= $completion_rate ?? 0 ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">  <!-- Removed mb-3 class -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning text-uppercase mb-1">AVERAGE RATING</h6>
                            <h4 class="mb-0 font-weight-bold">
                                <?= $average_rating ?? '0.0' ?> <i class="fas fa-star text-warning"></i>
                            </h4>
                        </div>
                        <div>
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Upcoming Bookings</h6>
            <a href="<?= APP_URL ?>/provider/bookings" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($upcomingBookings)): ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No upcoming bookings found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
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
                            <?php foreach ($upcomingBookings as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['client_first_name'] . ' ' . $booking['client_last_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($booking['start_time'])) ?></td>
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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Recent Reviews</h6>
            <a href="<?= APP_URL ?>/provider/reviews" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentReviews)): ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No reviews found yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReviews as $review): ?>
                                <tr>
                                    <td><?= htmlspecialchars($review['client_name']) ?></td>
                                    <td><?= htmlspecialchars($review['service_name']) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </td>
                                    <td><?= htmlspecialchars($review['comment']) ?></td>
                                    <td><?= date('M d, Y', strtotime($review['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add custom styles at the bottom of the file -->
    <style>
        /* Custom padding and margin adjustments */
        .container-fluid {
            max-width: 1600px;
        }

        .card {
            margin-bottom: 1rem;
        }

        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table td, .table th {
            padding: 0.75rem 1rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</div>

<?php include SRC_PATH . '/views/layouts/provider-footer.php'; ?>