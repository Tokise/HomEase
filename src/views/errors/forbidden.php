<div class="error-container">
    <div class="error-content">
        <div class="error-code">403</div>
        <h1 class="error-title">Access Forbidden</h1>
        <p class="error-message"><?= $message ?? 'You do not have permission to access this resource.' ?></p>
        <div class="error-actions">
            <a href="<?= APP_URL ?>" class="btn btn-primary">Back to Home</a>
            <a href="javascript:history.back()" class="btn btn-outline">Go Back</a>
        </div>
    </div>
</div>

<style>
    .error-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        text-align: center;
        padding: var(--spacing-xxl) var(--spacing-md);
    }
    
    .error-content {
        max-width: 600px;
    }
    
    .error-code {
        font-size: 8rem;
        font-weight: 700;
        color: var(--color-primary);
        line-height: 1;
        margin-bottom: var(--spacing-md);
    }
    
    .error-title {
        font-size: 2rem;
        font-weight: 600;
        color: var(--color-gray-800);
        margin-bottom: var(--spacing-md);
    }
    
    .error-message {
        font-size: 1.125rem;
        color: var(--color-gray-600);
        margin-bottom: var(--spacing-xl);
    }
    
    .error-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .error-code {
            font-size: 6rem;
        }
        
        .error-title {
            font-size: 1.75rem;
        }
        
        .error-message {
            font-size: 1rem;
        }
        
        .error-actions {
            flex-direction: column;
            gap: var(--spacing-sm);
        }
    }
</style> 