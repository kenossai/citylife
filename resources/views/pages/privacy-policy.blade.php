<x-app-layout>
    @section('title', 'Privacy Policy - City Life Church')
    @section('description', 'City Life Church Privacy Policy and Data Protection Information')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Privacy Policy</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Privacy Policy</span></li>
            </ul>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="about-page section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="about-page__content">
                        <h3 class="about-page__title">Your Privacy Matters</h3>
                        <p class="about-page__text">
                            City Life Church is committed to protecting your personal information and respecting your privacy rights. 
                            This policy explains how we collect, use, and protect your data.
                        </p>

                        <div class="mb-5">
                            <h4 class="text-primary mb-3">Data We Collect</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Essential Information</h5>
                                    <ul>
                                        <li>Name and email address</li>
                                        <li>Contact information (if provided)</li>
                                        <li>Course enrollment data</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Optional Information</h5>
                                    <ul>
                                        <li>Phone number</li>
                                        <li>Church membership status</li>
                                        <li>Emergency contact details</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h4 class="text-primary mb-3">How We Use Your Data</h4>
                            <div class="alert alert-info">
                                <i class="icon-shield"></i>
                                <strong>We follow data minimization principles</strong> - we only collect what's necessary.
                            </div>
                            <ul>
                                <li><strong>Course Administration:</strong> Managing enrollments, sending updates, issuing certificates</li>
                                <li><strong>Safety:</strong> Emergency contact information for course activities</li>
                                <li><strong>Communication:</strong> Church announcements and event updates (with consent)</li>
                                <li><strong>Legal Compliance:</strong> Meeting regulatory requirements</li>
                            </ul>
                        </div>

                        <div class="mb-5">
                            <h4 class="text-primary mb-3">Your Rights</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="privacy-right mb-3">
                                        <h5><i class="icon-eye"></i> Access</h5>
                                        <p>Request to see what data we hold about you</p>
                                    </div>
                                    <div class="privacy-right mb-3">
                                        <h5><i class="icon-edit"></i> Correction</h5>
                                        <p>Update or correct your personal information</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="privacy-right mb-3">
                                        <h5><i class="icon-trash"></i> Deletion</h5>
                                        <p>Request removal of your data</p>
                                    </div>
                                    <div class="privacy-right mb-3">
                                        <h5><i class="icon-download"></i> Portability</h5>
                                        <p>Request a copy of your data</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h4 class="text-primary mb-3">Data Retention</h4>
                            <div class="alert alert-warning">
                                <strong>Automatic Deletion:</strong> Course enrollment data is automatically deleted 2 years after course completion.
                            </div>
                            <ul>
                                <li><strong>Member accounts:</strong> Retained while account is active + 1 year after last login</li>
                                <li><strong>Course data:</strong> 2 years after course completion</li>
                                <li><strong>Contact inquiries:</strong> 1 year unless ongoing correspondence</li>
                            </ul>
                        </div>

                        <div class="mb-5">
                            <h4 class="text-primary mb-3">Data Security</h4>
                            <ul>
                                <li>Encrypted data transmission and storage</li>
                                <li>Limited access to authorized personnel only</li>
                                <li>Regular security audits and updates</li>
                                <li>Secure hosting with reputable providers</li>
                            </ul>
                        </div>

                        <div class="contact-section p-4 bg-light rounded">
                            <h4 class="text-primary mb-3">Exercise Your Rights</h4>
                            <p>To request access, correction, or deletion of your data, or if you have any privacy concerns:</p>
                            <div class="contact-options">
                                <p><i class="icon-mail"></i> <strong>Email:</strong> <a href="mailto:privacy@citylifecc.com">privacy@citylifecc.com</a></p>
                                <p><i class="icon-phone"></i> <strong>Phone:</strong> <a href="tel:01142728243">0114 272 8243</a></p>
                                <p><i class="icon-location"></i> <strong>Address:</strong> City Life Church, Kelham Island, Sheffield</p>
                            </div>
                            <small class="text-muted">
                                We will respond to your request within 30 days as required by GDPR.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar__single sidebar__about wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                            <h4 class="sidebar__title">Quick Links</h4>
                            <ul class="sidebar__links">
                                <li><a href="{{ route('cookie-policy') }}">Cookie Policy</a></li>
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="mailto:privacy@citylifecc.com">Data Protection Officer</a></li>
                            </ul>
                        </div>

                        <div class="sidebar__single sidebar__about wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                            <h4 class="sidebar__title">Data Protection Summary</h4>
                            <div class="privacy-summary">
                                <div class="privacy-item mb-3">
                                    <h6><i class="icon-check text-success"></i> Minimal Collection</h6>
                                    <small>Only essential data collected</small>
                                </div>
                                <div class="privacy-item mb-3">
                                    <h6><i class="icon-check text-success"></i> Transparent Use</h6>
                                    <small>Clear purpose for all data</small>
                                </div>
                                <div class="privacy-item mb-3">
                                    <h6><i class="icon-check text-success"></i> Secure Storage</h6>
                                    <small>Encrypted and protected</small>
                                </div>
                                <div class="privacy-item">
                                    <h6><i class="icon-check text-success"></i> Your Control</h6>
                                    <small>Easy access and deletion</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .privacy-right h5 {
            color: #351c42;
            margin-bottom: 8px;
        }
        .privacy-right i {
            margin-right: 8px;
            color: #f6d469;
        }
        .contact-options p {
            margin-bottom: 10px;
        }
        .contact-options i {
            margin-right: 10px;
            color: #351c42;
            width: 20px;
        }
        .privacy-item h6 i {
            margin-right: 8px;
        }
        .privacy-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</x-app-layout>