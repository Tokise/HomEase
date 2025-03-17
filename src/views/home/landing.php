<?php /* Header is already included by the Controller */ ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Home Services Made Easy</h1>
            <p class="hero-subtitle">Find reliable professionals for all your home service needs in one place.</p>
            <div class="hero-actions">
                <a href="<?= APP_URL ?>/services" class="btn btn-primary">Find Services</a>
                <a href="<?= APP_URL ?>/auth/register" class="btn btn-outline">Sign Up</a>
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,117.3C672,107,768,117,864,144C960,171,1056,213,1152,213.3C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
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
        <h2 class="section-title">Why Choose HomEase?</h2>
        
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
    <div class="section-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#f5f7fa" fill-opacity="1" d="M0,128L60,149.3C120,171,240,213,360,224C480,235,600,213,720,181.3C840,149,960,107,1080,106.7C1200,107,1320,149,1380,170.7L1440,192L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path></svg>
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
                <p>Join our network of trusted service providers and grow your business with HomEase.</p>
                <ul class="providers-benefits">
                    <li><i class="fas fa-check"></i> Reach more customers in your area</li>
                    <li><i class="fas fa-check"></i> Flexible scheduling that works for you</li>
                    <li><i class="fas fa-check"></i> Secure and timely payments</li>
                    <li><i class="fas fa-check"></i> Free profile and business tools</li>
                </ul>
                <a href="<?= APP_URL ?>/providers/register" class="btn btn-primary">Join as a Provider</a>
            </div>
            <div class="providers-image">
                <img src="<?= APP_URL ?>/assets/img/service-provider.jpg" alt="Service Provider">
            </div>
        </div>
    </div>
    <div class="section-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,138.7C672,139,768,181,864,208C960,235,1056,245,1152,229.3C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-title">What Our Customers Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"HomEase made finding a reliable plumber so easy! The service was prompt, professional and exactly what I needed. I'll definitely be using HomEase again."</p>
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
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"I've tried several home service platforms, but HomEase stands out for its quality and reliability. The cleaning service I booked exceeded my expectations!"</p>
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
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p>"As a busy mom, finding time for home maintenance is challenging. HomEase connected me with an excellent electrician who fixed everything efficiently and professionally."</p>
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
    // Add any page-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for the cards
        const animateOnScroll = document.querySelectorAll('.step-card, .feature-card, .service-card, .testimonial-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        animateOnScroll.forEach(item => {
            observer.observe(item);
        });
    });
</script>

<?php /* Footer is already included by the Controller */ ?> 