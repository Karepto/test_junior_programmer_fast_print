/**
 * Pure CSS Loading System - No Build Tools Required
 * This system works independently of Vite or any build tools
 */

class PureCSSLoader {
    constructor(options = {}) {
        this.options = {
            defaultEffect: 'spinner', // spinner, dots, pulse, wave, clock, bounce, bars, square
            loadingText: 'Memuat halaman...',
            minLoadingTime: 500,
            fadeOutDuration: 300,
            showProgressBar: true,
            enableAutoDetection: true,
            ...options
        };
        
        this.isLoading = false;
        this.startTime = null;
        this.overlay = null;
        this.progressBar = null;
        
        // Initialize if DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.createLoadingElements();
        
        if (this.options.enableAutoDetection) {
            this.attachAutoDetection();
        }
        
        // Show loading on initial page load if needed
        if (document.readyState === 'loading') {
            this.show();
            window.addEventListener('load', () => this.hide());
        }
    }

    createLoadingElements() {
        // Create main overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'pure-css-fullscreen-loading';
        this.overlay.id = 'pure-css-loading-overlay';
        this.overlay.style.display = 'none';
        
        // Create progress bar if enabled
        if (this.options.showProgressBar) {
            this.progressBar = document.createElement('div');
            this.progressBar.className = 'pure-css-progress-bar';
            this.progressBar.id = 'pure-css-progress-bar';
            this.progressBar.style.position = 'fixed';
            this.progressBar.style.top = '0';
            this.progressBar.style.left = '0';
            this.progressBar.style.width = '100%';
            this.progressBar.style.zIndex = '10000';
            this.progressBar.style.display = 'none';
            document.body.appendChild(this.progressBar);
        }
        
        document.body.appendChild(this.overlay);
    }

    getLoaderHTML(effect = null) {
        const loaderType = effect || this.options.defaultEffect;
        
        const loaders = {
            spinner: '<div class="pure-css-spinner"></div>',
            dots: `<div class="pure-css-dots">
                      <div></div><div></div><div></div><div></div>
                   </div>`,
            pulse: '<div class="pure-css-pulse"></div>',
            wave: `<div class="pure-css-wave">
                     <div class="rect1"></div><div class="rect2"></div>
                     <div class="rect3"></div><div class="rect4"></div>
                     <div class="rect5"></div>
                   </div>`,
            clock: '<div class="pure-css-clock"></div>',
            bounce: '<div class="pure-css-bounce"></div>',
            bars: `<div class="pure-css-bars">
                     <div></div><div></div><div></div>
                   </div>`,
            square: '<div class="pure-css-square"></div>',
            ripple: `<div class="pure-css-ripple">
                       <div></div><div></div>
                     </div>`
        };

        return loaders[loaderType] || loaders.spinner;
    }

    attachAutoDetection() {
        // Intercept all navigation links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (this.shouldInterceptLink(link)) {
                e.preventDefault();
                this.show();
                setTimeout(() => {
                    window.location.href = link.href;
                }, 100);
            }
        });

        // Intercept form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (this.shouldInterceptForm(form)) {
                this.show();
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', () => {
            this.show();
            setTimeout(() => this.hide(), this.options.minLoadingTime);
        });
    }

    shouldInterceptLink(link) {
        if (!link || !link.href) return false;
        
        // Skip external links, mailto, tel, etc.
        if (link.target === '_blank' || 
            link.href.startsWith('mailto:') || 
            link.href.startsWith('tel:') ||
            link.href.startsWith('javascript:')) {
            return false;
        }
        
        // Skip if has data-no-loading attribute
        if (link.hasAttribute('data-no-loading')) {
            return false;
        }
        
        // Skip Bootstrap dropdown toggles - Enhanced check
        if (link.hasAttribute('data-bs-toggle')) {
            const toggleType = link.getAttribute('data-bs-toggle');
            if (toggleType === 'dropdown' || toggleType === 'modal' || 
                toggleType === 'collapse' || toggleType === 'offcanvas') {
                return false;
            }
        }
        
        // Skip elements with dropdown classes
        if (link.classList.contains('dropdown-toggle') || 
            link.closest('.dropdown-toggle') ||
            link.closest('.dropdown')) {
            return false;
        }
        
        // Skip anchor links on same page
        if (link.href.includes('#') && 
            link.href.split('#')[0] === window.location.href.split('#')[0]) {
            return false;
        }
        
        // Only internal links
        try {
            const linkUrl = new URL(link.href);
            return linkUrl.origin === window.location.origin;
        } catch {
            return false;
        }
    }

    shouldInterceptForm(form) {
        return !form.hasAttribute('data-no-loading') && 
               !form.hasAttribute('data-ajax');
    }

    show(effect = null, text = null) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.startTime = Date.now();
        
        const loadingText = text || this.options.loadingText;
        const loaderHTML = this.getLoaderHTML(effect);
        
        this.overlay.innerHTML = `
            ${loaderHTML}
            <div class="loading-text">${loadingText}</div>
        `;
        
        this.overlay.style.display = 'flex';
        
        // Show progress bar
        if (this.progressBar) {
            this.progressBar.style.display = 'block';
            this.animateProgressBar();
        }
        
        // Add body class
        document.body.classList.add('pure-css-loading-active');
    }

    hide() {
        if (!this.isLoading) return;
        
        const elapsed = Date.now() - this.startTime;
        const remainingTime = Math.max(0, this.options.minLoadingTime - elapsed);
        
        setTimeout(() => {
            this.overlay.style.opacity = '0';
            this.overlay.style.transition = `opacity ${this.options.fadeOutDuration}ms ease`;
            
            setTimeout(() => {
                this.overlay.style.display = 'none';
                this.overlay.style.opacity = '1';
                this.overlay.style.transition = '';
                
                if (this.progressBar) {
                    this.progressBar.style.display = 'none';
                }
                
                document.body.classList.remove('pure-css-loading-active');
                this.isLoading = false;
            }, this.options.fadeOutDuration);
        }, remainingTime);
    }

    animateProgressBar() {
        if (!this.progressBar || !this.isLoading) return;
        
        // Reset progress bar
        this.progressBar.style.width = '0%';
        this.progressBar.style.transition = 'width 0.3s ease';
        
        let progress = 0;
        const interval = setInterval(() => {
            if (!this.isLoading) {
                clearInterval(interval);
                this.progressBar.style.width = '100%';
                return;
            }
            
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            
            this.progressBar.style.width = progress + '%';
            
            if (progress >= 90) {
                clearInterval(interval);
            }
        }, 200);
    }

    // Public methods for manual control
    showLoading(effect, text) {
        this.show(effect, text);
    }

    hideLoading() {
        this.hide();
    }

    setDefaultEffect(effect) {
        this.options.defaultEffect = effect;
    }

    destroy() {
        if (this.overlay && this.overlay.parentNode) {
            this.overlay.parentNode.removeChild(this.overlay);
        }
        if (this.progressBar && this.progressBar.parentNode) {
            this.progressBar.parentNode.removeChild(this.progressBar);
        }
        document.body.classList.remove('pure-css-loading-active');
    }
}

// Auto-initialize when DOM is ready
let pureCSSLoader = null;

function initPureCSSLoader() {
    if (!pureCSSLoader) {
        pureCSSLoader = new PureCSSLoader({
            defaultEffect: 'spinner',
            loadingText: 'Memuat halaman...',
            minLoadingTime: 300,
            showProgressBar: true,
            enableAutoDetection: true
        });
        
        // Make it globally accessible
        window.pureCSSLoader = pureCSSLoader;
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPureCSSLoader);
} else {
    initPureCSSLoader();
}

// Utility functions for easy use
window.showPureCSSLoading = function(effect, text) {
    if (window.pureCSSLoader) {
        window.pureCSSLoader.showLoading(effect, text);
    }
};

window.hidePureCSSLoading = function() {
    if (window.pureCSSLoader) {
        window.pureCSSLoader.hideLoading();
    }
};

// Export for module systems if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PureCSSLoader;
}