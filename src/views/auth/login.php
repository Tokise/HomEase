<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= APP_URL ?>/assets/img/logo.png" alt="HomEase" class="auth-logo">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your HomEase account</p>
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
        
        <form action="<?= APP_URL ?>/auth/process-login" method="post" class="auth-form" data-validate>
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>
                
                <a href="<?= APP_URL ?>/auth/forgot-password" class="forgot-link">Forgot password?</a>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="<?= APP_URL ?>/auth/register">Create Account</a></p>
        </div>
    </div>
</div>

<script>
    // Password toggle visibility
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script> 