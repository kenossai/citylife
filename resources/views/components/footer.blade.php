<footer class="main-footer background-white2">
    <div class="main-footer__top">
        <div class="container">
            <div class="row gutter-y-30">
                <div class="col-md-12 col-xl-4 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="00ms">
                    <div class="footer-widget footer-widget--about">
                        <a href="{{ route('home') }}" class="footer-widget__logo">
                            <img src="{{ asset('assets/images/logo.png') }}" width="100" alt="City Life Church">
                        </a>
                        <p class="footer-widget__about-text">Join us as we grow together in faith, love, and service to our community. Experience God's love and discover your purpose with us.</p>
                    </div><!-- /.footer-widget -->
                    <a href="https://maps.google.com" target="_blank" class="footer-widget__map">
                        <span class="footer-widget__map__text">View Map</span>
                        <span class="icon-paper-plane"></span>
                    </a>
                </div><!-- /.col-md-12 col-xl-3 -->
                <div class="col-xl-3 col-md-5 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                    <div class="footer-widget footer-widget--contact">
                        <h2 class="footer-widget__title">Get in touch!</h2><!-- /.footer-widget__title -->
                        <ul class="list-unstyled footer-widget__info">
                            <li> <span class="icon-location"></span>
                                <address>1 South Parade Sheffield S3 8SS</address>
                            </li>
                            <li> <span class="icon-phone"></span><a href="tel:(217)555-0123">(217) 555-0123</a></li>
                            <li> <span class="icon-envelope"></span><a href="mailto:info@citylifechurch.org">info@citylifechurch.org</a></li>
                        </ul><!-- /.list-unstyled -->
                    </div><!-- /.footer-widget -->
                </div><!-- /.col-xl-3 col-md-5 -->
                <div class="col-md-3 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="400ms">
                    <div class="footer-widget footer-widget--links">
                        <h2 class="footer-widget__title">Quick Links</h2><!-- /.footer-widget__title -->
                        <ul class="list-unstyled footer-widget__links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('media.index') }}">Media</a></li>
                            <li><a href="{{ route('teaching-series.index') }}">Teaching Series</a></li>
                            <li><a href="{{ route('events.index') }}">Events</a></li>
                            <li><a href="{{ route('baby-dedication.index') }}">Baby Dedication</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul><!-- /.list-unstyled footer-widget__links -->
                    </div><!-- /.footer-widget -->
                </div><!-- /.col-md-3 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
        <div class="main-footer__top__shape-box">
            <img src="{{ asset('assets/images/shapes/footer-shape-hand-1-1.png') }}" alt="hand" class="main-footer__top__shape-one">
            <img src="{{ asset('assets/images/shapes/footer-shape-hand-1-2.png') }}" alt="hand" class="main-footer__top__shape-two">
        </div><!-- /.main-footer__top__shape-box -->
    </div><!-- /.main-footer__top -->
    <div class="main-footer__bottom">
        <div class="main-footer__bottom__bg" style="background: url('{{ asset('assets/images/backgrounds/footer-bottom-bg-1-2.png') }}');"></div><!-- /.main-footer__bottom-bg -->
        <div class="container">
            <div class="main-footer__bottom__inner">
                <p class="main-footer__copyright">
                    &copy; Copyright <span class="dynamic-year"></span> City Life Church. All Rights Reserved.
                </p>
                <div class="main-footer__privacy-links">
                    <a href="/cookie-policy">Cookie Policy</a>
                    <span class="separator">|</span>
                    <button type="button" onclick="window.cookieConsent?.showPreferences()" class="cookie-preferences-link">
                        Cookie Preferences
                    </button>
                </div>
            </div><!-- /.main-footer__inner -->
        </div><!-- /.container -->
    </div><!-- /.main-footer__bottom -->
</footer><!-- /.main-footer -->

<style>
.main-footer__bottom__inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.main-footer__privacy-links {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
}

.main-footer__privacy-links a,
.cookie-preferences-link {
    color: #7f8c8d;
    text-decoration: none;
    transition: color 0.3s ease;
    background: none;
    border: none;
    font-size: inherit;
    cursor: pointer;
    padding: 0;
}

.main-footer__privacy-links a:hover,
.cookie-preferences-link:hover {
    color: var(--citylife-base, #ff6b35);
    text-decoration: underline;
}

.separator {
    color: #bdc3c7;
}

@media (max-width: 768px) {
    .main-footer__bottom__inner {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .main-footer__privacy-links {
        justify-content: center;
    }
}
</style>
