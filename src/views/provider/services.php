<?php
/**
 * Provider Services Template
 * Page for managing provider services
 */

$title = "My Services";
include SRC_PATH . '/views/layouts/provider-header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Services</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= APP_URL ?>/provider/services/add" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add New Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Services List -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold">Your Listed Services</h6>
        </div>
        <div class="card-body">
            <?php if (empty($services)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-tools fa-4x text-gray-300 mb-4"></i>
                    <p class="mb-3">You haven't listed any services yet.</p>
                    <a href="<?= APP_URL ?>/provider/services/add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Your First Service
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Bookings</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($service['image'])): ?>
                                                <img src="<?= APP_URL ?>/uploads/services/<?= $service['image'] ?>" 
                                                     class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;" 
                                                     alt="<?= htmlspecialchars($service['name']) ?>">
                                            <?php else: ?>
                                                <div class="bg-light me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-tools text-gray-500"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($service['name']) ?></h6>
                                                <small class="text-muted">
                                                    <?= substr(htmlspecialchars($service['description']), 0, 50) ?>...
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($service['category_name']) ?></td>
                                    <td>$<?= number_format($service['price'], 2) ?></td>
                                    <td>
                                        <?php if ($service['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $service['booking_count'] ?? 0 ?></td>
                                    <td>
                                        <?php if (isset($service['rating']) && $service['rating'] > 0): ?>
                                            <span class="text-warning"><?= number_format($service['rating'], 1) ?></span>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <span class="text-muted">No ratings</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= APP_URL ?>/provider/services/edit/<?= $service['id'] ?>" 
                                               class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/provider/services/toggle/<?= $service['id'] ?>" 
                                               class="btn btn-sm <?= $service['is_active'] ? 'btn-secondary' : 'btn-success' ?> toggle-service"
                                               data-status="<?= $service['is_active'] ? 'active' : 'inactive' ?>" 
                                               data-name="<?= htmlspecialchars($service['name']) ?>"
                                               data-bs-toggle="tooltip" 
                                               title="<?= $service['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                                <i class="fas <?= $service['is_active'] ? 'fa-toggle-off' : 'fa-toggle-on' ?>"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/provider/services/delete/<?= $service['id'] ?>" 
                                               class="btn btn-sm btn-danger delete-service"
                                               data-id="<?= $service['id'] ?>"
                                               data-name="<?= htmlspecialchars($service['name']) ?>"
                                               data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

    <!-- Service Analytics -->
    <?php if (!empty($services)): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold">Service Performance</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="small font-weight-bold">Most Booked Services</h5>
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="servicesBookingChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="small font-weight-bold">Ratings Distribution</h5>
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="ratingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Chart.js Script -->
<?php if (!empty($services)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Services Booking Chart
    const serviceCtx = document.getElementById('servicesBookingChart').getContext('2d');
    new Chart(serviceCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($top_services ?? [], 'name')) ?>,
            datasets: [{
                label: 'Number of Bookings',
                data: <?= json_encode(array_column($top_services ?? [], 'booking_count')) ?>,
                backgroundColor: 'rgba(63, 81, 181, 0.7)',
                borderColor: 'rgba(63, 81, 181, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Ratings Distribution Chart
    const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');
    new Chart(ratingsCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
            datasets: [{
                data: <?= json_encode($ratings_distribution ?? [0, 0, 0, 0, 0]) ?>,
                backgroundColor: [
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(54, 185, 204, 0.7)',
                    'rgba(246, 194, 62, 0.7)',
                    'rgba(246, 130, 62, 0.7)',
                    'rgba(231, 74, 59, 0.7)'
                ],
                borderColor: [
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)',
                    'rgba(246, 194, 62, 1)',
                    'rgba(246, 130, 62, 1)',
                    'rgba(231, 74, 59, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Delete service confirmation
    document.querySelectorAll('.delete-service').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href');
            const serviceName = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Delete Service?',
                text: `Are you sure you want to delete "${serviceName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
<?php endif; ?>

<?php include SRC_PATH . '/views/layouts/provider-footer.php'; ?> 