<x-app-layout>
@section('title', 'Cookie Policy')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Cookie Policy</h2>
        <p class="page-header__text">How we use cookies to enhance your experience</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Cookie Policy</span></li>
        </ul>
    </div>
</section>

<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-12">
                <div class="about-one__content">
                <div class="about-one__content">
                    <div class="section-header">
                        <div class="section-header__top">
                            <div class="section-header__top__left">
                                <span class="section-header__subtitle">Privacy & Data Protection</span>
                                <h2 class="section-header__title">
                                    <i class="fas fa-shield-alt" style="color: var(--citylife-base, #ff6b35); margin-right: 15px;"></i>
                                    Cookie Information
                                </h2>
                            </div>
                        </div>
                        <p class="section-header__text">
                            We believe in transparency about how we collect and use data. This policy explains how we use cookies
                            and similar technologies to provide you with a better experience on our website.
                        </p>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">What are cookies?</h3>
                        <p class="about-one__content__text">
                            Cookies are small text files that are stored on your computer or mobile device when you visit a website.
                            They help websites remember information about your visit, which can make it easier for you to visit the
                            site again and make the site more useful to you.
                        </p>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">How we use cookies</h3>
                        <p class="about-one__content__text">
                            City Life International Church uses cookies to improve your experience on our website,
                            understand how you interact with our content, and provide personalized features.
                            We are committed to protecting your privacy and being transparent about our data practices.
                        </p>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">Types of cookies we use</h3>

                        @foreach($categories as $categoryKey => $category)
                        <div class="cookie-category-item">
                            <div class="cookie-category-header">
                                <div class="cookie-category-icon">
                                    @if($categoryKey === 'essential')
                                        <i class="fas fa-shield-alt"></i>
                                    @elseif($categoryKey === 'analytics')
                                        <i class="fas fa-chart-line"></i>
                                    @elseif($categoryKey === 'marketing')
                                        <i class="fas fa-bullhorn"></i>
                                    @else
                                        <i class="fas fa-cogs"></i>
                                    @endif
                                </div>
                                <div class="cookie-category-content">
                                    <h4 class="cookie-category-title">
                                        {{ $category['name'] }}
                                        @if($category['required'])
                                            <span class="cookie-badge cookie-badge--required">Required</span>
                                        @else
                                            <span class="cookie-badge cookie-badge--optional">Optional</span>
                                        @endif
                                    </h4>
                                    <p class="cookie-category-description">{{ $category['description'] }}</p>

                                    <div class="cookie-details">
                                        <h6 class="cookie-details-title">Cookies in this category:</h6>
                                        @foreach($category['cookies'] as $cookieName => $cookieDescription)
                                        <div class="cookie-detail-item">
                                            <strong class="cookie-name">{{ $cookieName }}</strong>
                                            <span class="cookie-description">- {{ $cookieDescription }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">Managing your cookie preferences</h3>
                        <p class="about-one__content__text">
                            You can change your cookie preferences at any time by clicking the "Cookie Settings"
                            button below. You can also manage cookies through your browser settings.
                        </p>

                        <div class="cookie-actions">
                            <a href="javascript:void(0)" onclick="window.cookieConsent.showPreferences()" class="citylife-btn">
                                <span class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-settings"></span></div>
                                </span>
                                <span class="citylife-btn__text">Cookie Settings</span>
                            </a>
                            <a href="javascript:void(0)" onclick="showBrowserInstructions()" class="citylife-btn citylife-btn--border">
                                <span class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-browser"></span></div>
                                </span>
                                <span class="citylife-btn__text">Browser Settings</span>
                            </a>
                        </div>
                    </div>

                    <div class="browser-instructions" id="browserInstructions" style="display: none;">
                        <h3 class="about-one__content__title">Browser cookie settings</h3>
                        <div class="row gutter-y-30">
                            <div class="col-md-6">
                                <div class="browser-card">
                                    <div class="browser-card__icon">
                                        <i class="fab fa-chrome"></i>
                                    </div>
                                    <h5 class="browser-card__title">Chrome</h5>
                                    <p class="browser-card__text">
                                        Settings → Privacy and security → Cookies and other site data
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="browser-card">
                                    <div class="browser-card__icon">
                                        <i class="fab fa-firefox"></i>
                                    </div>
                                    <h5 class="browser-card__title">Firefox</h5>
                                    <p class="browser-card__text">
                                        Options → Privacy & Security → Cookies and Site Data
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="browser-card">
                                    <div class="browser-card__icon">
                                        <i class="fab fa-safari"></i>
                                    </div>
                                    <h5 class="browser-card__title">Safari</h5>
                                    <p class="browser-card__text">
                                        Preferences → Privacy → Manage Website Data
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="browser-card">
                                    <div class="browser-card__icon">
                                        <i class="fab fa-edge"></i>
                                    </div>
                                    <h5 class="browser-card__title">Edge</h5>
                                    <p class="browser-card__text">
                                        Settings → Cookies and site permissions → Cookies and site data
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">Third-party cookies</h3>
                        <p class="about-one__content__text">
                            Some cookies on our website are set by third-party services. These may include:
                        </p>
                        <ul class="about-one__content__list">
                            <li><strong>Google Analytics:</strong> Helps us understand website usage and improve our content</li>
                            <li><strong>YouTube:</strong> Embedded videos from our YouTube channel</li>
                            <li><strong>Social Media:</strong> Social sharing buttons and embedded content</li>
                        </ul>
                        <p class="about-one__content__text">
                            These third parties have their own privacy policies, and we encourage you to review them.
                        </p>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">Data retention</h3>
                        <p class="about-one__content__text">
                            Cookie consent preferences are stored for 12 months. After this period,
                            you will be asked to provide your consent again. You can withdraw or modify
                            your consent at any time using the cookie settings.
                        </p>
                    </div>

                    <div class="about-one__content-block">
                        <h3 class="about-one__content__title">Contact us</h3>
                        <p class="about-one__content__text">
                            If you have any questions about our cookie policy or how we handle your data,
                            please contact us:
                        </p>
                        <div class="contact-info">
                            <div class="contact-info__item">
                                <span class="contact-info__icon"><i class="fas fa-envelope"></i></span>
                                <span class="contact-info__text">Email: info@citylifechurch.org.uk</span>
                            </div>
                            <div class="contact-info__item">
                                <span class="contact-info__icon"><i class="fas fa-phone"></i></span>
                                <span class="contact-info__text">Phone: +44 114 272 6908</span>
                            </div>
                            <div class="contact-info__item">
                                <span class="contact-info__icon"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="contact-info__text">Address: Kelham Island Museum, Sheffield S3 8RY</span>
                            </div>
                        </div>
                    </div>

                    <div class="policy-update-notice">
                        <div class="policy-update-notice__icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="policy-update-notice__content">
                            <h5 class="policy-update-notice__title">Policy Updates</h5>
                            <p class="policy-update-notice__text">
                                This cookie policy was last updated on {{ date('F j, Y') }}.
                                We may update this policy from time to time, and any changes will be posted on this page.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Cookie Policy Page Styles - CityLife Theme */
.about-one__content-block {
    margin-bottom: 40px;
}

.about-one__content__title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 20px;
    position: relative;
    padding-left: 30px;
}

.about-one__content__title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 3px;
    background: var(--citylife-base, #ff6b35);
}

.about-one__content__text {
    font-size: 16px;
    line-height: 1.8;
    color: #666;
    margin-bottom: 15px;
}

.about-one__content__list {
    margin: 20px 0;
    padding-left: 0;
    list-style: none;
}

.about-one__content__list li {
    position: relative;
    padding-left: 25px;
    margin-bottom: 10px;
    color: #666;
    line-height: 1.6;
}

.about-one__content__list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 10px;
    width: 8px;
    height: 8px;
    background: var(--citylife-base, #ff6b35);
    border-radius: 50%;
}

/* Cookie Category Items */
.cookie-category-item {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 30px;
    margin-bottom: 25px;
    border-left: 4px solid var(--citylife-base, #ff6b35);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.cookie-category-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.cookie-category-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.cookie-category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--citylife-base, #ff6b35), #ff8c69);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.cookie-category-content {
    flex: 1;
}

.cookie-category-title {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.cookie-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.cookie-badge--required {
    background: #dc3545;
    color: white;
}

.cookie-badge--optional {
    background: #6c757d;
    color: white;
}

.cookie-category-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.cookie-details-title {
    font-size: 14px;
    font-weight: 600;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.cookie-detail-item {
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.cookie-detail-item:last-child {
    border-bottom: none;
}

.cookie-name {
    color: #1a1a1a;
    font-weight: 600;
    min-width: 150px;
}

.cookie-description {
    color: #666;
    flex: 1;
}

/* Cookie Actions */
.cookie-actions {
    display: flex;
    gap: 20px;
    margin-top: 30px;
    flex-wrap: wrap;
}

/* Browser Instructions */
.browser-instructions {
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid #f5f5f5;
}

.browser-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.browser-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.browser-card__icon {
    font-size: 40px;
    margin-bottom: 15px;
}

.browser-card__icon .fa-chrome {
    color: #4285f4;
}

.browser-card__icon .fa-firefox {
    color: #ff9500;
}

.browser-card__icon .fa-safari {
    color: #1b88ca;
}

.browser-card__icon .fa-edge {
    color: #0078d4;
}

.browser-card__title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.browser-card__text {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

/* Contact Info */
.contact-info {
    margin-top: 20px;
}

.contact-info__item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f5f5f5;
}

.contact-info__item:last-child {
    border-bottom: none;
}

.contact-info__icon {
    width: 40px;
    height: 40px;
    background: var(--citylife-base, #ff6b35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.contact-info__text {
    color: #666;
    font-size: 16px;
}

/* Policy Update Notice */
.policy-update-notice {
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    border: 1px solid #2196f3;
    border-radius: 10px;
    padding: 25px;
    margin-top: 40px;
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.policy-update-notice__icon {
    width: 50px;
    height: 50px;
    background: #2196f3;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.policy-update-notice__title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.policy-update-notice__text {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 992px) {
    .cookie-category-header {
        flex-direction: column;
        text-align: center;
    }

    .cookie-actions {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .about-one__content__title {
        font-size: 20px;
        padding-left: 25px;
    }

    .cookie-category-item {
        padding: 20px;
    }

    .cookie-category-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    .cookie-actions {
        flex-direction: column;
        align-items: center;
    }

    .policy-update-notice {
        flex-direction: column;
        text-align: center;
    }

    .contact-info__item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .about-one__content__title {
        font-size: 18px;
        padding-left: 20px;
    }

    .cookie-category-title {
        font-size: 18px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .cookie-detail-item {
        flex-direction: column;
        gap: 5px;
    }

    .cookie-name {
        min-width: auto;
    }
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
</x-app-layout>
