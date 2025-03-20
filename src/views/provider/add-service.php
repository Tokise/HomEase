<?php
/**
 * Add Service Template
 * Page for adding a new service
 */

$title = "Add New Service";
include SRC_PATH . '/views/layouts/provider-header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Add New Service</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= APP_URL ?>/provider/services" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Services
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">Service Information</h6>
                </div>
                <div class="card-body">
                    <form action="<?= APP_URL ?>/provider/services/create" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="form-text">Enter a descriptive name for your service.</div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            <div class="form-text">Provide a detailed description of the service you offer.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration (minutes)</label>
                                    <select class="form-select" id="duration" name="duration" required>
                                        <option value="30">30 minutes</option>
                                        <option value="60" selected>1 hour</option>
                                        <option value="90">1.5 hours</option>
                                        <option value="120">2 hours</option>
                                        <option value="180">3 hours</option>
                                        <option value="240">4 hours</option>
                                        <option value="300">5 hours</option>
                                        <option value="360">6 hours</option>
                                        <option value="480">8 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="service_image" class="form-label">Service Image (Optional)</label>
                            <input type="file" class="form-control" id="service_image" name="service_image" accept="image/jpeg,image/png">
                            <div class="form-text">Upload an image that represents your service. Maximum size: 2MB.</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Make this service available immediately</label>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Create Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold">Tips for Creating Services</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Be specific about what your service includes.</li>
                        <li class="mb-2">Set a competitive price based on market rates.</li>
                        <li class="mb-2">Clearly state the duration and what can be done in that time.</li>
                        <li class="mb-2">Add a high-quality image to make your service stand out.</li>
                        <li class="mb-2">Include any requirements or prerequisites (e.g., client must provide materials).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include SRC_PATH . '/views/layouts/provider-footer.php'; ?> 

<style>

.container-fluid{
    margin-left: 1rem;
    padding: 1rem;
}

.h2{
    margin-left: 1rem;
}
</style>