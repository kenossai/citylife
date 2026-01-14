<x-app-layout>

@section('title', 'Our Ministry - CityLife Church')
@section('meta_description', 'Welcome to CityLife Church. Learn about our ministries, vision, and how you can get involved in our community.')

@section('content')
<!-- Page Header Start -->
<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/backgrounds/worship-banner-1.jpg') }});"></div>
    <div class="container">
        <div class="page-header__inner text-center">
            <h2 class="page-header__title">Welcome to CityLife Church</h2>
            <ul class="breadcrumb justify-content-center">
                <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
            </ul>
        </div>
    </div>
</section>
<!-- Page Header End -->

<!-- Welcome Section Start -->
<section class="about-page section-space">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="about-page__content">
                    <div class="section-header">
                        <h2 class="section-header__title">Welcome to CityLife Church</h2>
                    </div>

                    <div class="about-page__text-box mt-4">
                        <p class="about-page__text">
                            If this is your first time here, we say a very big "Thank You" to you for taking a step and making the decision to come worship with us. Our prayer is that you will be richly blessed!
                        </p>

                        <p class="about-page__text">
                            <em>"God setteth the solitary in families: He bringeth out those which are bound with chains..." - Psalm 68:6</em>
                        </p>

                        <p class="about-page__text">
                            We are built on a simple system. In our weekly services we teach the word to understand God's will for us and during our prayer meetings we release our faith for the fulfilment of all He has shown us. We know He is faithful and so He works out in us during prayer what He has shown us in His word during our services.
                        </p>
                    </div>
                </div>
                <div class="about-page__content">
                    <div class="section-header">
                        <h2 class="section-header__title mt-5">Our Ministry</h2>
                    </div>

                    <div class="about-page__text-box mt-4">
                        <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Prayer Ministry</a>
                    </h6>
                    <p class="services-one__item__text">
                        Our Prayer Ministry is the spiritual backbone of our church, committed to interceding for our congregation, community, and world.
                    </p>
                    <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Outreach Programs</a>
                    </h6>
                    <p class="services-one__item__text">
                        We provide support to our community through various initiatives including food distribution, educational support, and family assistance programs.
                    </p>
                    <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Youth & Children</a>
                    </h6>
                    <p class="services-one__item__text">
                        Building the next generation through engaging programs, biblical teaching, and mentorship opportunities for young people.
                    </p>
                    <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Worship & Arts</a>
                    </h6>
                    <p class="services-one__item__text">
                        Experience powerful worship and creative expression through music, dance, drama, and other artistic ministries.
                    </p>
                    <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Teaching & Discipleship</a>
                    </h6>
                    <p class="services-one__item__text">
                        Providing biblical teaching and discipleship programs to help believers grow in their faith and live out their calling.
                    </p>
                    <h6 class="services-one__item__title">
                        <a href="{{ route('ministries.index') }}">Community Service</a>
                    </h6>
                    <p class="services-one__item__text">
                        Engaging in various community service projects to support and uplift those in need.
                    </p>
                    <h6 class="about-page__content__title">Service Times</h6>
                    <ul class="list-unstyled about-page__list">
                        <li><i class="fas fa-check-circle"></i> Sunday Service: 10:00 AM</li>
                        {{-- <li><i class="fas fa-check-circle"></i> Tuesday Prayer Meeting: 7:00 PM</li> --}}
                        <li><i class="fas fa-check-circle"></i> Thursday Prayer Meeting: 7:30 PM</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Welcome Section End -->

<!-- Get Involved Section Start -->
<section class="contact-one p-5">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-header__title">Get Involved</h2>
            <p class="section-header__text">At CityLife Church, we never walk alone. Here's how you can connect with us</p>
        </div>

        <div class="row gutter-y-30 mt-5">
            <!-- New to Church -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-one__item text-center">
                    <div class="contact-one__item__icon">
                        {{-- <span class="icon-user"></span> --}}
                    </div>
                    <h3 class="contact-one__item__title">New to Church?</h3>
                    <p class="contact-one__item__text">
                        We'd love to get to know you! Fill out our welcome form to help us connect with you better.
                    </p>
                    <div class="contact-one__item__btn">
                        <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-registration-modal'))"
                                class="citylife-btn citylife-btn--base">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Join with Us</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-one__item text-center">
                    <div class="contact-one__item__icon">
                        {{-- <span class="icon-calendar"></span> --}}
                    </div>
                    <h3 class="contact-one__item__title">Upcoming Events</h3>
                    <p class="contact-one__item__text">
                        Join us for exciting events, services, and activities designed to strengthen our community.
                    </p>
                    <div class="contact-one__item__btn">
                        <a href="{{ route('events.index') }}" class="citylife-btn citylife-btn--base">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">View Events</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-one__item text-center">
                    <div class="contact-one__item__icon">
                        {{-- <span class="icon-video-camera"></span> --}}
                    </div>
                    <h3 class="contact-one__item__title">Messages & Media</h3>
                    <p class="contact-one__item__text">
                        Watch or download our latest sermons and teaching series to grow in your faith.
                    </p>
                    <div class="contact-one__item__btn">
                        <a href="{{ route('media.index') }}" class="citylife-btn citylife-btn--base">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Watch Now</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Get Involved Section End -->

<!-- Connect Section Start -->
<section class="cta-one section-space" style="background-color: #ff6b35;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="cta-one__inner text-center">
                    <h2 class="cta-one__title text-white">Connect With Us</h2>
                    <p class="cta-one__text text-white mb-4">
                        Stay connected through our social media channels and email for updates, encouragement, and community
                    </p>

                    <div class="footer-widget__social">
                        <a href="https://facebook.com/citylifechurch" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/citylifechurch" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://instagram.com/citylifechurch" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://youtube.com/citylifechurch" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="citylife-btn citylife-btn--white">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Contact Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Connect Section End -->

<!-- Information Section Start -->

<!-- Information Section End -->

</x-app-layout>
