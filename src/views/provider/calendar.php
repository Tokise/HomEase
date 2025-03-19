<?php
/**
 * Provider Calendar Template
 */
$year = date('Y', strtotime($currentDate));
$month = date('m', strtotime($currentDate));
$day = date('d', strtotime($currentDate));
$currentMonth = date('F Y', strtotime($currentDate));
$daysInMonth = date('t', strtotime($currentDate));
$firstDayOfMonth = date('N', strtotime($year . '-' . $month . '-01'));
?>
<?php include SRC_PATH . '/views/layouts/provider-header.php'; ?>

<!-- Provider Calendar Page -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Calendar Overview</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/provider/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><?= $currentMonth ?></h5>
                        </div>
                        <div class="calendar-nav">
                            <?php
                            $prevMonth = date('Y-m-d', strtotime('-1 month', strtotime($currentDate)));
                            $nextMonth = date('Y-m-d', strtotime('+1 month', strtotime($currentDate)));
                            ?>
                            <a href="<?= APP_URL ?>/provider/calendar?date=<?= $prevMonth ?>" class="btn btn-sm btn-light me-2">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                            <a href="<?= APP_URL ?>/provider/calendar?date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-light me-2">
                                Today
                            </a>
                            <a href="<?= APP_URL ?>/provider/calendar?date=<?= $nextMonth ?>" class="btn btn-sm btn-light">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Sunday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Prepare bookings by date for faster lookup
                                $bookingsByDate = [];
                                foreach ($bookings as $booking) {
                                    $date = $booking['booking_date'];
                                    if (!isset($bookingsByDate[$date])) {
                                        $bookingsByDate[$date] = [];
                                    }
                                    $bookingsByDate[$date][] = $booking;
                                }

                                // Calendar generation
                                $day = 1;
                                $cellCount = 0;
                                
                                // Calculate offset for the first day
                                $offset = $firstDayOfMonth - 1; // Adjust to 0-based index where Monday is 0

                                echo '<tr>';
                                
                                // Empty cells for days before the start of the month
                                for ($i = 0; $i < $offset; $i++) {
                                    echo '<td class="calendar-day empty"></td>';
                                    $cellCount++;
                                }
                                
                                // Days of the month
                                while ($day <= $daysInMonth) {
                                    if ($cellCount % 7 === 0 && $cellCount !== 0) {
                                        echo '</tr><tr>';
                                    }

                                    $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $isToday = $date === date('Y-m-d');
                                    $cellClass = $isToday ? 'today' : '';
                                    
                                    echo '<td class="calendar-day ' . $cellClass . '">';
                                    echo '<div class="day-number' . ($isToday ? ' bg-primary text-white' : '') . '">' . $day . '</div>';
                                    
                                    // Show bookings for this day
                                    if (isset($bookingsByDate[$date])) {
                                        echo '<div class="calendar-events">';
                                        foreach ($bookingsByDate[$date] as $booking) {
                                            $startTime = date('h:i A', strtotime($booking['start_time']));
                                            $statusClass = '';
                                            
                                            switch ($booking['status']) {
                                                case 'confirmed':
                                                    $statusClass = 'bg-primary';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'bg-warning';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-success';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-secondary';
                                            }
                                            
                                            echo '<a href="' . APP_URL . '/provider/bookings/view/' . $booking['id'] . '" class="calendar-event ' . $statusClass . '">';
                                            echo $startTime . ' - ' . $booking['client_first_name'] . ' ' . $booking['client_last_name'];
                                            echo '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    
                                    echo '</td>';
                                    
                                    $day++;
                                    $cellCount++;
                                }
                                
                                // Empty cells for days after the end of the month
                                while ($cellCount % 7 !== 0) {
                                    echo '<td class="calendar-day empty"></td>';
                                    $cellCount++;
                                }
                                
                                echo '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Schedule for Selected Date -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daily Schedule: <?= date('F d, Y', strtotime($currentDate)) ?></h5>
                    <div class="d-flex">
                        <?php
                        $prevDay = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
                        $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
                        ?>
                        <a href="<?= APP_URL ?>/provider/calendar?date=<?= $prevDay ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-chevron-left"></i> Previous Day
                        </a>
                        <a href="<?= APP_URL ?>/provider/calendar?date=<?= $nextDay ?>" class="btn btn-sm btn-outline-primary">
                            Next Day <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No bookings scheduled for this date.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Sort bookings by start time
                                    usort($bookings, function($a, $b) {
                                        return strtotime($a['start_time']) - strtotime($b['start_time']);
                                    });
                                    
                                    foreach ($bookings as $booking):
                                    ?>
                                        <tr>
                                            <td>
                                                <?= date('h:i A', strtotime($booking['start_time'])) ?> - 
                                                <?= date('h:i A', strtotime($booking['end_time'])) ?>
                                            </td>
                                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                            <td><?= htmlspecialchars($booking['client_first_name'] . ' ' . $booking['client_last_name']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= getStatusBadgeClass($booking['status']) ?>">
                                                    <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= APP_URL ?>/provider/bookings/view/<?= $booking['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                    <a href="<?= APP_URL ?>/provider/bookings/confirm/<?= $booking['id'] ?>" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($booking['status'] === 'confirmed'): ?>
                                                    <a href="<?= APP_URL ?>/provider/bookings/complete/<?= $booking['id'] ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($booking['status'] !== 'cancelled' && $booking['status'] !== 'completed'): ?>
                                                    <a href="<?= APP_URL ?>/provider/bookings/cancel/<?= $booking['id'] ?>" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Layout Styles */
.container-fluid {
    padding: 1.5rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Page Header Styles */
.page-title-box {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.page-title-box h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 600;
}

.breadcrumb {
    margin: 0;
    padding: 0;
}

.breadcrumb-item a {
    color: #4e73df;
    text-decoration: none;
}

/* Calendar Card Styles */
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

.card-header.bg-primary {
    background: linear-gradient(45deg, #4e73df, #3867d6) !important;
}

.card-body {
    padding: 1.25rem;
}

/* Calendar Navigation */
.calendar-nav {
    display: flex;
    gap: 0.5rem;
}

.calendar-nav .btn {
    padding: 0.5rem 1rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.calendar-nav .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
}

/* Calendar Table Styles */
.calendar-table {
    width: 100%;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.calendar-table thead th {
    background: #4e73df;
    color: #fff;
    padding: 1rem;
    text-align: center;
    font-weight: 500;
    border: none;
}

.calendar-day {
    height: 140px;
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #e3e6f0;
    background: #fff;
    transition: all 0.2s;
}

.calendar-day:hover {
    background: #f8f9fc;
}

.calendar-day.empty {
    background: #f8f9fc;
}

.calendar-day.today {
    background: #e8f0fe;
    border: 2px solid #4e73df;
}

.day-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.today .day-number {
    background: #4e73df;
    color: #fff;
}

/* Event Styles */
.calendar-events {
    margin-top: 0.5rem;
    max-height: 90px;
    overflow-y: auto;
    scrollbar-width: thin;
}

.calendar-events::-webkit-scrollbar {
    width: 4px;
}

.calendar-events::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.calendar-events::-webkit-scrollbar-thumb {
    background: #c1c3c7;
    border-radius: 4px;
}

.calendar-event {
    display: block;
    padding: 0.375rem 0.75rem;
    margin-bottom: 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    color: #fff;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: all 0.2s;
}

.calendar-event:hover {
    transform: translateX(2px);
    opacity: 0.9;
    color: #fff;
}

/* Status Colors */
.bg-warning { background-color: #f6c23e !important; }
.bg-primary { background-color: #4e73df !important; }
.bg-success { background-color: #1cc88a !important; }
.bg-danger { background-color: #e74a3b !important; }
.bg-secondary { background-color: #858796 !important; }

/* Daily Schedule Section */
.daily-schedule {
    margin-top: 2rem;
}

.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fc;
    color: #4e73df;
    font-weight: 600;
    padding: 1rem;
    border-bottom: 2px solid #e3e6f0;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

/* Button Styles */
.btn-group .btn {
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin: 0 0.25rem;
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
}

/* Alert Styles */
.alert {
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-info {
    background-color: #e1f0ff;
    color: #2c7be5;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .calendar-nav {
        flex-wrap: wrap;
    }
    
    .calendar-nav .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .calendar-day {
        height: 120px;
    }
}
</style>

<?php
// Helper function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'confirmed':
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