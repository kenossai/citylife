<x-app-layout>
@section('title', 'Bible School International')
@section('description', 'Deepen your faith through our comprehensive Bible School International program.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">Our Bible School</h2>
        <h2 class="page-header__title">Bible School International</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Bible School</span></li>
        </ul>
    </div>
</section>

<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-6">
                <div class="about-one__image wow fadeInLeft" data-wow-duration="1500ms">
                    <div class="about-one__image__inner">
                        <img src="{{ asset('assets/images/resources/about-1-1.jpg') }}" alt="Bible School International" class="about-one__image__one">
                        <img src="{{ asset('assets/images/resources/about-1-2.jpg') }}" alt="Bible School" class="about-one__image__two">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-header">
                        <div class="section-header__top">
                            <span class="section-header__top__text">About Our Bible School</span>
                        </div>
                        <h3 class="section-header__title">Empowering Lives Through Biblical Teaching</h3>
                    </div>
                    <p class="about-one__text">
                        Welcome to Bible School International, a comprehensive program designed to deepen your understanding
                        of God's Word and equip you for effective ministry. Our Bible School features internationally
                        renowned speakers who bring years of experience and biblical insight.
                    </p>
                    <p class="about-one__text">
                        Through our structured curriculum, you'll engage with powerful teaching sessions covering essential
                        biblical topics, theology, and practical ministry applications. Each session is carefully designed
                        to build your faith and transform your life.
                    </p>

                    <div class="about-one__info mt-4">
                        <h5 class="mb-3">What You'll Experience:</h5>
                        <ul class="list-unstyled about-one__list">
                            <li>
                                <span class="icon-check-mark"></span>
                                In-depth biblical teaching from experienced ministers
                            </li>
                            <li>
                                <span class="icon-check-mark"></span>
                                Comprehensive video and audio resources
                            </li>
                            <li>
                                <span class="icon-check-mark"></span>
                                Organized sessions by year and speaker
                            </li>
                            <li>
                                <span class="icon-check-mark"></span>
                                Access to archived teachings from previous years
                            </li>
                            <li>
                                <span class="icon-check-mark"></span>
                                Life-changing insights and practical applications
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('bible-school-international.resources') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-play"></span></div>
                            </div>
                            <span class="citylife-btn__text">Browse Resources</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-one cta-one--home" style="background-image: url('{{ asset('assets/images/backgrounds/cta-bg-1-1.jpg') }}');">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="cta-one__content">
                    <h3 class="cta-one__title">Ready to Deepen Your Faith?</h3>
                    <p class="cta-one__text">Access our comprehensive library of teaching resources and begin your journey of spiritual growth today.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="cta-one__button">
                    <a href="{{ route('bible-school-international.resources') }}" class="citylife-btn citylife-btn--white">
                        <div class="citylife-btn__icon-box">
                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                        </div>
                        <span class="citylife-btn__text">View All Resources</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
