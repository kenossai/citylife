{{-- Cookie Consent Banner Component --}}
<div id="cookieConsent" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-container">
        <div class="cookie-consent-content">
            <div class="cookie-consent-icon">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <div class="cookie-consent-text">
                <h4>We use cookies</h4>
                <p>This website uses cookies to enhance user experience and to analyze website performance and traffic.
                By clicking "Accept All", you consent to our use of cookies. You can manage your preferences or learn more in our
                <a href="#" id="cookiePolicyLink">Cookie Policy</a> and
                <a href="#" id="privacyPolicyLink">Privacy Policy</a>.</p>
            </div>
        </div>
        <div class="cookie-consent-actions">
            <button type="button" id="cookieRejectBtn" class="cookie-btn cookie-btn-secondary">
                <i class="fas fa-times"></i> Reject All
            </button>
            <button type="button" id="cookieSettingsBtn" class="cookie-btn cookie-btn-settings">
                <i class="fas fa-cog"></i> Settings
            </button>
            <button type="button" id="cookieAcceptBtn" class="cookie-btn cookie-btn-primary">
                <i class="fas fa-check"></i> Accept All
            </button>
        </div>
    </div>
</div>

{{-- Cookie Settings Modal --}}
<div id="cookieSettingsModal" class="cookie-modal" style="display: none;">
    <div class="cookie-modal-overlay"></div>
    <div class="cookie-modal-content">
        <div class="cookie-modal-header">
            <h3><i class="fas fa-shield-alt"></i> Cookie Settings</h3>
            <button type="button" id="closeCookieModal" class="cookie-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="cookie-modal-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="cookie-category-info">
                        <h4>Essential Cookies</h4>
                        <p>These cookies are necessary for the website to function and cannot be disabled.</p>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="essentialCookies" checked disabled>
                        <label for="essentialCookies">Always Active</label>
                    </div>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="cookie-category-info">
                        <h4>Analytics Cookies</h4>
                        <p>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</p>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="analyticsCookies">
                        <label for="analyticsCookies">Enable</label>
                    </div>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="cookie-category-info">
                        <h4>Marketing Cookies</h4>
                        <p>These cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging.</p>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="marketingCookies">
                        <label for="marketingCookies">Enable</label>
                    </div>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="cookie-category-info">
                        <h4>Functional Cookies</h4>
                        <p>These cookies enable the website to provide enhanced functionality and personalization.</p>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="functionalCookies">
                        <label for="functionalCookies">Enable</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="cookie-modal-footer">
            <button type="button" id="rejectAllCookies" class="cookie-btn cookie-btn-secondary">
                Reject All
            </button>
            <button type="button" id="savePreferences" class="cookie-btn cookie-btn-primary">
                Save Preferences
            </button>
        </div>
    </div>
</div>

<style>
/* Cookie Consent Banner Styles */
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 20px;
    z-index: 9999;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
    border-top: 3px solid var(--citylife-base, #ff6b35);
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-consent-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.cookie-consent-content {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
    min-width: 300px;
}

.cookie-consent-icon {
    font-size: 2.5rem;
    color: var(--citylife-base, #ff6b35);
    flex-shrink: 0;
}

.cookie-consent-text h4 {
    margin: 0 0 8px 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: white;
}

.cookie-consent-text p {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #ecf0f1;
}

.cookie-consent-text a {
    color: var(--citylife-base, #ff6b35);
    text-decoration: underline;
    font-weight: 500;
}

.cookie-consent-text a:hover {
    color: #ff8c69;
    text-decoration: none;
}

.cookie-consent-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.cookie-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 120px;
    justify-content: center;
}

.cookie-btn-primary {
    background: var(--citylife-base, #ff6b35);
    color: white;
}

.cookie-btn-primary:hover {
    background: #ff8c69;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
}

.cookie-btn-secondary {
    background: transparent;
    color: #bdc3c7;
    border: 1px solid #bdc3c7;
}

.cookie-btn-secondary:hover {
    background: #bdc3c7;
    color: #2c3e50;
}

.cookie-btn-settings {
    background: #3498db;
    color: white;
}

.cookie-btn-settings:hover {
    background: #5dade2;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

/* Cookie Modal Styles */
.cookie-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.cookie-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.cookie-modal-content {
    position: relative;
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    animation: modalAppear 0.3s ease-out;
}

@keyframes modalAppear {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.cookie-modal-header {
    padding: 25px 25px 20px;
    border-bottom: 1px solid #ecf0f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.cookie-modal-header h3 {
    margin: 0;
    font-size: 1.4rem;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cookie-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #7f8c8d;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.cookie-modal-close:hover {
    background: #e74c3c;
    color: white;
}

.cookie-modal-body {
    padding: 25px;
    max-height: 400px;
    overflow-y: auto;
}

.cookie-category {
    margin-bottom: 25px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--citylife-base, #ff6b35);
}

.cookie-category:last-child {
    margin-bottom: 0;
}

.cookie-category-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
}

.cookie-category-info h4 {
    margin: 0 0 8px 0;
    font-size: 1.1rem;
    color: #2c3e50;
    font-weight: 600;
}

.cookie-category-info p {
    margin: 0;
    font-size: 0.9rem;
    color: #7f8c8d;
    line-height: 1.5;
}

.cookie-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.cookie-toggle input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--citylife-base, #ff6b35);
}

.cookie-toggle label {
    font-size: 0.9rem;
    color: #2c3e50;
    font-weight: 500;
    cursor: pointer;
}

.cookie-modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #ecf0f1;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    background: #f8f9fa;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cookie-consent-container {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .cookie-consent-content {
        flex-direction: column;
        text-align: center;
        min-width: auto;
    }

    .cookie-consent-actions {
        justify-content: center;
        width: 100%;
    }

    .cookie-btn {
        flex: 1;
        min-width: auto;
    }

    .cookie-modal-content {
        margin: 10px;
        max-height: 90vh;
    }

    .cookie-category-header {
        flex-direction: column;
        gap: 15px;
    }

    .cookie-modal-footer {
        flex-direction: column;
    }

    .cookie-modal-footer .cookie-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .cookie-consent-banner {
        padding: 15px;
    }

    .cookie-consent-text h4 {
        font-size: 1.1rem;
    }

    .cookie-consent-text p {
        font-size: 0.85rem;
    }

    .cookie-btn {
        padding: 8px 16px;
        font-size: 0.85rem;
    }
}
</style>

<script>
// Cookie Consent Management
class CookieConsent {
    constructor() {
        this.cookieName = 'citylife_cookie_consent';
        this.consentData = this.loadConsent();
        this.init();
    }

    init() {
        // Show banner if no consent has been given
        if (!this.consentData) {
            this.showBanner();
        }

        // Add event listeners
        this.addEventListeners();

        // Load approved cookies
        this.loadApprovedCookies();
    }

    addEventListeners() {
        // Banner buttons
        document.getElementById('cookieAcceptBtn')?.addEventListener('click', () => this.acceptAll());
        document.getElementById('cookieRejectBtn')?.addEventListener('click', () => this.rejectAll());
        document.getElementById('cookieSettingsBtn')?.addEventListener('click', () => this.showSettings());

        // Modal buttons
        document.getElementById('closeCookieModal')?.addEventListener('click', () => this.hideSettings());
        document.getElementById('savePreferences')?.addEventListener('click', () => this.savePreferences());
        document.getElementById('rejectAllCookies')?.addEventListener('click', () => this.rejectAll());

        // Policy links
        document.getElementById('cookiePolicyLink')?.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = '/cookie-policy';
        });

        document.getElementById('privacyPolicyLink')?.addEventListener('click', (e) => {
            e.preventDefault();
            // Update this to your actual privacy policy URL
            window.location.href = '/privacy-policy';
        });

        // Modal overlay click
        document.querySelector('.cookie-modal-overlay')?.addEventListener('click', () => this.hideSettings());
    }

    showBanner() {
        document.getElementById('cookieConsent').style.display = 'block';
    }

    hideBanner() {
        document.getElementById('cookieConsent').style.display = 'none';
    }

    showSettings() {
        // Load current preferences into modal
        if (this.consentData) {
            document.getElementById('analyticsCookies').checked = this.consentData.analytics || false;
            document.getElementById('marketingCookies').checked = this.consentData.marketing || false;
            document.getElementById('functionalCookies').checked = this.consentData.functional || false;
        }

        document.getElementById('cookieSettingsModal').style.display = 'flex';
    }

    hideSettings() {
        document.getElementById('cookieSettingsModal').style.display = 'none';
    }

    acceptAll() {
        const consent = {
            essential: true,
            analytics: true,
            marketing: true,
            functional: true,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(consent);
        this.hideBanner();
        this.loadApprovedCookies();
        this.trackConsentEvent('accept_all');
    }

    rejectAll() {
        const consent = {
            essential: true,
            analytics: false,
            marketing: false,
            functional: false,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(consent);
        this.hideBanner();
        this.hideSettings();
        this.removeNonEssentialCookies();
        this.trackConsentEvent('reject_all');
    }

    savePreferences() {
        const consent = {
            essential: true,
            analytics: document.getElementById('analyticsCookies').checked,
            marketing: document.getElementById('marketingCookies').checked,
            functional: document.getElementById('functionalCookies').checked,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(consent);
        this.hideBanner();
        this.hideSettings();
        this.loadApprovedCookies();
        this.trackConsentEvent('save_preferences');
    }

    saveConsent(consent) {
        this.consentData = consent;

        // Save to localStorage
        localStorage.setItem(this.cookieName, JSON.stringify(consent));

        // Send to backend
        fetch('/cookie-consent/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(consent)
        }).then(response => {
            if (!response.ok) {
                console.warn('Failed to save consent to backend');
            }
        }).catch(error => {
            console.warn('Error saving consent to backend:', error);
        });

        console.log('Cookie consent saved:', consent);
    }

    loadConsent() {
        // Try localStorage first
        const stored = localStorage.getItem(this.cookieName);
        if (stored) {
            try {
                return JSON.parse(stored);
            } catch (e) {
                console.warn('Failed to parse stored consent:', e);
            }
        }

        // Fallback to cookie
        const cookieValue = this.getCookie(this.cookieName);
        if (cookieValue) {
            try {
                return JSON.parse(cookieValue);
            } catch (e) {
                console.warn('Failed to parse cookie consent:', e);
            }
        }

        return null;
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    loadApprovedCookies() {
        if (!this.consentData) return;

        // Load Google Analytics if analytics consent given
        if (this.consentData.analytics) {
            this.loadGoogleAnalytics();
        }

        // Load marketing scripts if marketing consent given
        if (this.consentData.marketing) {
            this.loadMarketingScripts();
        }

        // Load functional scripts if functional consent given
        if (this.consentData.functional) {
            this.loadFunctionalScripts();
        }
    }

    loadGoogleAnalytics() {
        // Example: Load Google Analytics
        // Replace 'GA_MEASUREMENT_ID' with your actual GA measurement ID
        /*
        window.gtag = window.gtag || function() {
            (window.gtag.q = window.gtag.q || []).push(arguments);
        };

        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID';
        document.head.appendChild(script);

        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
        */
        console.log('Analytics cookies loaded');
    }

    loadMarketingScripts() {
        // Example: Load marketing/advertising scripts
        console.log('Marketing cookies loaded');
    }

    loadFunctionalScripts() {
        // Example: Load functional enhancement scripts
        console.log('Functional cookies loaded');
    }

    removeNonEssentialCookies() {
        // Remove non-essential cookies when rejected
        const cookies = document.cookie.split(';');

        cookies.forEach(cookie => {
            const eqPos = cookie.indexOf('=');
            const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();

            // List of essential cookies to keep
            const essentialCookies = [
                'citylife_cookie_consent',
                'XSRF-TOKEN',
                'laravel_session',
                '_token'
            ];

            if (!essentialCookies.includes(name)) {
                // Remove the cookie
                document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
                document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=${window.location.hostname};`;
            }
        });
    }

    trackConsentEvent(action) {
        // Track consent events for analytics (only if analytics consent is given)
        if (this.consentData && this.consentData.analytics && typeof gtag === 'function') {
            gtag('event', 'cookie_consent', {
                'action': action,
                'timestamp': new Date().toISOString()
            });
        }
    }

    // Public method to check if a specific cookie type is consented
    hasConsent(type) {
        return this.consentData && this.consentData[type] === true;
    }

    // Public method to show preferences modal (for use in footer links etc.)
    showPreferences() {
        this.showSettings();
    }
}

// Initialize cookie consent when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.cookieConsent = new CookieConsent();
});

// Global function to check cookie consent (for use in other scripts)
window.hasCookieConsent = function(type) {
    return window.cookieConsent && window.cookieConsent.hasConsent(type);
};
</script>
