<x-app-layout>
    @section('title', 'Ministries - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Our Ministries</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
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
                                <img src="{{ asset('storage/' . $ministry->image) }}" alt="{{ $ministry->title }}">
                            @else
                                <img src="{{ asset('assets/images/events/event-2-1.jpg') }}" alt="{{ $ministry->name }}">
                            @endif
                            <div class="event-card-four__date">
                                <span>03</span>
                                <span>Sep</span>
                            </div><!-- /.event-card-four__date -->
                        </a><!-- /.event-card-four__image -->
                        <div class="event-card-four__content">
                            <div class="event-card-four__time">
                                <i class="event-card-four__time__icon fa fa-clock"></i>10:00 aM - 2.00 PM
                            </div><!-- /.event-card-four__time -->
                            <h4 class="event-card-four__title"><a href="event-details-right.html">{{ $ministry->name }}</a></h4><!-- /.event-card-four__title -->
                            <div class="event-card-four__text">{{ Str::limit($ministry->description, 100) }}</div><!-- /.event-card-four__text -->
                            <ul class="event-card-four__meta">
                                <li>
                                    <h5 class="event-card-four__meta__title">Leader</h5>
                                    {{ $ministry->leader ?? 'Not specified' }}
                                </li>
                                <li>
                                    <h5 class="event-card-four__meta__title"><span class="icon-location"></span> Meeting Venue</h5>
                                    {{ $ministry->venue ?? 'Not specified' }}
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
                <a href="{{ route('contact') }}" class="cleenhearts-btn">
                    <div class="cleenhearts-btn__icon-box">
                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                    </div>
                    <span class="cleenhearts-btn__text">Join Us</span>
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
