<x-app-layout>
@section('title', 'Missions at Home - CityLife Church')
@section('description', 'Discover our local mission work supporting families, children, and communities in need through food packages, school uniform drives, and foundation work.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Missions at Home</h2>
        <p class="page-header__text">Serving our local community with love and compassion</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('missions.index') }}">Missions</a></li>
            <li><span>Home</span></li>
        </ul>
    </div>
</section>

<!-- Biblical Foundation Section -->
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50 align-items-center">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Matthew 25:35-36</h6>
                        <h3 class="section-title__title">"...for I was hungry and you gave Me food..."</h3>
                        <p class="about-one__content__text">
                            "...for I was hungry and you gave Me food; I was thirsty and you gave Me drink; I was a stranger and you took Me in; I was naked and you clothed Me; I was sick and you visited Me;"
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/community-help.jpg') }}" alt="Helping Community" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section class="about-two section-space-two">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h3 class="section-title__title">At City Life, we believe we have a mandate from Jesus to help those in need.</h3>
                    <p class="section-title__text">
                        Our Home Missions are focused on providing assistance to people in our local community with food, clothing etc.
                        The Home Missions project is managed by Pastor Terence and Vivienne Williams.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Programs -->
<section class="donate-page section-space">
    <div class="container">
        <div class="row gutter-y-30">
            <!-- Food/Toiletries Packages -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center h-100">
                    <div class="donate-card__icon">
                        <span class="icon-donation"></span>
                    </div>
                    <h3 class="donate-card__title">Food/Toiletries Packages</h3>
                    <p class="donate-card__text">
                        We collect non-perishable food items and toiletries to distribute to people who need them.
                        If you wish to donate, please bring your items and place them in the designated baskets in the main hall.
                    </p>
                    <div class="donate-card__actions mt-auto">
                        <a href="{{ route('contact') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Get Involved</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- City Life Kids And Families Foundation -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center h-100">
                    <div class="donate-card__icon">
                        <span class="icon-heart"></span>
                    </div>
                    <h3 class="donate-card__title">City Life Kids And Families Foundation</h3>
                    <p class="donate-card__text">
                        The City Life Kids Foundation is set up to help disadvantaged children through initiatives such as
                        providing meals during school holidays and hosting pre-loved uniform events.
                    </p>
                    <div class="donate-card__actions mt-auto">
                        <a href="{{ route('contact') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Learn More</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pre-Loved School Uniform -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center h-100">
                    <div class="donate-card__icon">
                        <span class="icon-user"></span>
                    </div>
                    <h3 class="donate-card__title">Pre-Loved School Uniform</h3>
                    <p class="donate-card__text">
                        We collect second hand school uniform items to distribute at our "Pre-Loved School Uniform" events,
                        where families who are struggling with the cost of school uniform can come and get the items they need free of charge.
                    </p>
                    <div class="donate-card__actions mt-auto">
                        <a href="{{ route('events.index') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">View Events</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="contact-one__bottom-cta section-space-two" style="background-image: url('{{ asset('assets/images/backgrounds/help-donate-bg-1-1.jpg') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-one__bottom-cta__content text-center">
                    <h3 class="contact-one__bottom-cta__title">Ready to Make a Difference?</h3>
                    <p class="contact-one__bottom-cta__text">
                        Join us in serving our local community. Whether through donations, volunteering, or prayer support,
                        your contribution makes a real difference in people's lives.
                    </p>
                    <div class="contact-one__bottom-cta__btn">
                        <a href="{{ route('contact') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Contact Us</span>
                        </a>
                        <a href="{{ route('volunteer.index') }}" class="citylife-btn citylife-btn--border">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-user"></span></div>
                            </div>
                            <span class="citylife-btn__text">Volunteer</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
