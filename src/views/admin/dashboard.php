<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | HomeSwift Admin</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        main {
            margin-left: 250px;
            padding: 2rem;
            margin-top: 60px;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .h2 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.75rem;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-3px);
        }

        .stats-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-icon.bookings {
            background: rgba(78, 115, 223, 0.1);
            color: #4e73df;
        }

        .stats-icon.services {
            background: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
        }

        .stats-icon.providers {
            background: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }

        .stats-label {
            color: #858796;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Recent Users Section */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #ddd;
            padding: 2rem;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            
        }

        

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            margin: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: #858796;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e3e6f0;
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: #2d3436;
            font-size: 0.875rem;
            border-bottom: 1px solid #e3e6f0;
        }

        /* User Avatar */
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 0.75rem;
            object-fit: cover;
            border: 2px solid #e3e6f0;
        }

        .avatar-placeholder {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            background: #4e73df;
            margin-right: 0.75rem;
            border: 2px solid #4668c7;
        }

        .user-info {
            padding: 0.5rem 0;
            margin-left: 0.5rem;
        }

        .d-flex.align-items-center {
            padding: 0.25rem 0;
        }

        /* Update the user name text styles */
        .user-name {
            margin: 0;
            font-weight: 600;
            font-size: 0.875rem;
            color: #2d3436;
            line-height: 1.4;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 1500;
            border-radius: 6px;
            
        }

        .badge-admin {
            background: rgba(231, 74, 59, 0.1);
            color: #e74a3b;
        }

        .badge-client {
            background: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
        }

        .badge-provider {
            background: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
        }

        @media (max-width: 768px) {
            main {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php require_once SRC_PATH . '/views/layouts/admin-header.php'; ?>
    <?php require_once SRC_PATH . '/views/layouts/admin-sidebar.php'; ?>

    <div class="admin-wrapper">
        <main>
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Dashboard Overview</h1>
                <div class="btn-toolbar">
                    <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Data
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <!-- Total Bookings -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-primary h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Bookings
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $bookingCount ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Services -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-success h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Active Services
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $serviceCount ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Providers -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-info h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Service Providers
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $providerCount ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-warning h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Users
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $userCount ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Users</h5>
                    <a href="<?= APP_URL ?>/admin/users" class="btn btn-primary btn-sm">View All Users</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>USER</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>JOINED</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentUsers)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-3">No recent users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentUsers as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($user['google_picture'])): ?>
                                                        <img src="<?= htmlspecialchars($user['google_picture']) ?>" 
                                                             alt="Profile" 
                                                             class="user-avatar"
                                                             referrerpolicy="no-referrer"
                                                             onerror="this.onerror=null; this.src='<?= APP_URL ?>/assets/images/default-avatar.png';">
                                                    <?php elseif (!empty($user['profile_picture'])): ?>
                                                        <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" 
                                                             alt="Profile" 
                                                             class="user-avatar"
                                                             onerror="this.onerror=null; this.src='<?= APP_URL ?>/assets/images/default-avatar.png';">
                                                    <?php else: ?>
                                                        <div class="avatar-placeholder">
                                                            <?= strtoupper(substr($user['first_name'] ?? '', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="user-info">
                                                        <p class="user-name">
                                                            <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                            <td>
                                                <?php
                                                $roleClass = 'bg-secondary';
                                                $roleName = 'User';
                                                
                                                if (isset($user['role_id'])) {
                                                    switch ((int)$user['role_id']) {
                                                        case ROLE_ADMIN:
                                                            $roleClass = 'bg-danger';
                                                            $roleName = 'Administrator';
                                                            break;
                                                        case ROLE_PROVIDER:
                                                            $roleClass = 'bg-info';
                                                            $roleName = 'Provider';
                                                            break;
                                                        case ROLE_CLIENT:
                                                            $roleClass = 'bg-success';
                                                            $roleName = 'Client';
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <span class="badge <?= $roleClass ?>">
                                                    <?= $roleName ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($user['is_active'] ?? 0) ? 'success' : 'danger' ?>">
                                                    <?= ($user['is_active'] ?? 0) ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Bookings</h5>
                    <a href="<?= APP_URL ?>/admin/bookings" class="btn btn-primary btn-sm">
                        View All Bookings
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>BOOKING ID</th>
                                    <th>SERVICE</th>
                                    <th>CLIENT</th>
                                    <th>PROVIDER</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentBookings)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3">No recent bookings found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentBookings as $booking): ?>
                                        <tr>
                                            <td>#<?= $booking['id'] ?></td>
                                            <td><?= htmlspecialchars($booking['service_name']) ?></td>
                                            <td><?= htmlspecialchars($booking['client_name']) ?></td>
                                            <td><?= htmlspecialchars($booking['provider_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $this->getStatusBadgeClass($booking['status']) ?>">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require_once SRC_PATH . '/views/layouts/admin-footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 