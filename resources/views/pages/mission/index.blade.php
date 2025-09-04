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

<!-- Mission Overview -->
<section class="about-one section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">Our Mission Heart</h6>
                    <h3 class="section-title__title">Called to Serve, Commissioned to Go</h3>
                    <p class="section-title__text">
                        At CityLife Church, we believe that serving others is at the heart of the Gospel.
                        From our local community to the far reaches of the world, we are committed to demonstrating God's love through practical action.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Types -->
<section class="donate-page section-space" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row gutter-y-30">
            <!-- Missions at Home -->
            <div class="col-lg-6">
                <div class="donate-card text-center h-100">
                    <div class="donate-card__image mb-4">
                        <img src="{{ asset('assets/images/missions/home-missions.jpg') }}" alt="Missions at Home" class="img-fluid rounded">
                    </div>
                    <div class="donate-card__icon">
                        <span class="icon-heart"></span>
                    </div>
                    <h3 class="donate-card__title">Missions at Home</h3>
                    <p class="donate-card__text">
                        Supporting our local community through food packages, school uniform drives, and the City Life Kids & Families Foundation.
                        We believe in caring for those closest to us first.
                    </p>
                    <div class="donate-card__features">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check text-success"></i> Food & Toiletries Packages</li>
                            <li><i class="fa fa-check text-success"></i> Pre-Loved School Uniform Events</li>
                            <li><i class="fa fa-check text-success"></i> Kids & Families Foundation</li>
                            <li><i class="fa fa-check text-success"></i> Community Support Programs</li>
                        </ul>
                    </div>
                    <div class="donate-card__actions mt-auto">
                        <a href="{{ route('missions.home') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Learn More</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Missions Abroad -->
            <div class="col-lg-6">
                <div class="donate-card text-center h-100">
                    <div class="donate-card__image mb-4">
                        <img src="{{ asset('assets/images/missions/abroad-missions.jpg') }}" alt="Missions Abroad" class="img-fluid rounded">
                    </div>
                    <div class="donate-card__icon">
                        <span class="icon-donation"></span>
                    </div>
                    <h3 class="donate-card__title">Missions Abroad</h3>
                    <p class="donate-card__text">
                        Partnering with projects in India and the Democratic Republic of Congo to transform communities,
                        educate children, and provide hope for the future.
                    </p>
                    <div class="donate-card__features">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check text-success"></i> The John Project (India)</li>
                            <li><i class="fa fa-check text-success"></i> Shalom Project (New Delhi)</li>
                            <li><i class="fa fa-check text-success"></i> DRC Community Development</li>
                            <li><i class="fa fa-check text-success"></i> Education & Healthcare</li>
                        </ul>
                    </div>
                    <div class="donate-card__actions mt-auto">
                        <a href="{{ route('missions.abroad') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Discover More</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Biblical Foundation -->
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50 align-items-center">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Our Foundation</h6>
                        <h3 class="section-title__title">Rooted in Scripture</h3>
                        <div class="about-one__content__quote">
                            <blockquote>
                                <p>"Go therefore and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit."</p>
                                <footer>Matthew 28:19</footer>
                            </blockquote>
                        </div>
                        <div class="about-one__content__quote mt-4">
                            <blockquote>
                                <p>"Religion that is pure and undefiled before God the Father is this: to visit orphans and widows in their affliction."</p>
                                <footer>James 1:27</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/bible-missions.jpg') }}" alt="Biblical Foundation" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

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

</x-app-layout>
