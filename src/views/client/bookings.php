<?php /* Header is already included by the Controller */ ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/client/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="page-title">My Bookings</h1>
            <p class="text-muted">View and manage all your service bookings</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="<?= APP_URL ?>/services" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Book New Service
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="bookingsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                                All Bookings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">
                                Upcoming
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                Completed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                                Cancelled
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="bookingsTabContent">
                        <!-- All Bookings Tab -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <?php if (empty($bookings)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You don't have any bookings yet. 
                                    <a href="<?= APP_URL ?>/services" class="alert-link">Browse our services</a> to make your first booking.
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingsTable($bookings); ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Upcoming Bookings Tab -->
                        <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            <?php 
                                $upcomingBookings = array_filter($bookings, function($booking) {
                                    return $booking['status'] != 'completed' && $booking['status'] != 'cancelled' && 
                                           (strtotime($booking['booking_date']) > time() || 
                                           (strtotime($booking['booking_date']) == strtotime(date('Y-m-d')) && 
                                            strtotime($booking['start_time']) > time()));
                                });
                                
                                if (empty($upcomingBookings)):
                            ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You don't have any upcoming bookings.
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingsTable($upcomingBookings); ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Completed Bookings Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <?php 
                                $completedBookings = array_filter($bookings, function($booking) {
                                    return $booking['status'] == 'completed';
                                });
                                
                                if (empty($completedBookings)):
                            ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You don't have any completed bookings yet.
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingsTable($completedBookings); ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Cancelled Bookings Tab -->
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            <?php 
                                $cancelledBookings = array_filter($bookings, function($booking) {
                                    return $booking['status'] == 'cancelled';
                                });
                                
                                if (empty($cancelledBookings)):
                            ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You don't have any cancelled bookings.
                                </div>
                            <?php else: ?>
                                <?php $this->renderBookingsTable($cancelledBookings); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.5rem;
        margin-bottom: 30px;
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 12px 20px;
    }
    
    .nav-tabs .nav-link.active {
        color: #4e73df;
        font-weight: 600;
        border-bottom: 2px solid #4e73df;
        background-color: transparent;
    }
    
    .booking-table th {
        font-weight: 600;
        background-color: #f8f9fc;
    }
    
    .booking-date {
        min-width: 100px;
    }
    
    .booking-time {
        min-width: 100px;
    }
    
    .booking-actions .btn {
        margin-right: 5px;
    }
    
    .booking-actions .btn:last-child {
        margin-right: 0;
    }
</style>

<?php /* Footer is already included by the Controller */ ?> 