<?php
/**
 * Provider Profile Template
 */
?>

<!-- Provider Profile Page -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">My Profile</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/provider/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <?php $profilePic = $provider['profile_picture'] ?? '/assets/img/default-avatar.png'; ?>
                        <div class="mb-4">
                            <img src="<?= APP_URL . $profilePic ?>" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1"><?= htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($provider['business_name'] ?? 'Service Provider') ?></p>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <p class="mb-0 text-muted"><?= htmlspecialchars($provider['email']) ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0">Phone</h6>
                                <p class="mb-0 text-muted"><?= htmlspecialchars($provider['phone_number'] ?? 'Not provided') ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0">Address</h6>
                                <p class="mb-0 text-muted">
                                    <?= htmlspecialchars($provider['address'] ?? 'Not provided') ?>
                                    <?= !empty($provider['city']) ? ', ' . htmlspecialchars($provider['city']) : '' ?>
                                    <?= !empty($provider['state']) ? ', ' . htmlspecialchars($provider['state']) : '' ?>
                                    <?= !empty($provider['postal_code']) ? ' ' . htmlspecialchars($provider['postal_code']) : '' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Update Profile</h4>
                    
                    <form action="<?= APP_URL ?>/provider/updateProfile" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($provider['first_name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($provider['last_name']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($provider['email']) ?>" readonly disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($provider['phone_number'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($provider['address'] ?? '') ?>">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($provider['city'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="<?= htmlspecialchars($provider['state'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= htmlspecialchars($provider['postal_code'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png">
                            <small class="text-muted">Max file size: 2MB. Allowed formats: JPG, PNG</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" value="<?= htmlspecialchars($provider['business_name'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_description" class="form-label">Business Description</label>
                            <textarea class="form-control" id="business_description" name="business_description" rows="4"><?= htmlspecialchars($provider['business_description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 