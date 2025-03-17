    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-company">
                    <a href="<?= APP_URL ?>/" class="footer-logo">
                        <img src="<?= APP_URL ?>/assets/img/logo-white.png" alt="HomEase" class="logo-img">
                    </a>
                    <p class="footer-description">
                        Finding reliable home services has never been easier. HomEase connects you with trusted professionals for all your home needs.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <div class="footer-links-column">
                        <h4 class="footer-heading">Quick Links</h4>
                        <ul class="footer-link-list">
                            <li><a href="<?= APP_URL ?>/">Home</a></li>
                            <li><a href="<?= APP_URL ?>/services">Services</a></li>
                            <li><a href="<?= APP_URL ?>/about">About Us</a></li>
                            <li><a href="<?= APP_URL ?>/contact">Contact</a></li>
                            <li><a href="<?= APP_URL ?>/pricing">Pricing</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4 class="footer-heading">Services</h4>
                        <ul class="footer-link-list">
                            <li><a href="<?= APP_URL ?>/services/cleaning">Home Cleaning</a></li>
                            <li><a href="<?= APP_URL ?>/services/plumbing">Plumbing</a></li>
                            <li><a href="<?= APP_URL ?>/services/electrical">Electrical</a></li>
                            <li><a href="<?= APP_URL ?>/services/gardening">Gardening</a></li>
                            <li><a href="<?= APP_URL ?>/services/all">View All Services</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4 class="footer-heading">Support</h4>
                        <ul class="footer-link-list">
                            <li><a href="<?= APP_URL ?>/help">Help Center</a></li>
                            <li><a href="<?= APP_URL ?>/faq">FAQ</a></li>
                            <li><a href="<?= APP_URL ?>/privacy-policy">Privacy Policy</a></li>
                            <li><a href="<?= APP_URL ?>/terms-of-service">Terms of Service</a></li>
                            <li><a href="<?= APP_URL ?>/contact">Contact Support</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4 class="footer-heading">Contact Us</h4>
                        <address class="footer-contact">
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                123 Services Street, City Name, Country
                            </p>
                            <p>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+1234567890">+1 (234) 567-890</a>
                            </p>
                            <p>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:info@homeease.com">info@homeease.com</a>
                            </p>
                        </address>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; <?= date('Y') ?> HomEase. All rights reserved.
                </p>
                <div class="footer-bottom-links">
                    <a href="<?= APP_URL ?>/privacy-policy">Privacy</a>
                    <a href="<?= APP_URL ?>/terms-of-service">Terms</a>
                    <a href="<?= APP_URL ?>/cookies">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Page specific scripts -->
    <?php if (isset($scripts) && is_array($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Add the main.js script reference -->
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
    
<style>
    .site-footer {
        background-color: var(--color-gray-900);
        color: var(--color-gray-300);
        padding-top: var(--spacing-2xl);
    }
    
    .footer-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        border-bottom: 1px solid var(--color-gray-800);
        padding-bottom: var(--spacing-xl);
    }
    
    .footer-company {
        margin-bottom: var(--spacing-lg);
    }
    
    .footer-logo {
        display: inline-block;
        margin-bottom: var(--spacing-md);
    }
    
    .logo-img {
        height: 40px;
    }
    
    .footer-description {
        margin-bottom: var(--spacing-md);
        max-width: 400px;
        line-height: 1.6;
    }
    
    .footer-social {
        display: flex;
        gap: var(--spacing-sm);
    }
    
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--color-gray-800);
        color: var(--color-gray-300);
        transition: all var(--transition-fast);
    }
    
    .social-link:hover {
        background-color: var(--color-primary);
        color: white;
        transform: translateY(-3px);
    }
    
    .footer-links {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: var(--spacing-lg);
    }
    
    .footer-heading {
        color: white;
        margin-bottom: var(--spacing-md);
        font-size: var(--font-size-lg);
        position: relative;
    }
    
    .footer-heading::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: var(--color-primary);
    }
    
    .footer-link-list {
        list-style: none;
        padding: 0;
    }
    
    .footer-link-list li {
        margin-bottom: var(--spacing-xs);
    }
    
    .footer-link-list a {
        color: var(--color-gray-400);
        transition: color var(--transition-fast);
        display: inline-block;
        position: relative;
    }
    
    .footer-link-list a:hover {
        color: var(--color-gray-100);
        padding-left: var(--spacing-xs);
    }
    
    .footer-link-list a::before {
        content: '\f105';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: -15px;
        opacity: 0;
        transition: all var(--transition-fast);
    }
    
    .footer-link-list a:hover::before {
        opacity: 1;
        left: -10px;
    }
    
    .footer-contact p {
        display: flex;
        align-items: flex-start;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-sm);
    }
    
    .footer-contact i {
        color: var(--color-primary);
        margin-top: 5px;
    }
    
    .footer-contact a {
        color: var(--color-gray-400);
        transition: color var(--transition-fast);
    }
    
    .footer-contact a:hover {
        color: var(--color-primary);
    }
    
    .footer-bottom {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
        padding: var(--spacing-lg) 0;
        text-align: center;
    }
    
    .footer-copyright {
        font-size: var(--font-size-sm);
        margin: 0;
    }
    
    .footer-bottom-links {
        display: flex;
        justify-content: center;
        gap: var(--spacing-md);
        font-size: var(--font-size-sm);
    }
    
    .footer-bottom-links a {
        color: var(--color-gray-400);
    }
    
    .footer-bottom-links a:hover {
        color: var(--color-primary);
    }
    
    @media (min-width: 768px) {
        .footer-links {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .footer-bottom {
            flex-direction: row;
            justify-content: space-between;
            text-align: left;
        }
    }
    
    @media (min-width: 992px) {
        .footer-content {
            grid-template-columns: 1fr 2fr;
        }
        
        .footer-links {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

</body>
</html> 