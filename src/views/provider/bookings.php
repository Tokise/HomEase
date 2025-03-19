<?php
/**
 * Provider Bookings Template
 * Page for managing service bookings
 */

$title = "Bookings";
include SRC_PATH . '/views/layouts/provider-header.php';
?>

<style>
.container-fluid {
    padding: 1.5rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.page-header h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.75rem;
    font-weight: 600;
}

/* Card Styles */
.card {
    background: #fff;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
}

.card-header {
    padding: 1.25rem;
    border-bottom: 1px solid #e3e6f0;
    background: #fff;
    border-radius: 0.5rem 0.5rem 0 0;
}

.card-body {
    padding: 1.25rem;
}

/* Tab Navigation */
.nav-tabs {
    border-bottom: none;
    gap: 0.5rem;
    padding: 0 0.5rem;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
    color: #4e73df;
    background: rgba(78, 115, 223, 0.1);
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    background: rgba(78, 115, 223, 0.1);
    border-bottom: 2px solid #4e73df;
}

/* Badge Styles */
.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    border-radius: 0.375rem;
}

.badge.bg-warning { background-color: #f6c23e !important; }
.badge.bg-primary { background-color: #4e73df !important; }
.badge.bg-success { background-color: #1cc88a !important; }
.badge.bg-danger { background-color: #e74a3b !important; }
.badge.bg-secondary { background-color: #858796 !important; }

/* Search and Filter Section */
.search-filter {
    padding: 1.25rem;
    background: #f8f9fc;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.input-group {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
    overflow: hidden;
}

.input-group .form-control {
    border: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
}

.input-group .btn {
    padding: 0.75rem 1.25rem;
    font-weight: 500;
}

.form-select {
    border: 1px solid #e3e6f0;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background-color: #fff;
}

/* Table Styles */
.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.booking-table {
    margin: 0;
    width: 100%;
}

.booking-table thead th {
    background: #f8f9fc;
    color: #4e73df;
    font-weight: 600;
    padding: 1rem;
    border-bottom: 2px solid #e3e6f0;
}

.booking-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e3e6f0;
}

.booking-table tbody tr {
    transition: all 0.2s;
}

.booking-table tbody tr:hover {
    background: #f8f9fc;
}

/* Client Photo Styles */
.client-photo {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    object-fit: cover;
}

.client-photo-placeholder {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #858796;
}

/* Button Styles */
.btn-group {
    display: flex;
    gap: 0.375rem;
}

.btn-group .btn {
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
}

.btn-outline-primary {
    color: #4e73df;
    border-color: #4e73df;
}

.btn-outline-primary:hover {
    background: #4e73df;
    color: #fff;
}

/* Empty State Styles */
.empty-state {
    padding: 3rem 1.5rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    color: #dddfeb;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #858796;
    margin: 0;
    font-size: 1rem;
}

/* Today's Schedule Section */
.today-schedule {
    margin-top: 2rem;
}

.today-schedule .card-header {
    background: linear-gradient(45deg, #4e73df, #3867d6);
    color: #fff;
}

.today-schedule .card-header h6 {
    margin: 0;
    font-weight: 600;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .search-filter .row > div {
        margin-bottom: 1rem;
    }
    
    .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    
    .nav-tabs .nav-link {
        white-space: nowrap;
    }
    
    .btn-group {
        flex-wrap: wrap;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.375rem;
    }
    
    .booking-table {
        min-width: 800px;
    }
}
</style>

        <!-- Main Content -->
<div class="container-fluid">
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

<?php include SRC_PATH . '/views/layouts/provider-footer.php'; ?>
 