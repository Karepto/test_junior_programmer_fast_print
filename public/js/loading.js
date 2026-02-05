// Simple Loading System - No Vite Required
// Loading overlay management
function showLoading(message = 'Loading...') {
    const overlay = document.createElement('div');
    overlay.className = 'page-loading-overlay';
    overlay.id = 'loadingOverlay';
    
    overlay.innerHTML = `
        <div class="text-center">
            <div class="loading-spinner mb-3"></div>
            <div class="loading-text">${message}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('fade-out');
        setTimeout(() => {
            overlay.remove();
        }, 300);
    }
}

// Auto-hide loading on page load
document.addEventListener('DOMContentLoaded', function() {
    // Hide any existing loading
    setTimeout(hideLoading, 100);
    
    // Add loading to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            showLoading('Processing...');
        });
    });
    
    // Add loading to AJAX links
    const ajaxLinks = document.querySelectorAll('[data-loading]');
    ajaxLinks.forEach(link => {
        link.addEventListener('click', function() {
            const message = this.getAttribute('data-loading') || 'Loading...';
            showLoading(message);
        });
    });
});

// Progress bar functions
function showProgressBar() {
    const progressBar = document.createElement('div');
    progressBar.className = 'loading-bar animate';
    progressBar.id = 'progressBar';
    document.body.appendChild(progressBar);
}

function hideProgressBar() {
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressBar.remove();
        }, 300);
    }
}

// Button loading states
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.setAttribute('data-original-text', button.innerHTML);
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        button.classList.add('btn-loading');
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text') || button.innerHTML;
        button.classList.remove('btn-loading');
    }
}

// Card loading state
function setCardLoading(cardElement, loading = true) {
    if (loading) {
        cardElement.style.opacity = '0.6';
        cardElement.style.pointerEvents = 'none';
        
        const spinner = document.createElement('div');
        spinner.className = 'position-absolute top-50 start-50 translate-middle';
        spinner.innerHTML = '<div class="loading-pulse"></div>';
        spinner.id = 'cardSpinner';
        
        cardElement.style.position = 'relative';
        cardElement.appendChild(spinner);
    } else {
        cardElement.style.opacity = '1';
        cardElement.style.pointerEvents = 'auto';
        
        const spinner = cardElement.querySelector('#cardSpinner');
        if (spinner) {
            spinner.remove();
        }
    }
}

// Export functions for global use
window.LoadingSystem = {
    show: showLoading,
    hide: hideLoading,
    showProgress: showProgressBar,
    hideProgress: hideProgressBar,
    setButtonLoading: setButtonLoading,
    setCardLoading: setCardLoading
};