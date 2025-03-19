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
                                    <img src="<?= htmlspecialchars($user['google_picture']) ?>" alt="Profile" class="user-avatar">
                                <?php elseif (!empty($user['profile_picture'])): ?>
                                    <img src="<?= APP_URL ?>/<?= $user['profile_picture'] ?>" alt="Profile" class="user-avatar">
                                <?php else: ?>
                                    <div class="default-avatar" style="background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%); color: white;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($user['google_id'])): ?>
                                    <span style="
                                        position: absolute;
                                        bottom: -2px;
                                        right: -2px;
                                        background: #fff;
                                        border-radius: 50%;
                                        width: 16px;
                                        height: 16px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 10px;
                                        color: #4285F4;
                                        border: 1px solid #e0e0e0;
                                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                                        title="Google Account">
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

    <style>
    :root {
        --primary-color: #4e73df;
        --primary-hover: #2e59d9;
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
    }

    .navbar-brand img {
        height: 40px;
        width: auto;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .user-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover, #2e59d9) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .google-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        background: #fff;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #4285F4;
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .dropdown-toggle {
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border-radius: 8px;
        transition: background-color 0.2s;
    }

    .dropdown-toggle:hover {
        background-color: rgba(0,0,0,0.05);
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

    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background-color: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
    }

    .card-header h5 {
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .profile-card {
        margin-bottom: 1.5rem;
    }

    .profile-image-container {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }

    .profile-image {
        width: 140px;
        height: 140px;
        margin: 0 auto;
        position: relative;
        border-radius: 50%;
        padding: 4px;
        background-color: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
    }

    .default-avatar {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 3.5rem;
        border: 3px solid #fff;
    }

    .profile-google-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #fff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #4285F4;
        border: 2px solid #fff;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    .account-type-badge {
        display: inline-block;
        padding: 6px 15px;
        background-color: #f8f9fc;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #4285F4;
        margin-top: 10px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .account-type-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .form-label {
        font-weight: 500;
        color: var(--text-primary);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .btn {
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #4262c7;
        border-color: #4262c7;
    }
    </style> 