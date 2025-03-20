<?php require_once SRC_PATH . '/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
     
        
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?>">
                <?= $_SESSION['flash_message'] ?>
                <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>
        
        <form id="signupForm" action="<?= APP_URL ?>/auth/process-register" method="post" class="auth-form">
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
            <div class="create-account-section">
                <span>Already have an account?</span>
                <a href="<?= APP_URL ?>/auth/login" class="btn btn-link">Sign In</a>
            </div>
        </div>
    </div>
</div>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        updatePasswordStrength(strength);
    });

    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        return strength;
    }

    function updatePasswordStrength(strength) {
        const percentage = (strength / 5) * 100;
        strengthMeter.style.width = percentage + '%';
        
        let text = 'Too weak';
        let color = '#ff4444';
        
        if (strength >= 5) {
            text = 'Very strong';
            color = '#00C851';
        } else if (strength >= 4) {
            text = 'Strong';
            color = '#00C851';
        } else if (strength >= 3) {
            text = 'Medium';
            color = '#ffbb33';
        } else if (strength >= 2) {
            text = 'Weak';
            color = '#ff8800';
        }
        
        strengthMeter.style.backgroundColor = color;
        strengthText.textContent = text;
    }

    // Handle form submission
    const signupForm = document.getElementById('signupForm');
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const formData = new FormData(this);
            
            // Basic validation
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match!'
                });
                return;
            }
            
            if (!document.getElementById('terms').checked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terms Not Accepted',
                    text: 'Please accept the Terms of Service and Privacy Policy'
                });
                return;
            }

            // Disable form submission button and show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            let data;
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                throw new Error('Server response was not JSON');
            }
            
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Welcome!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.href = data.redirect;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: data.message || 'Something went wrong during registration. Please try again.'
                });
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        } catch (error) {
            console.error('Registration error:', error);
            Swal.fire({
                icon: 'error',
                title: 'System Error',
                text: 'A system error occurred during registration. Please try again later.'
            });
            // Re-enable submit button
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Create Account';
        }
    });
});
</script>

<style>
.auth-container {
    min-height: calc(100vh - 80px);
    padding: 2rem;
    background-color: #f8f9fa;
}

.auth-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    padding: 2rem;
    width: 100%;
    max-width: 600px; /* Reduced from 800px */
    margin: 0 auto;
}

.auth-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

/* Elements that should span full width */
.form-group:nth-child(n+5), /* Starting from password field */
.form-check, 
.form-group:last-child,
.auth-footer {
    grid-column: 1 / -1;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #344767;
    margin-bottom: 0.5rem;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.input-icon .form-control {
    width: 100%;
    height: 42px;
    padding-left: 35px;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .auth-card {
        max-width: 400px;
        padding: 1.5rem;
    }
    
    .auth-form {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        grid-column: 1;
    }
}

/* Remove any custom padding/margin from password toggle */
.password-toggle {
    right: 10px;
    width: auto;
    height: auto;
}

/* Adjust password strength meter */
.password-strength {
    margin-top: 0.5rem;
}

.strength-meter {
    height: 3px;
    margin-bottom: 0.25rem;
}

.strength-text {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>



