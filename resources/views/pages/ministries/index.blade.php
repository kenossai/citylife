<x-app-layout>
    @section('title', 'Ministries - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h4 class="text-white">Our Ministries</h4>
            <h2 class="page-header__title">Connect Through Ministry</h2>
            <p class="section-title__text text-white">
                Discover your calling and make a difference in our community and beyond.
                Join one of our vibrant ministries and grow in faith while serving others.
            </p>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Ministries</span></li>
            </ul>
        </div>
    </section>
<section class="events-list-page section-space">
    <div class="container">
        <div class="row gutter-y-30">
            @forelse ($ministries as $ministry)
                <div class="col-lg-12 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                    <div class="event-card-four">
                        <a href="event-details-right.html" class="event-card-four__image">
                            @if ($ministry->image)
                                <img src="{{ Storage::url('' . $ministry->image) }}" alt="{{ $ministry->title }}">
                            @else
                                <img src="{{ asset('assets/images/events/event-2-1.jpg') }}" alt="{{ $ministry->name }}">
                            @endif
                        </a><!-- /.event-card-four__image -->
                        <div class="event-card-four__content">
                            @if ($ministry->meeting_time)
                                <div class="event-card-four__time">
                                    <i class="event-card-four__time__icon fa fa-clock"></i>{{ $ministry->meeting_time }}
                                </div>
                            @endif
                            <h4 class="event-card-four__title"><a href="{{ route('ministries.show', $ministry->slug) }}">{{ $ministry->name }}</a></h4><!-- /.event-card-four__title -->
                            <div class="event-card-four__text">{{ Str::limit($ministry->description, 100) }}</div><!-- /.event-card-four__text -->
                            <ul class="event-card-four__meta">
                                <li>
                                    @if ($ministry->leader)
                                    <h5 class="event-card-four__meta__title">Led By</h5>
                                        {{ $ministry->leader }}
                                    @else
                                        Not specified
                                    @endif
                                </li>
                                <li>
                                    @if ($ministry->venue)
                                    <h5 class="event-card-four__meta__title"><span class="icon-location"></span> Meeting Venue</h5>
                                        {{ $ministry->venue }}
                                    @else
                                        Not specified
                                    @endif
                                </li>
                            </ul><!-- /.event-card-four__meta -->
                        </div><!-- /.event-card-four__content -->
                    </div><!-- /.event-card-four -->
                </div>
            @empty
               <div class="col-12">
                    <div class="text-center">
                        <h3>No Ministries Available</h3>
                        <p>Please check back later for ministry opportunities.</p>
                    </div>
                </div>
            @endforelse
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
    <!-- Call to Action -->
    <section class="cta-one cta-one--page">
        <div class="container mb-5">
            <div class="cta-one__inner text-center">
                <h3 class="cta-one__title">Ready to Get Involved?</h3>
                <p class="cta-one__text">Contact our ministry coordinator to find the perfect ministry for you.</p>
                <a href="{{ route('contact') }}" class="citylife-btn">
                    <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                    </div>
                    <span class="citylife-btn__text">Join Us</span>
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
