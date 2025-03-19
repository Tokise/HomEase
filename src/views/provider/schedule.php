<?php
/**
 * Provider Schedule Template
 */
?>

<!-- Provider Schedule Page -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Manage Schedule</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/provider/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Availability</h5>
                </div>
                <div class="card-body">
                    <form action="<?= APP_URL ?>/provider/manageSchedule" method="post">
                        <div class="mb-3">
                            <label for="day_of_week" class="form-label">Day of Week</label>
                            <select class="form-select" id="day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                <option value="0">Monday</option>
                                <option value="1">Tuesday</option>
                                <option value="2">Wednesday</option>
                                <option value="3">Thursday</option>
                                <option value="4">Friday</option>
                                <option value="5">Saturday</option>
                                <option value="6">Sunday</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Add Availability</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Availability</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($availability)): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> No availability set yet. Please add your availability using the form.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availability as $slot): ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                echo $days[$slot['day_of_week']];
                                                ?>
                                            </td>
                                            <td><?= date('h:i A', strtotime($slot['start_time'])) ?></td>
                                            <td><?= date('h:i A', strtotime($slot['end_time'])) ?></td>
                                            <td>
                                                <a href="<?= APP_URL ?>/provider/deleteAvailability/<?= $slot['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this availability?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Weekly Schedule Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
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
                                $times = [];
                                $start = strtotime('08:00:00');
                                $end = strtotime('18:00:00');
                                $interval = 60 * 60; // 1 hour

                                for ($i = $start; $i <= $end; $i += $interval) {
                                    $times[] = date('H:i', $i);
                                }

                                // Create schedule array
                                $schedule = [];
                                foreach ($availability as $slot) {
                                    $day = $slot['day_of_week'];
                                    $start = date('H:i', strtotime($slot['start_time']));
                                    $end = date('H:i', strtotime($slot['end_time']));
                                    $schedule[$day][$start] = $end;
                                }

                                foreach ($times as $time) {
                                    echo '<tr>';
                                    echo '<td>' . date('h:i A', strtotime($time)) . '</td>';
                                    
                                    for ($day = 0; $day < 7; $day++) {
                                        $timeFormatted = $time;
                                        $isAvailable = false;
                                        
                                        foreach ($schedule[$day] ?? [] as $start => $end) {
                                            if ($timeFormatted >= $start && $timeFormatted < $end) {
                                                $isAvailable = true;
                                                break;
                                            }
                                        }
                                        
                                        $class = $isAvailable ? 'bg-success-subtle text-success' : 'bg-light text-muted';
                                        $text = $isAvailable ? 'Available' : 'Not Available';
                                        
                                        echo '<td class="' . $class . '">' . $text . '</td>';
                                    }
                                    
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation for time inputs
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    endTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && endTimeInput.value) {
            if (endTimeInput.value <= startTimeInput.value) {
                alert('End time must be after start time');
                endTimeInput.value = '';
            }
        }
    });
});
</script>

<style>
/* Schedule Form Styles */
.form-control, .form-select {
    border: 1px solid #e3e6f0;
    border-radius: 6px;
    padding: 10px 15px;
    font-size: 0.9rem;
    transition: border-color 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-label {
    font-weight: 500;
    color: #5a5c69;
    margin-bottom: 8px;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    margin-bottom: 24px;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid #e3e6f0;
    padding: 16px;
}

.card-title {
    color: #4e73df;
    font-weight: 600;
    margin: 0;
}

.card-body {
    padding: 20px;
}

/* Table Styles */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f8f9fc;
    color: #4e73df;
    font-weight: 600;
    border-bottom: 2px solid #e3e6f0;
}

.table tbody td {
    vertical-align: middle;
    padding: 12px;
}

/* Weekly Schedule Overview */
.table-bordered {
    border: 1px solid #e3e6f0;
}

.table-bordered td, .table-bordered th {
    border: 1px solid #e3e6f0;
}

.bg-success-subtle {
    background-color: #e6f4ea !important;
}

.text-success {
    color: #28a745 !important;
}

.bg-light {
    background-color: #f8f9fc !important;
}

/* Button Styles */
.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-danger {
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Alert Styles */
.alert {
    border-radius: 8px;
    border: none;
    padding: 16px;
}

.alert-info {
    background-color: #e1f0ff;
    color: #2c7be5;
}

.alert i {
    margin-right: 8px;
}

/* Breadcrumb Styles */
.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #4e73df;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #858796;
}

/* Time Input Styles */
input[type="time"] {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e3e6f0;
}

input[type="time"]:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: none;
}

/* Page Title Styles */
.page-title-box {
    margin-bottom: 24px;
}

.page-title-box h4 {
    color: #5a5c69;
    font-weight: 600;
}

.page-title-right {
    margin-top: 8px;
}
</style> 