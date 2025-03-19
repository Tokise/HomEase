<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard | HomeSwift' ?></title>
    
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
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .admin-header {
            background: #fff;
            height: 60px;
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        #sidebarToggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        #sidebarToggle:hover {
            background: #f8f9fa;
            color: #4e73df;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notification-badge {
            position: relative;
            color: #6c757d;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .notification-badge:hover {
            background: #f8f9fa;
            color: #4e73df;
        }

        .badge-count {
            position: absolute;
            top: 0;
            right: 0;
            background: #e74a3b;
            color: white;
            border-radius: 50%;
            padding: 0.25rem;
            font-size: 0.75rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-site-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s;
        }

        .view-site-btn:hover {
            background: #f8f9fa;
            color: #4e73df;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .user-menu:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #4e73df;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2d3436;
            margin: 0;
        }

        .user-role {
            font-size: 0.75rem;
            color: #6c757d;
            margin: 0;
        }

        .dropdown-menu {
            margin-top: 0.5rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: #3a3b45;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background: #f8f9fc;
            color: #4e73df;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #e3e6f0;
        }

        @media (max-width: 768px) {
            .admin-header {
                left: 0;
            }

            .header-right {
                gap: 1rem;
            }

            .user-info {
                display: none;
            }

            .view-site-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="header-left">
            <button id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="header-right">
            <div class="notification-badge">
                <i class="fas fa-bell"></i>
                <?php if (isset($notificationCount) && $notificationCount > 0): ?>
                    <span class="badge-count"><?= $notificationCount ?></span>
                <?php endif; ?>
            </div>

            <a href="<?= APP_URL ?>/?preview=true" class="view-site-btn" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-external-link-alt"></i>
                <span>View Site</span>
            </a>

            <div class="dropdown">
                <div class="user-menu" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <?php if (!empty($admin['profile_picture'])): ?>
                            <img src="<?= APP_URL ?>/<?= $admin['profile_picture'] ?>" alt="Admin" width="35" height="35" class="rounded-circle">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <p class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin User') ?></p>
                        <p class="user-role">Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem; color: #6c757d;"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= APP_URL ?>/admin/profile">
                            <i class="fas fa-user"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= APP_URL ?>/admin/settings">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= APP_URL ?>/auth/logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show m-3" role="alert">
            <?= $_SESSION['flash_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?> 