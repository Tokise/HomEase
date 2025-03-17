<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase" class="auth-logo">
            <h1 class="auth-title">Create an Account</h1>
            <p class="auth-subtitle">Join HomEase and start finding reliable home services</p>
        </div>
        
        <div class="auth-social">
            <a href="<?= $googleAuthUrl ?>" class="btn-google">
                <img src="<?= APP_URL ?>/assets/img/google-logo.svg" alt="Google">
                <span>Continue with Google</span>
            </a>
        </div>
        
        <div class="auth-divider">
            <span>OR</span>
        </div>
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?>">
                <?= $_SESSION['flash_message'] ?>
                <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= APP_URL ?>/auth/process-register" method="post" class="auth-form" data-validate>
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="first_name" name="first_name" class="form-control" required placeholder="First name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="Last name">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input type="tel" id="phone" name="phone_number" class="form-control" placeholder="Your phone number (optional)">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Create a password" minlength="8">
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength">
                    <div class="strength-meter">
                        <div class="strength-meter-fill" data-strength="0"></div>
                    </div>
                    <div class="strength-text">Password strength: <span>Too weak</span></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirm your password">
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                    <label for="terms" class="form-check-label">I agree to the <a href="<?= APP_URL ?>/terms-of-service" target="_blank">Terms of Service</a> and <a href="<?= APP_URL ?>/privacy-policy" target="_blank">Privacy Policy</a></label>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </div>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="<?= APP_URL ?>/auth/login">Sign In</a></p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle visibility
        const toggleButtons = document.querySelectorAll('.password-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.querySelector('.strength-meter-fill');
        const strengthText = document.querySelector('.strength-text span');
        
        if (passwordInput && strengthMeter && strengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let feedback = 'Too weak';
                
                if (password.length >= 8) {
                    strength += 1;
                }
                
                if (password.match(/[A-Z]/)) {
                    strength += 1;
                }
                
                if (password.match(/[0-9]/)) {
                    strength += 1;
                }
                
                if (password.match(/[^A-Za-z0-9]/)) {
                    strength += 1;
                }
                
                switch (strength) {
                    case 0:
                        feedback = 'Too weak';
                        break;
                    case 1:
                        feedback = 'Weak';
                        break;
                    case 2:
                        feedback = 'Fair';
                        break;
                    case 3:
                        feedback = 'Good';
                        break;
                    case 4:
                        feedback = 'Strong';
                        break;
                }
                
                strengthMeter.setAttribute('data-strength', strength);
                strengthText.textContent = feedback;
            });
        }
        
        // Confirm password validation
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        if (passwordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    });
</script>

