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
</head>
<body>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= APP_URL ?>">
                <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomeSwift" height="40">
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar-wrapper">
                            <?php if (!empty($user['google_picture'])): ?>
                                <img src="<?= htmlspecialchars($user['google_picture']) ?>" 
                                     alt="Profile" class="user-avatar"
                                     referrerpolicy="no-referrer">
                            <?php elseif (!empty($user['profile_picture'])): ?>
                                <img src="<?= APP_URL . '/' . ltrim($user['profile_picture'], '/') ?>" 
                                     alt="Profile" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar-placeholder">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
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

    <!-- Page Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/client/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Book Service</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Book Service</h4>
                    </div>
                    <div class="card-body">
                        <!-- Service Details -->
                        <div class="service-details mb-4">
                            <h5><?= htmlspecialchars($service['name']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($service['description']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info"><?= htmlspecialchars($service['category_name']) ?></span>
                                <span class="h5 mb-0">$<?= number_format($service['price'], 2) ?></span>
                            </div>
                        </div>

                        <hr>

                        <!-- Booking Form -->
                        <form action="<?= APP_URL ?>/booking/create" method="POST">
                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                            <input type="hidden" name="provider_id" value="<?= $service['provider_id'] ?>">
                            <input type="hidden" name="total_price" value="<?= $service['price'] ?>">

                            <!-- Date Selection -->
                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Select Date</label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                       min="<?= date('Y-m-d') ?>" required>
                            </div>

                            <!-- Time Selection -->
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Select Time</label>
                                <div class="time-slots-grid">
                                    <?php foreach ($timeSlots as $slot): ?>
                                        <div class="form-check time-slot-option">
                                            <input type="radio" class="form-check-input" 
                                                   id="time_<?= str_replace(':', '', $slot) ?>"
                                                   name="start_time" 
                                                   value="<?= $slot ?>" required>
                                            <label class="form-check-label" 
                                                   for="time_<?= str_replace(':', '', $slot) ?>">
                                                <?= date('h:i A', strtotime($slot)) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                        placeholder="Any special instructions or requirements..."></textarea>
                            </div>

                            <!-- Booking Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-title">Booking Summary</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Service:</strong> <?= htmlspecialchars($service['name']) ?></p>
                                            <p class="mb-1"><strong>Duration:</strong> <?= $service['duration'] ?> minutes</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Price:</strong> $<?= number_format($service['price'], 2) ?></p>
                                            <p class="mb-1"><strong>Provider:</strong> <?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Confirm Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('booking_date').addEventListener('change', function() {
        const date = this.value;
        const serviceId = <?= $service['id'] ?>;
        const timeSlotContainer = document.querySelector('.time-slots-grid');
        
        // Fetch available time slots for selected date
        fetch(`<?= APP_URL ?>/booking/getTimeSlots?service_id=${serviceId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                const availableSlots = new Set(data.slots);
                
                // Update time slots UI
                document.querySelectorAll('.time-slot-option').forEach(slot => {
                    const input = slot.querySelector('input');
                    if (!availableSlots.has(input.value)) {
                        slot.classList.add('disabled');
                        input.disabled = true;
                    } else {
                        slot.classList.remove('disabled');
                        input.disabled = false;
                    }
                });
            })
            .catch(error => console.error('Error fetching time slots:', error));
    });
    </script>

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
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 15px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        .service-details {
            background-color: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 2rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .user-avatar, .user-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-avatar-placeholder {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a5cbe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
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

        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot-option {
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .time-slot-option:hover {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .time-slot-option .form-check-input {
            display: none;
        }

        .time-slot-option .form-check-label {
            display: block;
            cursor: pointer;
            font-weight: 500;
            margin: 0;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .time-slot-option .form-check-input:checked + .form-check-label {
            background-color: var(--primary-color);
            color: #fff;
        }

        .time-slot-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .time-slot-option.disabled label {
            cursor: not-allowed;
        }
    </style>
</body>
</html>


