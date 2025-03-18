<?php
// Determine current page based on URL
$uri = $_SERVER['REQUEST_URI'];
$currentPage = 'home';

if (strpos($uri, '/services') !== false) {
    $currentPage = 'services';
} elseif (strpos($uri, '/about') !== false) {
    $currentPage = 'about';
} elseif (strpos($uri, '/contact') !== false) {
    $currentPage = 'contact';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title><?= $title ?? 'HomEase - Home Services Platform' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= APP_URL ?>/assets/img/favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    
    <!-- Page specific CSS -->
    <?php if ($currentPage === 'home'): ?>
        <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/landing.css">
    <?php endif; ?>
    
    <?php if (isset($styles) && is_array($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link href="<?= APP_URL ?>/assets/css/<?= $style ?>.css" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= APP_URL ?>">
                <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <?php if ($_SESSION['user_role'] == ROLE_ADMIN): ?>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/admin/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                                <?php elseif ($_SESSION['user_role'] == ROLE_SERVICE_PROVIDER): ?>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/provider/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Provider Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?= APP_URL ?>/client/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/client/profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline-primary me-2">Login</a>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Mobile Navigation Menu (hidden by default) -->
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <a href="<?= APP_URL ?>/" class="site-logo">
                <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase">
            </a>
            <button class="mobile-menu-close" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mobile-nav">
            <ul class="mobile-nav-list">
                <li class="mobile-nav-item"><a href="<?= APP_URL ?>/" class="mobile-nav-link">Home</a></li>
                <li class="mobile-nav-item"><a href="<?= APP_URL ?>/services" class="mobile-nav-link">Services</a></li>
                <li class="mobile-nav-item"><a href="<?= APP_URL ?>/about" class="mobile-nav-link">About</a></li>
                <li class="mobile-nav-item"><a href="<?= APP_URL ?>/contact" class="mobile-nav-link">Contact</a></li>
            </ul>
        </nav>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="mobile-menu-actions">
                <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline btn-block mb-sm">Login</a>
                <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary btn-block">Sign Up</a>
            </div>
        <?php else: ?>
            <div class="mobile-user-menu">
                <div class="mobile-user-header">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <h4 class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h4>
                        <p class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
                    </div>
                </div>
                <ul class="mobile-user-links">
                    <li><a href="<?= APP_URL ?>/account"><i class="fas fa-user"></i> My Account</a></li>
                    <li><a href="<?= APP_URL ?>/bookings"><i class="fas fa-calendar"></i> My Bookings</a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?= APP_URL ?>/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="overlay"></div>
    
    <!-- Main Content Area -->
    <main class="site-main">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const mobileMenu = document.querySelector('.mobile-menu');
        const overlay = document.querySelector('.overlay');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
        
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMenu);
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }
        
        function closeMenu() {
            mobileMenu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Highlight active menu item
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === APP_URL + '/' && currentPath === APP_URL + '/') {
                link.classList.add('active');
            } else if (href !== APP_URL + '/' && currentPath.startsWith(href)) {
                link.classList.add('active');
            }
        });
    });
</script> 