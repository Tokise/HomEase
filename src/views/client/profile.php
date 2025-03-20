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
        <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
        <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/client.css">
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                HomeSwift
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-avatar-wrapper">
                                <?php if (!empty($user['google_picture'])): ?>
                                    <img src="<?= htmlspecialchars($user['google_picture']) ?>" alt="Profile" class="user-avatar">
                                <?php elseif (!empty($user['profile_picture'])): ?>
                                    <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile" class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($user['google_id'])): ?>
                                    <span class="google-badge" title="Google Account">
                                        <i class="fab fa-google"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="ms-2"><?= htmlspecialchars($user['first_name']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/client/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/client/bookings"><i class="fas fa-calendar me-2"></i>My Bookings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card profile-card">
                        <div class="card-body text-center">
                            <div class="profile-image-container mb-4">
                                <div class="profile-image">
                                    <?php if (!empty($user['google_picture'])): ?>
                                        <img src="<?= htmlspecialchars($user['google_picture']) ?>" 
                                             alt="Profile Picture" 
                                             class="rounded-circle"
                                             referrerpolicy="no-referrer"
                                             onerror="this.onerror=null; this.src='<?= APP_URL ?>/assets/img/default-avatar.png';">
                                    <?php elseif (!empty($user['profile_picture'])): ?>
                                        <img src="<?= APP_URL . htmlspecialchars($user['profile_picture']) ?>" 
                                             alt="Profile Picture" 
                                             class="rounded-circle"
                                             onerror="this.onerror=null; this.src='<?= APP_URL ?>/assets/img/default-avatar.png';">
                                    <?php else: ?>
                                        <div class="profile-picture-placeholder rounded-circle">
                                            <span><?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($user['google_id'])): ?>
                                    <div class="google-badge" title="Google Account">
                                        <i class="fab fa-google"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h4 class="mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                            <p class="text-muted"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($user['phone_number'] ?? 'Not provided') ?></p>
                            <?php if (!empty($user['google_id'])): ?>
                                <span class="account-type-badge">
                                    <i class="fab fa-google me-1"></i> Google Account
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= APP_URL ?>/client/updateProfile" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Profile Picture</label>
                                    <input type="file" 
                                           class="form-control" 
                                           name="profile_picture" 
                                           accept="image/jpeg,image/png"
                                           data-max-size="2097152">
                                    <div class="form-text">
                                        <small class="text-muted">
                                            Max file size: 2MB. Supported formats: JPG, PNG.
                                            <?php if (!empty($user['profile_picture'])): ?>
                                                <br>
                                                <a href="#" class="text-danger" id="removeProfilePicture">
                                                    <i class="fas fa-trash-alt"></i> Remove current picture
                                                </a>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File input validation
            const fileInput = document.querySelector('input[type="file"]');
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                const maxSize = parseInt(this.dataset.maxSize);
                
                if (file) {
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Please select an image under 2MB in size.'
                        });
                        this.value = '';
                        return;
                    }
                    
                    if (!['image/jpeg', 'image/png'].includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Please select a JPG or PNG image.'
                        });
                        this.value = '';
                        return;
                    }
                }
            });

            // Remove profile picture
            const removeButton = document.getElementById('removeProfilePicture');
            if (removeButton) {
                removeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Remove Profile Picture?',
                        text: 'Are you sure you want to remove your profile picture?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '<?= APP_URL ?>/client/removeProfilePicture';
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            }
        });
        </script>
    </body>
    </html>