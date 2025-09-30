<x-app-layout>
    <x-slot name="title">Youth Camping Registration</x-slot>

<!-- Page Header -->
<section class="page-header @@extraClassNam                                <a href="{{ route('youth-camping.show', $currentCamping) }}" class="citylife-btn citylife-btn--border-base">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner">
                                            <span class="icon-eye"></span>
                                        </div>
                                    </div>
                                    <span class="citylife-btn__text">View Details</span>
                                </a><div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">Youth Camping Registration</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Youth Camping</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section><!-- /.page-header -->

<!-- Youth Camping Section -->
<section class="events-list-page section-space">
    <div class="container">
        @if($currentCamping)
            <!-- Current/Upcoming Camping Carousel -->
            <div class="events-list-page__carousel citylife-owl__carousel citylife-owl__carousel--basic-nav owl-theme owl-carousel" data-owl-options='{
                "items": 1,
                "margin": 30,
                "smartSpeed": 700,
                "loop": false,
                "autoplay": false,
                "nav": true,
                "dots": true,
                "navText": ["<span class=\"icon-arrow-left\"></span>","<span class=\"icon-arrow-right\"></span>"],
                "responsive":{
                    "0":{
                        "items": 1,
                        "margin": 20
                    },
                    "575":{
                        "items": 1,
                        "margin": 20
                    },
                    "768":{
                        "items": 1,
                        "margin": 20
                    },
                    "992":{
                        "items": 1,
                        "margin": 20
                    },
                    "1200":{
                        "items": 1,
                        "margin": 20
                    }
                }
            }'>
                <div class="item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="00ms">
                    <div class="event-card-four">
                        <a href="{{ route('youth-camping.show', $currentCamping) }}" class="event-card-four__image">
                            @if($currentCamping->featured_image)
                                <img src="{{ asset('storage/' . $currentCamping->featured_image) }}" alt="{{ $currentCamping->name }}">
                            @else
                                <img src="{{ asset('assets/images/events/youth-camping-default.jpg') }}" alt="{{ $currentCamping->name }}">
                            @endif
                            <div class="event-card-four__date">
                                <span>{{ $currentCamping->start_date->format('d') }}</span>
                                <span>{{ $currentCamping->start_date->format('M') }}</span>
                            </div><!-- /.event-card-four__date -->
                            @if($currentCamping->is_registration_available)
                                <div class="event-card-four__status event-card-four__status--open">
                                    <span>Registration Open</span>
                                </div>
                            @else
                                <div class="event-card-four__status event-card-four__status--closed">
                                    <span>{{ $currentCamping->registration_status_message }}</span>
                                </div>
                            @endif
                        </a><!-- /.event-card-four__image -->
                        <div class="event-card-four__content">
                            <div class="event-card-four__time">
                                <i class="event-card-four__time__icon fa fa-calendar"></i>
                                {{ $currentCamping->start_date->format('M j') }} - {{ $currentCamping->end_date->format('M j, Y') }}
                            </div><!-- /.event-card-four__time -->
                            <h4 class="event-card-four__title">
                                <a href="{{ route('youth-camping.show', $currentCamping) }}">{{ $currentCamping->name }}</a>
                            </h4><!-- /.event-card-four__title -->
                            <div class="event-card-four__text">{{ Str::limit($currentCamping->description, 150) }}</div><!-- /.event-card-four__text -->

                            <!-- Camp Details -->
                            <ul class="event-card-four__meta">
                                <li>
                                    <h5 class="event-card-four__meta__title">Cost</h5>
                                    ${{ number_format($currentCamping->cost, 2) }}
                                </li>
                                <li>
                                    <h5 class="event-card-four__meta__title"><span class="icon-location"></span> Location</h5>
                                    {{ $currentCamping->location }}
                                </li>
                                <li>
                                    <h5 class="event-card-four__meta__title">Available Spots</h5>
                                    <span class="{{ $currentCamping->available_spots <= 5 ? 'text-danger' : 'text-success' }}">
                                        {{ $currentCamping->available_spots }} spots left
                                    </span>
                                </li>
                            </ul><!-- /.event-card-four__meta -->

                            <!-- Registration Status Alert -->
                            @if($currentCamping->is_registration_available)
                                <div class="alert alert-success mb-3">
                                    <i class="fa fa-check-circle"></i>
                                    <strong>Registration is Open!</strong>
                                    Register until {{ $currentCamping->registration_closes_at->format('F j, Y') }}
                                </div>
                            @else
                                <div class="alert alert-warning mb-3">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <strong>{{ $currentCamping->registration_status_message }}</strong>
                                    @if($currentCamping->registration_opens_at > now())
                                        <br>Opens on {{ $currentCamping->registration_opens_at->format('F j, Y') }}
                                    @endif
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="event-card-four__actions">
                                @if($currentCamping->is_registration_available)
                                    <a href="{{ route('youth-camping.register', $currentCamping) }}" class="citylife-btn">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner">
                                                <span class="icon-plus"></span>
                                            </div>
                                        </div>
                                        <span class="citylife-btn__text">Register Your Child</span>
                                    </a>
                                @else
                                    <button disabled class="citylife-btn citylife-btn--disabled">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner">
                                                <span class="icon-lock"></span>
                                            </div>
                                        </div>
                                        <span class="citylife-btn__text">Registration Closed</span>
                                    </button>
                                @endif
                                <a href="{{ route('youth-camping.show', $currentCamping) }}" class="citylife-btn citylife-btn--border-base">
                                    <div class="cleenhearts-btn__icon-box">
                                        <div class="cleenhearts-btn__icon-box__inner">
                                            <span class="icon-eye"></span>
                                        </div>
                                    </div>
                                    <span class="cleenhearts-btn__text">View Details</span>
                                </a>
                            </div><!-- /.event-card-four__actions -->
                        </div><!-- /.event-card-four__content -->
                    </div><!-- /.event-card-four -->
                </div><!-- /.item -->
            </div><!-- /.events-list-page__carousel -->
        @else
            <!-- No Current Camping -->
            <div class="row">
                <div class="col-12">
                    <div class="event-card-four event-card-four--no-events">
                        <div class="event-card-four__content text-center py-5">
                            <div class="event-card-four__icon mb-4">
                                <i class="icon-camping" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                            <h4 class="event-card-four__title mb-3">No Upcoming Camping Events</h4>
                            <div class="event-card-four__text">
                                There are currently no youth camping events scheduled. Check back soon for upcoming adventures!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Past Campings Section -->
        @if($pastCampings->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="section-title text-center">
                        <h2 class="section-title__title">Previous Camping Events</h2>
                        <p class="section-title__text">Take a look at our recent camping adventures</p>
                    </div>
                </div>
            </div>

            <div class="row gutter-y-30 mt-4">
                @foreach($pastCampings as $camping)
                    <div class="col-md-6 col-lg-4">
                        <div class="event-card-four event-card-four--past">
                            <a href="{{ route('youth-camping.show', $camping) }}" class="event-card-four__image">
                                @if($camping->featured_image)
                                    <img src="{{ asset('storage/' . $camping->featured_image) }}" alt="{{ $camping->name }}">
                                @else
                                    <img src="{{ asset('assets/images/events/youth-camping-default.jpg') }}" alt="{{ $camping->name }}">
                                @endif
                                <div class="event-card-four__date">
                                    <span>{{ $camping->start_date->format('d') }}</span>
                                    <span>{{ $camping->start_date->format('M') }}</span>
                                </div>
                                <div class="event-card-four__status event-card-four__status--past">
                                    <span>Completed</span>
                                </div>
                            </a>
                            <div class="event-card-four__content">
                                <div class="event-card-four__time">
                                    <i class="event-card-four__time__icon fa fa-calendar"></i>
                                    {{ $camping->start_date->format('M j') }} - {{ $camping->end_date->format('M j, Y') }}
                                </div>
                                <h4 class="event-card-four__title">
                                    <a href="{{ route('youth-camping.show', $camping) }}">{{ $camping->name }}</a>
                                </h4>
                                <div class="event-card-four__text">{{ Str::limit($camping->description, 100) }}</div>
                                <ul class="event-card-four__meta">
                                    <li>
                                        <h5 class="event-card-four__meta__title"><span class="icon-location"></span> Location</h5>
                                        {{ $camping->location }}
                                    </li>
                                    <li>
                                        <h5 class="event-card-four__meta__title">Year</h5>
                                        {{ $camping->year }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div><!-- /.container -->
</section><!-- /.events-list-page section-space -->

<!-- Information Section -->
<section class="about-one about-one--page section-space" id="about">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="sec-title">
                        <span class="sec-title__tagline">Youth Ministry</span><!-- /.sec-title__tagline -->
                        <h2 class="sec-title__title">Important Information</h2><!-- /.sec-title__title -->
                    </div><!-- /.sec-title -->
                    <p class="about-one__content__text">Our youth camping program provides a safe and fun environment for children to grow in faith, build friendships, and create lasting memories.</p>

                    <ul class="about-one__content__list">
                        <li class="about-one__content__list__item">
                            <span class="icon-check"></span>
                            <strong>Age Requirements:</strong> Children ages 5-18 are welcome
                        </li>
                        <li class="about-one__content__list__item">
                            <span class="icon-check"></span>
                            <strong>What's Included:</strong> Accommodation, meals, activities, and supervision
                        </li>
                        <li class="about-one__content__list__item">
                            <span class="icon-check"></span>
                            <strong>Medical Forms:</strong> Medical information and consent forms required
                        </li>
                        <li class="about-one__content__list__item">
                            <span class="icon-check"></span>
                            <strong>Safety First:</strong> Trained staff and emergency protocols
                        </li>
                    </ul><!-- /.about-one__content__list -->
                </div><!-- /.about-one__content -->
            </div><!-- /.col-lg-6 -->
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/about/youth-camping-info.jpg') }}" alt="Youth Camping Information" class="about-one__image__one">
                    <div class="about-one__image__caption">
                        <h3 class="about-one__image__caption__title">Creating Memories</h3>
                        <p class="about-one__image__caption__text">Building faith and friendships</p>
                    </div><!-- /.about-one__image__caption -->
                </div><!-- /.about-one__image -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.about-one section-space -->

<!-- Contact Section -->
<section class="contact-one contact-one--page section-space-bottom">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-xl-5 col-lg-6">
                <div class="contact-one__content">
                    <div class="sec-title">
                        <span class="sec-title__tagline">Get in Touch</span><!-- /.sec-title__tagline -->
                        <h2 class="sec-title__title">Questions About Youth Camping?</h2><!-- /.sec-title__title -->
                    </div><!-- /.sec-title -->
                    <p class="contact-one__content__text">Our youth ministry team is here to help with any questions about registration, activities, or medical requirements.</p>

                    <ul class="contact-one__info">
                        <li class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-email"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <span class="contact-one__info__name">Email Address</span>
                                <a href="mailto:youth@citylifechurch.com">youth@citylifechurch.com</a>
                            </div><!-- /.contact-one__info__content -->
                        </li>
                        <li class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-phone"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <span class="contact-one__info__name">Phone Number</span>
                                <a href="tel:(555)123-4567">(555) 123-4567</a>
                            </div><!-- /.contact-one__info__content -->
                        </li>
                        <li class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-location"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <span class="contact-one__info__name">Office Location</span>
                                <p>City Life Church<br>123 Church Street<br>Your City, State 12345</p>
                            </div><!-- /.contact-one__info__content -->
                        </li>
                    </ul><!-- /.contact-one__info -->
                </div><!-- /.contact-one__content -->
            </div><!-- /.col-xl-5 col-lg-6 -->
            <div class="col-xl-7 col-lg-6">
                <div class="contact-one__image">
                    <img src="{{ asset('assets/images/contact/contact-1-1.jpg') }}" alt="Contact Youth Ministry" />
                </div><!-- /.contact-one__image -->
            </div><!-- /.col-xl-7 col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.contact-one section-space-bottom -->

<!-- Custom Styles for Youth Camping -->
<style>
.event-card-four__status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

.event-card-four__status--open {
    background-color: #28a745;
    color: white;
}

.event-card-four__status--closed {
    background-color: #ffc107;
    color: #856404;
}

.event-card-four__status--past {
    background-color: #6c757d;
    color: white;
}

.event-card-four__actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.event-card-four__actions .citylife-btn {
    flex: 1;
    min-width: 150px;
}

.event-card-four--no-events {
    border: 2px dashed #ddd;
    background-color: #f8f9fa;
}

.event-card-four--past {
    opacity: 0.8;
}

.event-card-four--past:hover {
    opacity: 1;
}

.alert {
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.text-danger {
    color: #dc3545 !important;
}

.text-success {
    color: #28a745 !important;
}
</style>

</x-app-layout>

