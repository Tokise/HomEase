<?php require_once SRC_PATH . '/views/layouts/admin-header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php require_once SRC_PATH . '/views/layouts/admin-sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Users</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?= APP_URL ?>/admin/add-user" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add User
                        </a>
                    </div>
                </div>

                <!-- Filter and search -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="roleFilter" class="form-label">Filter by Role</label>
                                <select id="roleFilter" class="form-select form-select-sm">
                                    <option value="">All Roles</option>
                                    <option value="1">Administrator</option>
                                    <option value="2">Service Provider</option>
                                    <option value="3">Client</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select id="statusFilter" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="searchUser" class="form-label">Search</label>
                                <input type="text" id="searchUser" class="form-control form-control-sm" placeholder="Search users...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
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
                                                        <div class="user-avatar me-2">
                                                            <?php if (!empty($user['google_picture'])): ?>
                                                                <img src="<?= htmlspecialchars($user['google_picture']) ?>" alt="Profile" width="32" height="32" class="rounded-circle">
                                                            <?php elseif (!empty($user['profile_picture'])): ?>
                                                                <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile" width="32" height="32" class="rounded-circle">
                                                            <?php else: ?>
                                                                <div class="avatar-placeholder rounded-circle" style="width: 32px; height: 32px; background-color: #4e73df; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $this->getRoleBadgeClass($user['role_id']) ?>">
                                                        <?= $this->getRoleName($user['role_id']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($user['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?= APP_URL ?>/admin/view-user/<?= $user['id'] ?>" class="btn btn-info" data-bs-toggle="tooltip" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= APP_URL ?>/admin/edit-user/<?= $user['id'] ?>" class="btn btn-primary" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="<?= APP_URL ?>/admin/delete-user/<?= $user['id'] ?>" class="btn btn-danger delete-confirm" data-bs-toggle="tooltip" title="Delete" data-name="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
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
    </div>
</div>

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
});
</script>

<?php require_once SRC_PATH . '/views/layouts/admin-footer.php'; ?> 