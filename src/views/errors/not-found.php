<?php
// This is a standalone error page with its own header/footer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Page Not Found' ?> - HomEase</title>
    <style>
        :root {
            --color-primary: #3b82f6;
            --color-primary-dark: #2563eb;
            --color-primary-light: #60a5fa;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--color-gray-700);
            background-color: var(--color-gray-50);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        header {
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .site-logo {
            display: inline-block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primary);
            text-decoration: none;
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
        }
        
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 60vh;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: var(--color-primary);
            line-height: 1;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--color-gray-800);
        }
        
        .error-message {
            font-size: 1.125rem;
            max-width: 500px;
            margin-bottom: 2rem;
            color: var(--color-gray-600);
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: white;
            border: 1px solid var(--color-primary);
        }
        
        .btn-primary:hover {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--color-gray-700);
            border: 1px solid var(--color-gray-300);
        }
        
        .btn-outline:hover {
            background-color: var(--color-gray-100);
        }
        
        footer {
            background-color: white;
            padding: 1.5rem 0;
            border-top: 1px solid var(--color-gray-200);
            color: var(--color-gray-600);
            font-size: 0.875rem;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 1.75rem;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="<?= APP_URL ?>/" class="site-logo">
                    HomEase
                </a>
            </div>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div class="error-container">
                <div class="error-code">404</div>
                <h1 class="error-title">Page Not Found</h1>
                <p class="error-message"><?= $message ?? 'The page you are looking for does not exist.' ?></p>
                <div class="error-actions">
                    <a href="<?= APP_URL ?>/" class="btn btn-primary">Back to Home</a>
                    <a href="javascript:history.back()" class="btn btn-outline">Go Back</a>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="copyright">&copy; <?= date('Y') ?> HomEase. All rights reserved.</div>
                <div class="footer-links">
                    <a href="<?= APP_URL ?>/terms-of-service">Terms</a> |
                    <a href="<?= APP_URL ?>/privacy-policy">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html> 