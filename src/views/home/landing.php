<?php require_once SRC_PATH . '/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Home Services Made Easy</h1>
            <p class="hero-subtitle">Find reliable professionals for all your home service needs in one place.</p>
            <div class="hero-actions">
                <a href="<?= APP_URL ?>/services" class="btn btn-primary">Find Services</a>
                <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline-primary">Sign Up</a>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works-section">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Find Services</h3>
                <p>Browse our extensive catalog of home services or search for specific services you need.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Book An Appointment</h3>
                <p>Choose a convenient time and date that works best for your schedule.</p>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Get It Done</h3>
                <p>Our verified professional will arrive at your home and complete the service.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Why Choose HomeSwift?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Verified Professionals</h3>
                <p>All service providers are thoroughly vetted and background checked for your safety and peace of mind.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Secure Payments</h3>
                <p>Your payments are protected with our secure payment processing system with multiple payment options.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Highly Rated</h3>
                <p>Our service providers maintain high ratings from satisfied customers with verified reviews.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Easy Scheduling</h3>
                <p>Book services for the time that works best for your schedule with flexible appointment options.</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Services Section -->
<section class="services-section">
    <div class="container">
        <h2 class="section-title">Popular Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-broom"></i>
                </div>
                <h3>House Cleaning</h3>
                <p>Professional cleaning services for homes of all sizes.</p>
                <a href="<?= APP_URL ?>/services/cleaning" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <h3>Plumbing</h3>
                <p>Expert plumbing repairs and installations.</p>
                <a href="<?= APP_URL ?>/services/plumbing" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Electrical</h3>
                <p>Safe and reliable electrical services for your home.</p>
                <a href="<?= APP_URL ?>/services/electrical" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Gardening</h3>
                <p>Professional gardening and landscaping services.</p>
                <a href="<?= APP_URL ?>/services/gardening" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="services-cta">
            <a href="<?= APP_URL ?>/services" class="btn btn-outline">View All Services</a>
        </div>
    </div>
</section>

<!-- Service Providers Section -->
<section class="providers-section">
    <div class="container">
        <div class="providers-content">
            <div class="providers-info">
                <h2>Are You a Service Professional?</h2>
                <p>Join our network of trusted service providers and grow your business with HomeSwift.</p>
                <ul class="providers-benefits">
                    <li><i class="fas fa-check"></i> Reach more customers in your area</li>
                    <li><i class="fas fa-check"></i> Flexible scheduling that works for you</li>
                    <li><i class="fas fa-check"></i> Secure and timely payments</li>
                    <li><i class="fas fa-check"></i> Free profile and business tools</li>
                </ul>
                <a href="<?= APP_URL ?>/providers/register" class="btn btn-primary">Join as a Provider</a>
            </div>
            <div class="providers-image">
                <img src="<?= APP_URL ?>/assets/img/provider-hero.png" alt="Service Provider">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-title">What Our Customers Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card" data-delay="0">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"HomeSwift made finding a reliable plumber so easy! The service was prompt, professional and exactly what I needed. I'll definitely be using HomeSwift again."</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="<?= APP_URL ?>/assets/img/testimonials/avatar-1.jpg" alt="Sarah J.">
                    </div>
                    <div class="testimonial-info">
                        <h4>Sarah J.</h4>
                        <p>Plumbing Service</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card" data-delay="200">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"I've tried several home service platforms, but HomeSwift stands out for its quality and reliability. The cleaning service I booked exceeded my expectations!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="<?= APP_URL ?>/assets/img/testimonials/avatar-2.jpg" alt="Michael T.">
                    </div>
                    <div class="testimonial-info">
                        <h4>Michael T.</h4>
                        <p>Cleaning Service</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card" data-delay="400">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p>"As a busy mom, finding time for home maintenance is challenging. HomeSwift connected me with an excellent electrician who fixed everything efficiently and professionally."</p>
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        <img src="<?= APP_URL ?>/assets/img/testimonials/avatar-3.jpg" alt="Jennifer R.">
                    </div>
                    <div class="testimonial-info">
                        <h4>Jennifer R.</h4>
                        <p>Electrical Service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to transform your home service experience?</h2>
            <p>Join thousands of satisfied customers and find the perfect service for your home needs.</p>
            <div class="cta-buttons">
                <a href="<?= APP_URL ?>/auth/register" class="btn btn-light">Sign Up Now</a>
                <a href="<?= APP_URL ?>/services" class="btn btn-outline-light">Browse Services</a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate elements when they come into view
        const animateOnScroll = function() {
            const cards = document.querySelectorAll('.step-card, .feature-card, .service-card, .testimonial-card');
            
            cards.forEach(card => {
                const cardPosition = card.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.2;
                
                if (cardPosition < screenPosition) {
                    // Check if the card has a delay attribute
                    const delay = card.getAttribute('data-delay');
                    if (delay) {
                        setTimeout(() => {
                            card.classList.add('animated');
                        }, parseInt(delay));
                    } else {
                        card.classList.add('animated');
                    }
                }
            });
        };
        
        // Run on load
        animateOnScroll();
        
        // Run on scroll
        window.addEventListener('scroll', animateOnScroll);
        
        // Handle missing testimonial avatar images
        document.querySelectorAll('.testimonial-avatar img').forEach(img => {
            img.onerror = function() {
                // Replace with a default avatar if image fails to load
                this.src = `${APP_URL}/assets/img/default-avatar.jpg`;
                
                // If the default also fails, use an initial avatar
                this.onerror = function() {
                    const parent = this.closest('.testimonial-author');
                    const nameElement = parent.querySelector('.testimonial-info h4');
                    const name = nameElement ? nameElement.textContent.trim() : 'User';
                    const initial = name.charAt(0).toUpperCase();
                    
                    const avatar = document.createElement('div');
                    avatar.className = 'initial-avatar';
                    avatar.textContent = initial;
                    
                    this.parentNode.replaceChild(avatar, this);
                };
            };
        });
    });
</script>

<?php require_once SRC_PATH . '/views/layouts/footer.php'; ?>