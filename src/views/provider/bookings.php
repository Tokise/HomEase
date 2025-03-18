<?php
/**
 * Provider Bookings Template
 * Page for managing service bookings
 */

$title = "Bookings";
include '../layouts/provider-header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/provider/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= APP_URL ?>/provider/bookings">
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
                <h1 class="h2">Manage Bookings</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= APP_URL ?>/provider/calendar" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-calendar-alt"></i> View Calendar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Booking Status Tabs -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="bookingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" 
                                    type="button" role="tab" aria-controls="all" aria-selected="true">
                                All Bookings <span class="badge bg-secondary ms-1"><?= count($bookings ?? []) ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" 
                                    type="button" role="tab" aria-controls="pending" aria-selected="false">
                                Pending <span class="badge bg-warning text-dark ms-1"><?= $pending_count ?? 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" 
                                    type="button" role="tab" aria-controls="confirmed" aria-selected="false">
                                Confirmed <span class="badge bg-primary ms-1"><?= $confirmed_count ?? 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" 
                                    type="button" role="tab" aria-controls="completed" aria-selected="false">
                                Completed <span class="badge bg-success ms-1"><?= $completed_count ?? 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" 
                                    type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                                Cancelled <span class="badge bg-danger ms-1"><?= $cancelled_count ?? 0 ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchBooking" 
                                       placeholder="Search by client name or service...">
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" id="serviceFilter">
                                        <option value="">All Services</option>
                                        <?php if (!empty($services)): ?>
                                            <?php foreach ($services as $service): ?>
                                                <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="dateFilter">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="bookingTabContent">
                        <!-- All Bookings Tab -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <?php if (empty($bookings)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-check fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No bookings found.</p>
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingTable($bookings); ?>
                            <?php endif; ?>
                        </div>

                        <!-- Pending Bookings Tab -->
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <?php 
                            $pending_bookings = array_filter($bookings ?? [], function($booking) {
                                return $booking['status'] == 'pending';
                            });
                            if (empty($pending_bookings)): 
                            ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-clock fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No pending bookings.</p>
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingTable($pending_bookings); ?>
                            <?php endif; ?>
                        </div>

                        <!-- Confirmed Bookings Tab -->
                        <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                            <?php 
                            $confirmed_bookings = array_filter($bookings ?? [], function($booking) {
                                return $booking['status'] == 'confirmed';
                            });
                            if (empty($confirmed_bookings)): 
                            ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No confirmed bookings.</p>
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingTable($confirmed_bookings); ?>
                            <?php endif; ?>
                        </div>

                        <!-- Completed Bookings Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <?php 
                            $completed_bookings = array_filter($bookings ?? [], function($booking) {
                                return $booking['status'] == 'completed';
                            });
                            if (empty($completed_bookings)): 
                            ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-check-double fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No completed bookings.</p>
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingTable($completed_bookings); ?>
                            <?php endif; ?>
                        </div>

                        <!-- Cancelled Bookings Tab -->
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            <?php 
                            $cancelled_bookings = array_filter($bookings ?? [], function($booking) {
                                return $booking['status'] == 'cancelled';
                            });
                            if (empty($cancelled_bookings)): 
                            ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-times-circle fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No cancelled bookings.</p>
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingTable($cancelled_bookings); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Schedule -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Today's Schedule</h6>
                    <a href="<?= APP_URL ?>/provider/calendar" class="btn btn-sm btn-primary">Full Calendar</a>
                </div>
                <div class="card-body">
                    <?php if (empty($todays_bookings)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No bookings scheduled for today.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todays_bookings as $booking): ?>
                                        <tr>
                                            <td><?= date('h:i A', strtotime($booking['booking_time'])) ?></td>
                                            <td><?= htmlspecialchars($booking['client_name']) ?></td>
                                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
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
        </main>
    </div>
</div>

<!-- Booking Table Template -->
<?php 
function renderBookingTable($bookings) { 
?>
    <div class="table-responsive">
        <table class="table table-hover booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr data-service="<?= $booking['service_id'] ?>" data-date="<?= $booking['booking_date'] ?>">
                        <td>#<?= $booking['booking_id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if (!empty($booking['client_photo'])): ?>
                                    <img src="<?= APP_URL ?>/uploads/users/<?= $booking['client_photo'] ?>" 
                                         class="rounded-circle me-2" width="30" height="30" 
                                         alt="<?= htmlspecialchars($booking['client_name']) ?>">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                         style="width: 30px; height: 30px;">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                                <?= htmlspecialchars($booking['client_name']) ?>
                            </div>
                        </td>
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
                            <?php if ($booking['payment_status'] == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php elseif ($booking['payment_status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Unpaid</span>
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
<?php 
}
?>

<!-- Search and Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchBooking');
    const serviceFilter = document.getElementById('serviceFilter');
    const dateFilter = document.getElementById('dateFilter');
    const bookingTables = document.querySelectorAll('.booking-table');

    // Search functionality
    searchInput.addEventListener('keyup', filterBookings);
    serviceFilter.addEventListener('change', filterBookings);
    dateFilter.addEventListener('change', filterBookings);

    function filterBookings() {
        const searchTerm = searchInput.value.toLowerCase();
        const serviceId = serviceFilter.value;
        const date = dateFilter.value;

        bookingTables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const clientName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const serviceName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const rowServiceId = row.getAttribute('data-service');
                const rowDate = row.getAttribute('data-date');
                
                let showRow = true;
                
                // Apply search filter
                if (searchTerm) {
                    showRow = clientName.includes(searchTerm) || serviceName.includes(searchTerm);
                }
                
                // Apply service filter
                if (showRow && serviceId) {
                    showRow = rowServiceId === serviceId;
                }
                
                // Apply date filter
                if (showRow && date) {
                    showRow = rowDate === date;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        });
    }
});
</script>

<?php include '../layouts/provider-footer.php'; ?>
 