    </div> <!-- End of main-content -->

    <style>
        .footer {
            background: #ffffff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            position: relative;
            margin-top: 2rem;
            font-family: 'Poppins', sans-serif;
        }

        .footer .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer .text-muted {
            color: #6c757d !important;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Provider Footer */
        .provider-footer {
            background: #fff;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            right: 0;
            left: 250px;
            border-top: 1px solid #e3e6f0;
            z-index: 999;
            transition: all 0.3s;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #858796;
            font-size: 0.875rem;
        }

        .footer-links a {
            color: #4e73df;
            text-decoration: none;
            margin-left: 1rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #2e59d9;
        }

        @media (max-width: 768px) {
            .provider-footer {
                left: 0;
                text-align: center;
            }

            .footer-content {
                flex-direction: column;
                gap: 0.5rem;
            }

            .footer-links {
                margin-top: 0.5rem;
            }
        }
    </style>

    <!-- Provider Footer -->
    <footer class="provider-footer">
        <div class="footer-content">
            <div class="footer-copyright">
                &copy; <?= date('Y') ?> HomeSwift. All rights reserved.
            </div>
            <div class="footer-links">
                <a href="<?= APP_URL ?>/privacy-policy">Privacy Policy</a>
                <a href="<?= APP_URL ?>/terms-of-service">Terms of Service</a>
                <a href="<?= APP_URL ?>/contact">Contact</a>
            </div>
        </div>
    </footer>
    
    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.body.classList.toggle('sidebar-toggled');
                const providerFooter = document.querySelector('.provider-footer');
                if (providerFooter) {
                    providerFooter.style.left = document.body.classList.contains('sidebar-toggled') ? '0' : '250px';
                }
            });
        }

        // Responsive footer adjustment
        function adjustFooter() {
            const providerFooter = document.querySelector('.provider-footer');
            if (window.innerWidth <= 768) {
                providerFooter.style.left = '0';
            } else {
                providerFooter.style.left = document.body.classList.contains('sidebar-toggled') ? '0' : '250px';
            }
        }

        window.addEventListener('resize', adjustFooter);
        adjustFooter();
    });
    </script>

    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 