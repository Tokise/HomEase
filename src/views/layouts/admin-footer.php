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

        /* SweetAlert2 Custom Styles */
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 10px !important;
        }

        .swal2-title {
            font-weight: 600 !important;
            color: #2d3436 !important;
        }

        .swal2-text {
            color: #636e72 !important;
        }

        .swal2-confirm {
            background-color: #e74a3b !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            padding: 0.5rem 1.5rem !important;
        }

        .swal2-cancel {
            background-color: #4e73df !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            padding: 0.5rem 1.5rem !important;
        }

        /* Tooltip Custom Styles */
        .tooltip {
            font-family: 'Poppins', sans-serif !important;
            font-size: 0.75rem !important;
        }

        .tooltip-inner {
            background-color: #2d3436 !important;
            border-radius: 4px !important;
            padding: 0.25rem 0.5rem !important;
        }

        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #2d3436 !important;
        }

        .bs-tooltip-bottom .tooltip-arrow::before {
            border-bottom-color: #2d3436 !important;
        }

        .bs-tooltip-start .tooltip-arrow::before {
            border-left-color: #2d3436 !important;
        }

        .bs-tooltip-end .tooltip-arrow::before {
            border-right-color: #2d3436 !important;
        }

        /* Admin Footer */
        .admin-footer {
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
            .admin-footer {
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

    <footer class="footer mt-auto py-3">
        <div class="container">
            <span class="text-muted">© <?= date('Y') ?> HomEase. Admin Dashboard v1.0</span>
        </div>
    </footer>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips with custom options
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                animation: true,
                delay: { show: 100, hide: 100 }
            });
        });
        
        // Enhanced delete confirmations
        document.querySelectorAll('.delete-confirm').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('href');
                const name = this.getAttribute('data-name');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you really want to delete "${name}"? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#4e73df',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-primary'
                    },
                    buttonsStyling: false,
                    reverseButtons: true,
                    padding: '2em'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
    </script>
    
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Admin Footer -->
    <footer class="admin-footer">
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
                const adminFooter = document.querySelector('.admin-footer');
                if (adminFooter) {
                    adminFooter.style.left = document.body.classList.contains('sidebar-toggled') ? '0' : '250px';
                }
            });
        }

        // Responsive footer adjustment
        function adjustFooter() {
            const adminFooter = document.querySelector('.admin-footer');
            if (window.innerWidth <= 768) {
                adminFooter.style.left = '0';
            } else {
                adminFooter.style.left = document.body.classList.contains('sidebar-toggled') ? '0' : '250px';
            }
        }

        window.addEventListener('resize', adjustFooter);
        adjustFooter();
    });
    </script>
</body>
</html> 