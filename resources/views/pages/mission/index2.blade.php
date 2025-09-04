<x-app-layout>
@section('title', 'Missions - CityLife Church')
@section('description', 'Discover our mission work both at home and abroad, serving communities locally and internationally through various outreach programs.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Our Missions</h2>
        <p class="page-header__text">Serving God by serving others, both near and far</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Missions</span></li>
        </ul>
    </div>
</section>

<!-- Mission Vision Section -->
<section class="help-donate-one section-space-top">
    <div class="help-donate-one__bg citylife-jarallax" data-jarallax data-speed="0.3" data-imgPosition="50% -100%" style="background-image: url({{ asset('assets/images/backgrounds/Girls-Home.jpg') }});"></div>
    <div class="container">
        <div class="sec-title">
            <h6 class="sec-title__tagline sec-title__tagline--center">MISSION & VISION</h6>
        </div>
    </div>
</section>

<!-- Mission Types -->
<section class="donations-one donations-carousel section-space-bottom">
    <div class="container">
        <div class="donations-one__row row gutter-y-30">
            <div class="col-xl-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="00ms">
                <div class="donation-information">
                    <div class="donation-information__bg" style='background-image: url({{ asset('assets/images/resources/donation-information-bg-1-1.jpg') }})'></div>
                    <div class="donation-information__content">
                        <h3 class="donation-information__title">Missions at Home</h3>
                        <p class="donation-information__text">Supporting our local community through food packages, school uniform drives, and the City Life Kids & Families Foundation. We believe in caring for those closest to us first.</p>
                        <a href="{{ route('missions.home') }}" class="citylife-btn citylife-btn--border">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">learn more</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                <div class="gift-card">
                    <div class="gift-card__bg" style='background-image: url({{ asset('assets/images/resources/mission2.jpeg') }})'></div>
                    <div class="gift-card__content">
                        <h3 class="gift-card__title">Missions Abroad</h3>
                        <p class="gift-card__text">Partnering with projects in India and the Democratic Republic of Congo to transform communities, educate children, and provide hope for the future.</p>
                        <div class="donate-card__features">
                            <ul class="">
                                <li><i class="fa fa-check text-success"></i> <span>The John Project (India)</span></li>
                                <li><i class="fa fa-check text-success"></i> <span>Shalom Project (New Delhi)</span></li>
                                <li><i class="fa fa-check text-success"></i> <span>DRC Community Development</span></li>
                                <li><i class="fa fa-check text-success"></i> <span>Education & Healthcare</span></li>
                            </ul>
                        </div>
                        <a href="{{ route('missions.abroad') }}" class="citylife-btn citylife-btn--border">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">discover more</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($missions->count() > 0)
<!-- Featured Missions -->
<section class="blog-page section-space" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">Featured</h6>
                    <h3 class="section-title__title">Current Mission Projects</h3>
                </div>
            </div>
        </div>

        <div class="row gutter-y-30">
            @foreach($missions->take(3) as $mission)
            <div class="col-lg-4 col-md-6">
                <div class="blog-card-three">
                    @if($mission->featured_image)
                    <div class="blog-card-three__image">
                        <img src="{{ asset('storage/' . $mission->featured_image) }}" alt="{{ $mission->title }}">
                    </div>
                    @endif
                    <div class="blog-card-three__content">
                        <h3 class="blog-card-three__title">
                            <a href="{{ route('missions.show', $mission) }}">{{ $mission->title }}</a>
                        </h3>
                        <p class="blog-card-three__text">{{ Str::limit($mission->description, 120) }}</p>
                        <div class="blog-card-three__meta">
                            <span class="blog-card-three__meta__item">
                                <i class="fa fa-map-marker"></i> {{ $mission->location }}
                            </span>
                            <span class="blog-card-three__meta__item">
                                <i class="fa fa-tag"></i> {{ ucfirst($mission->mission_type) }}
                            </span>
                        </div>
                        <a href="{{ route('missions.show', $mission) }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Read More</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Get Involved -->
<section class="contact-one__bottom-cta section-space-two" style="background-image: url('{{ asset('assets/images/backgrounds/help-donate-bg-1-1.jpg') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-one__bottom-cta__content text-center">
                    <h3 class="contact-one__bottom-cta__title">Join Us in Making a Difference</h3>
                    <p class="contact-one__bottom-cta__text">
                        Whether through prayer, giving, or volunteering, there are many ways you can be part of our mission to serve others in Jesus' name.
                    </p>
                    <div class="contact-one__bottom-cta__btn">
                        <a href="{{ route('giving.index') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-donate"></span></div>
                            </div>
                            <span class="citylife-btn__text">Support Missions</span>
                        </a>
                        <a href="{{ route('contact') }}" class="citylife-btn citylife-btn--border">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Get Involved</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.mission-stats {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
}

.mission-stat {
    text-align: center;
    flex: 1;
}

.mission-stat__number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--citylife-base);
    line-height: 1.2;
}

.mission-stat__label {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .mission-stats {
        flex-direction: column;
        gap: 15px;
    }

    .mission-stat__number {
        font-size: 1.3rem;
    }
}
</style>

</x-app-layout>
