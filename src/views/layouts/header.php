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
    <title><?= $title ?? 'HomEase - Home Services Made Easy' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= APP_URL ?>/assets/img/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Page specific CSS -->
    <?php if ($currentPage === 'home'): ?>
        <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/landing.css">
    <?php endif; ?>
    
    <?php if (isset($styles) && is_array($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header Section -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <a href="<?= APP_URL ?>/" class="site-logo">
                    <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase">
                </a>
                
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="<?= APP_URL ?>/" class="nav-link<?= $currentPage === 'home' ? ' active' : '' ?>">Home</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/services" class="nav-link<?= $currentPage === 'services' ? ' active' : '' ?>">Services</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/about" class="nav-link<?= $currentPage === 'about' ? ' active' : '' ?>">About</a></li>
                        <li class="nav-item"><a href="<?= APP_URL ?>/contact" class="nav-link<?= $currentPage === 'contact' ? ' active' : '' ?>">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <button class="user-menu-toggle">
                                <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="<?= APP_URL ?>/account" class="dropdown-item">
                                    <i class="fas fa-user"></i> My Account
                                </a>
                                <a href="<?= APP_URL ?>/bookings" class="dropdown-item">
                                    <i class="fas fa-calendar"></i> My Bookings
                                </a>
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <a href="<?= APP_URL ?>/admin/dashboard" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                    </a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?= APP_URL ?>/auth/logout" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-outline">Login</a>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
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
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="container mt-md">
                <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?>">
                    <?= $_SESSION['flash_message'] ?>
                    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
                </div>
            </div>
        <?php endif; ?>

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