@extends('layouts.app')

@section('title', 'Cookie Policy')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="page-header text-center mb-5">
                <h1 class="page-title">
                    <i class="fas fa-shield-alt text-primary me-3"></i>
                    Cookie Policy
                </h1>
                <p class="lead text-muted">
                    How we use cookies to enhance your experience on our website
                </p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-5">
                        <h2 class="h4 mb-3">What are cookies?</h2>
                        <p>
                            Cookies are small text files that are stored on your computer or mobile device when you visit a website.
                            They help websites remember information about your visit, which can make it easier for you to visit the
                            site again and make the site more useful to you.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">How we use cookies</h2>
                        <p>
                            City Life International Church uses cookies to improve your experience on our website,
                            understand how you interact with our content, and provide personalized features.
                            We are committed to protecting your privacy and being transparent about our data practices.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">Types of cookies we use</h2>

                        @foreach($categories as $categoryKey => $category)
                        <div class="cookie-category-card mb-4">
                            <div class="d-flex align-items-start">
                                <div class="cookie-category-icon me-3">
                                    @if($categoryKey === 'essential')
                                        <i class="fas fa-shield-alt text-danger"></i>
                                    @elseif($categoryKey === 'analytics')
                                        <i class="fas fa-chart-line text-info"></i>
                                    @elseif($categoryKey === 'marketing')
                                        <i class="fas fa-bullhorn text-warning"></i>
                                    @else
                                        <i class="fas fa-cogs text-success"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="h5 mb-2">
                                        {{ $category['name'] }}
                                        @if($category['required'])
                                            <span class="badge bg-danger ms-2">Required</span>
                                        @else
                                            <span class="badge bg-secondary ms-2">Optional</span>
                                        @endif
                                    </h4>
                                    <p class="text-muted mb-3">{{ $category['description'] }}</p>

                                    <div class="cookies-list">
                                        <h6 class="small text-uppercase text-muted mb-2">Cookies in this category:</h6>
                                        @foreach($category['cookies'] as $cookieName => $cookieDescription)
                                        <div class="cookie-item mb-2">
                                            <strong class="text-dark">{{ $cookieName }}</strong>
                                            <span class="text-muted ms-2">- {{ $cookieDescription }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">Managing your cookie preferences</h2>
                        <p>
                            You can change your cookie preferences at any time by clicking the "Cookie Settings"
                            button below. You can also manage cookies through your browser settings.
                        </p>

                        <div class="d-flex gap-3 mt-4">
                            <button type="button" class="btn btn-primary" onclick="window.cookieConsent.showPreferences()">
                                <i class="fas fa-cog me-2"></i>Cookie Settings
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="showBrowserInstructions()">
                                <i class="fas fa-browser me-2"></i>Browser Settings
                            </button>
                        </div>
                    </div>

                    <div class="mb-5" id="browserInstructions" style="display: none;">
                        <h2 class="h4 mb-3">Browser cookie settings</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fab fa-chrome text-primary me-2"></i>Chrome
                                        </h5>
                                        <p class="card-text small">
                                            Settings → Privacy and security → Cookies and other site data
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fab fa-firefox text-warning me-2"></i>Firefox
                                        </h5>
                                        <p class="card-text small">
                                            Options → Privacy & Security → Cookies and Site Data
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fab fa-safari text-info me-2"></i>Safari
                                        </h5>
                                        <p class="card-text small">
                                            Preferences → Privacy → Manage Website Data
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fab fa-edge text-primary me-2"></i>Edge
                                        </h5>
                                        <p class="card-text small">
                                            Settings → Cookies and site permissions → Cookies and site data
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">Third-party cookies</h2>
                        <p>
                            Some cookies on our website are set by third-party services. These may include:
                        </p>
                        <ul>
                            <li><strong>Google Analytics:</strong> Helps us understand website usage and improve our content</li>
                            <li><strong>YouTube:</strong> Embedded videos from our YouTube channel</li>
                            <li><strong>Social Media:</strong> Social sharing buttons and embedded content</li>
                        </ul>
                        <p>
                            These third parties have their own privacy policies, and we encourage you to review them.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">Data retention</h2>
                        <p>
                            Cookie consent preferences are stored for 12 months. After this period,
                            you will be asked to provide your consent again. You can withdraw or modify
                            your consent at any time using the cookie settings.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h4 mb-3">Contact us</h2>
                        <p>
                            If you have any questions about our cookie policy or how we handle your data,
                            please contact us:
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-envelope me-2"></i> Email: info@citylifechurch.org.uk</li>
                            <li><i class="fas fa-phone me-2"></i> Phone: +44 114 272 6908</li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Address: Kelham Island Museum, Sheffield S3 8RY</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Policy Updates
                        </h5>
                        <p class="mb-0">
                            This cookie policy was last updated on {{ date('F j, Y') }}.
                            We may update this policy from time to time, and any changes will be posted on this page.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cookie-category-card {
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
    border-left: 4px solid var(--citylife-base, #ff6b35);
}

.cookie-category-icon {
    font-size: 1.5rem;
    width: 40px;
    text-align: center;
}

.cookie-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.cookie-item:last-child {
    border-bottom: none;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    color: #2c3e50;
    font-weight: 600;
}
</style>

<script>
function showBrowserInstructions() {
    const instructions = document.getElementById('browserInstructions');
    if (instructions.style.display === 'none') {
        instructions.style.display = 'block';
        instructions.scrollIntoView({ behavior: 'smooth' });
    } else {
        instructions.style.display = 'none';
    }
}
</script>
@endsection
