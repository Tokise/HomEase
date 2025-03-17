<?php /* Header is already included by the Controller */ ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/client/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card profile-sidebar">
                <div class="card-body text-center">
                    <div class="profile-image-container mb-3">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile Picture" class="profile-image">
                        <?php else: ?>
                            <div class="profile-initial">
                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h4 class="profile-name"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    
                    <ul class="list-group profile-info-list mt-4">
                        <li class="list-group-item">
                            <i class="fas fa-user me-2"></i> Member since <?= date('M Y', strtotime($user['created_at'])) ?>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-phone me-2"></i> 
                            <?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted">No phone added</span>' ?>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-map-marker-alt me-2"></i> 
                            <?= !empty($user['address']) ? htmlspecialchars($user['address']) : '<span class="text-muted">No address added</span>' ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-edit me-2"></i> Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form action="<?= APP_URL ?>/client/updateProfile" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <div class="form-text">Email cannot be changed</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                            <div class="form-text">Upload a square image for best results. Max file size: 2MB</div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <div class="form-text">Leave blank if you don't want to change your password</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-sidebar {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.5rem;
        border: none;
    }
    
    .profile-image-container {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #4e73df;
        color: white;
        font-size: 3rem;
        font-weight: 700;
    }
    
    .profile-name {
        margin-top: 1rem;
        font-weight: 600;
        color: #333;
    }
    
    .profile-info-list {
        text-align: left;
    }
    
    .profile-info-list .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .profile-info-list .list-group-item:first-child {
        border-top: none;
    }
    
    .profile-info-list .list-group-item i {
        color: #4e73df;
    }
    
    .form-text {
        font-size: 0.85rem;
    }
    
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.5rem;
        border: none;
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
    }
    
    .card-header h5 {
        margin-bottom: 0;
        font-weight: 600;
        color: #4e73df;
    }
</style>

<?php /* Footer is already included by the Controller */ ?> 