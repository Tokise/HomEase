/**
 * HomEase - Main JavaScript
 * This file contains common functionality used across the site
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeDropdowns();
    initializeFormValidation();
    initializeAlerts();
    initializeScrollEffects();
    
    // Set APP_URL for JavaScript use
    window.APP_URL = document.querySelector('meta[name="app-url"]')?.content || '';
});

/**
 * Initialize dropdown menus
 */
function initializeDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        const dropdown = toggle.nextElementSibling;
        
        if (!dropdown) return;
        
        // Toggle dropdown on click
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdown.classList.remove('show');
        });
        
        // Prevent dropdown from closing when clicking inside it
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            
            // Get all required inputs
            const requiredInputs = form.querySelectorAll('[required]');
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    showInputError(input, 'This field is required');
                } else {
                    clearInputError(input);
                    
                    // Validate email fields
                    if (input.type === 'email' && !validateEmail(input.value)) {
                        isValid = false;
                        showInputError(input, 'Please enter a valid email address');
                    }
                    
                    // Validate password fields
                    if (input.type === 'password' && input.minLength && input.value.length < input.minLength) {
                        isValid = false;
                        showInputError(input, `Password must be at least ${input.minLength} characters`);
                    }
                    
                    // Validate password confirmation
                    if (input.id === 'confirm_password') {
                        const password = form.querySelector('#password');
                        if (password && input.value !== password.value) {
                            isValid = false;
                            showInputError(input, 'Passwords do not match');
                        }
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Live validation on input
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    showInputError(input, 'This field is required');
                } else {
                    clearInputError(input);
                    
                    // Validate email fields on input
                    if (input.type === 'email' && input.value && !validateEmail(input.value)) {
                        showInputError(input, 'Please enter a valid email address');
                    }
                    
                    // Validate password confirmation on input
                    if (input.id === 'confirm_password') {
                        const password = form.querySelector('#password');
                        if (password && input.value && input.value !== password.value) {
                            showInputError(input, 'Passwords do not match');
                        }
                    }
                }
            });
        });
    });
}

/**
 * Show error message for an input
 */
function showInputError(input, message) {
    input.classList.add('is-invalid');
    
    // Find or create error message element
    let errorElement = input.parentElement.querySelector('.invalid-feedback');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        input.parentElement.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

/**
 * Clear error message for an input
 */
function clearInputError(input) {
    input.classList.remove('is-invalid');
    
    const errorElement = input.parentElement.querySelector('.invalid-feedback');
    if (errorElement) {
        errorElement.textContent = '';
    }
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Initialize alert messages
 */
function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        // Add close button if not present
        if (!alert.querySelector('.alert-close')) {
            const closeButton = document.createElement('button');
            closeButton.className = 'alert-close';
            closeButton.innerHTML = '&times;';
            closeButton.setAttribute('aria-label', 'Close');
            alert.appendChild(closeButton);
            
            closeButton.addEventListener('click', () => {
                alert.remove();
            });
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.classList.add('alert-fade-out');
            
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

/**
 * Initialize scroll effects
 */
function initializeScrollEffects() {
    // Add shadow to header on scroll
    const header = document.querySelector('.site-header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = header ? header.offsetHeight : 0;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Reveal elements on scroll
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    
    if (revealElements.length > 0) {
        const revealElementsOnScroll = () => {
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('revealed');
                }
            });
        };
        
        // Initial check
        revealElementsOnScroll();
        
        // Check on scroll
        window.addEventListener('scroll', revealElementsOnScroll);
    }
}

/**
 * Format currency
 */
function formatCurrency(amount, currencyCode = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currencyCode
    }).format(amount);
}

/**
 * Format date
 */
function formatDate(dateString, format = 'medium') {
    const date = new Date(dateString);
    
    const options = {
        short: { month: 'numeric', day: 'numeric', year: '2-digit' },
        medium: { month: 'short', day: 'numeric', year: 'numeric' },
        long: { month: 'long', day: 'numeric', year: 'numeric' }
    };
    
    return new Intl.DateTimeFormat('en-US', options[format]).format(date);
}

/**
 * Debounce function to limit how often a function can be called
 */
function debounce(func, wait = 300) {
    let timeout;
    
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Make AJAX request
 */
async function makeRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            method: options.method || 'GET',
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            body: options.body ? JSON.stringify(options.body) : null
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Request error:', error);
        throw error;
    }
} 