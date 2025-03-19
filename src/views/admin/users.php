<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | HomeSwift Admin</title>
    
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

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            padding: 1.25rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Form Controls */
        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-select, .form-control {
            font-size: 0.875rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem;
        }

        .form-select:focus, .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #858796;
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

        .table tbody tr:hover {
            background-color: #f8fafc;
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

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            color: #2d3436;
            font-size: 0.875rem;
            border-bottom: 1px solid #e3e6f0;
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

        /* Badges */
        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.75em;
            font-weight: 500;
            border-radius: 6px;
            color: #fff;
        }

        .bg-danger {
            background-color: #e74a3b !important;
        }

        .bg-info {
            background-color: #36b9cc !important;
        }

        .bg-success {
            background-color: #1cc88a !important;
        }

        .bg-secondary {
            background-color: #858796 !important;
        }

        /* Action Buttons */
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
        }

        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
            color: #fff;
        }

        .btn-info:hover {
            background-color: #2ea7b9;
            border-color: #2a9faf;
            color: #fff;
        }

        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }

        .btn-danger:hover {
            background-color: #be2617;
            border-color: #be2617;
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
                <h1 class="h2">Manage Users</h1>
                <div class="btn-toolbar">
                    <a href="<?= APP_URL ?>/admin/add-user" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add User
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="roleFilter" class="form-label">Filter by Role</label>
                            <select id="roleFilter" class="form-select">
                                <option value="">All Roles</option>
                                <option value="1">Administrator</option>
                                <option value="2">Service Provider</option>
                                <option value="3">Client</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchUser" class="form-label">Search</label>
                            <input type="text" id="searchUser" class="form-control" placeholder="Search users...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>USER</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>STATUS</th>
                                    <th>REGISTERED</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
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
                                                        <p class="user-name"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <?php
                                                $roleClass = '';
                                                $roleName = '';
                                                
                                                if (isset($user['role_id'])) {
                                                    switch ((int)$user['role_id']) {
                                                        case 1:
                                                            $roleClass = 'bg-danger';
                                                            $roleName = 'Administrator';
                                                            break;
                                                        case 2:
                                                            $roleClass = 'bg-info';
                                                            $roleName = 'Provider';
                                                            break;
                                                        case 3:
                                                            $roleClass = 'bg-success';
                                                            $roleName = 'Client';
                                                            break;
                                                        default:
                                                            $roleClass = 'bg-secondary';
                                                            $roleName = 'User';
                                                    }
                                                } else {
                                                    $roleClass = 'bg-secondary';
                                                    $roleName = 'User';
                                                }
                                                ?>
                                                <span class="badge <?= $roleClass ?>">
                                                    <?= $roleName ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= APP_URL ?>/admin/view-user/<?= $user['id'] ?>" class="btn btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= APP_URL ?>/admin/edit-user/<?= $user['id'] ?>" class="btn btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <button type="button" class="btn btn-danger delete-user" 
                                                                data-id="<?= $user['id'] ?>" 
                                                                data-name="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
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

    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role filter functionality
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('searchUser');
        const tableRows = document.querySelectorAll('#usersTable tbody tr');
        
        // Combined filter function
        function filterTable() {
            const roleValue = roleFilter.value;
            const statusValue = statusFilter.value;
            const searchValue = searchInput.value.toLowerCase();
            
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const roleCell = cells[3].textContent.trim();
                    const statusCell = cells[4].textContent.trim();
                    const matchesRole = roleValue === '' || (
                        roleValue === '1' && roleCell.includes('Administrator') ||
                        roleValue === '2' && roleCell.includes('Service Provider') ||
                        roleValue === '3' && roleCell.includes('Client')
                    );
                    
                    const matchesStatus = statusValue === '' || (
                        statusValue === '1' && statusCell.includes('Active') ||
                        statusValue === '0' && statusCell.includes('Inactive')
                    );
                    
                    const matchesSearch = searchValue === '' || 
                        cells[1].textContent.toLowerCase().includes(searchValue) || 
                        cells[2].textContent.toLowerCase().includes(searchValue);
                    
                    row.style.display = (matchesRole && matchesStatus && matchesSearch) ? '' : 'none';
                }
            });
        }
        
        // Add event listeners
        roleFilter.addEventListener('change', filterTable);
        statusFilter.addEventListener('change', filterTable);
        searchInput.addEventListener('keyup', filterTable);

        // Delete user confirmation
        const deleteButtons = document.querySelectorAll('.delete-user');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.id;
                const userName = this.dataset.name;
                if (confirm(`Are you sure you want to delete ${userName}?`)) {
                    window.location.href = `${APP_URL}/admin/delete-user/${userId}`;
                }
            });
        });
    });
    </script>
</body>
</html> 