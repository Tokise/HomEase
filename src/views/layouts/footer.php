    </main>

    <!-- Footer Section -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>HomEase</h5>
                    <p class="mb-3">Your one-stop platform for all home services. Quality work, guaranteed satisfaction.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= APP_URL ?>" class="text-decoration-none text-white-50">Home</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/services" class="text-decoration-none text-white-50">Services</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/about" class="text-decoration-none text-white-50">About Us</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/contact" class="text-decoration-none text-white-50">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= APP_URL ?>/services/category/cleaning" class="text-decoration-none text-white-50">Cleaning</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/services/category/plumbing" class="text-decoration-none text-white-50">Plumbing</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/services/category/electrical" class="text-decoration-none text-white-50">Electrical</a></li>
                        <li class="mb-2"><a href="<?= APP_URL ?>/services/category/gardening" class="text-decoration-none text-white-50">Gardening</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contact Us</h5>
                    <address class="text-white-50">
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Service Street, City</p>
                        <p class="mb-2"><i class="fas fa-phone-alt me-2"></i> (123) 456-7890</p>
                        <p class="mb-2"><i class="fas fa-envelope me-2"></i> info@homeease.com</p>
                    </address>
                </div>
            </div>
            <hr class="mt-4 mb-4 bg-secondary">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0">&copy; <?= date('Y') ?> HomEase. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= APP_URL ?>/privacy-policy" class="text-decoration-none text-white-50 me-3">Privacy Policy</a>
                    <a href="<?= APP_URL ?>/terms-of-service" class="text-decoration-none text-white-50">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 