    <!-- Footer -->
    <footer class="footer mt-5 py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">&copy; <?= date('Y') ?> HomEase. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= APP_URL ?>/help" class="text-decoration-none text-muted me-3">Help Center</a>
                    <a href="<?= APP_URL ?>/contact" class="text-decoration-none text-muted">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Handle booking cancellation confirmations
        document.querySelectorAll('.cancel-booking').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('href');
                const id = this.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Cancel Booking?',
                    text: 'Are you sure you want to cancel this booking? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3f51b5',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'Yes, cancel it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
        
        // Handle service status toggling
        document.querySelectorAll('.toggle-service').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('href');
                const status = this.getAttribute('data-status');
                const service = this.getAttribute('data-name');
                
                Swal.fire({
                    title: status === 'active' ? 'Deactivate Service?' : 'Activate Service?',
                    text: status === 'active' 
                        ? `Do you want to deactivate "${service}"? It won't be visible to clients.` 
                        : `Do you want to activate "${service}"? It will be visible to clients.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3f51b5',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: status === 'active' ? 'Yes, deactivate' : 'Yes, activate'
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
</body>
</html> 