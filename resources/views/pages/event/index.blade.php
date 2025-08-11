<x-app-layout>
@section('title', 'Events')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Church Events</h3>
        <h2 class="page-header__title">Upcoming Events & Activities</h2>
        <p class="section-header__text">Join us for upcoming events, services, and activities designed to strengthen our faith community and grow in fellowship together.</p>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Events</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="events-page section-space">
    <div class="container">
        @if($events->count() > 0)
        <div class="row gutter-y-30">
            @foreach($events as $index => $event)
            <div class="col-lg-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="{{ ($index % 2) * 200 }}ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: {{ ($index % 2) * 200 }}ms; animation-name: fadeInUp;">
                <div class="event-card-grid {{ $index % 2 === 1 ? 'event-card-grid--reverse' : '' }}">
                    <a href="{{ route('events.show', $event->slug) }}" class="event-card-grid__image">
                        <img src="{{ $event->featured_image_url }}" alt="{{ $event->title }}">
                        <div class="event-card-grid__date-wrapper">
                            <div class="event-card-grid__time">
                                <span class="event-card-grid__time__icon fa fa-clock"></span>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}
                            </div><!-- /.event-card-grid__time -->
                            <div class="event-card-grid__date">{{ $event->start_date->format('d M') }}</div><!-- /.event-card-grid__date -->
                        </div>
                    </a><!-- /.event-card-grid__image -->
                    <div class="event-card-grid__content">
                        <h4 class="event-card-grid__title"><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h4><!-- /.event-card-grid__title -->
                        <div class="event-card-grid__description">{{ Str::limit($event->description, 120) }}</div>
                        <ul class="event-card-grid__meta">
                            @if($event->event_anchor)
                            <li>
                                <h5 class="event-card-grid__meta__title">Host</h5>
                                {{ $event->event_anchor }}
                            </li>
                            @endif
                            @if($event->guest_speaker)
                            <li>
                                <h5 class="event-card-grid__meta__title">Guest Speaker</h5>
                                {{ $event->guest_speaker }}
                            </li>
                            @endif
                            <li>
                                <h5 class="event-card-grid__meta__title"><span class="icon-location"></span> Venue</h5>
                                {{ $event->location }}
                            </li>
                            @if($event->requires_registration)
                            <li>
                                <h5 class="event-card-grid__meta__title"><span class="icon-check"></span> Registration</h5>
                                Required
                                @if($event->max_attendees)
                                    (Max: {{ $event->max_attendees }})
                                @endif
                            </li>
                            @endif
                        </ul><!-- /.event-card-grid__meta -->
                    </div><!-- /.event-card-grid__content -->
                </div><!-- /.event-card-grid -->
            </div><!-- /.col-lg-6 -->
            @endforeach
        </div><!-- /.row -->

        <!-- Pagination -->
        @if($events->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="pagination-wrapper text-center mt-5">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
        @endif

        @else
        <div class="row">
            <div class="col-12 text-center">
                <h3>No Upcoming Events</h3>
                <p>There are no upcoming events scheduled at this time. Please check back later for updates.</p>
            </div>
        </div>
        @endif
    </div><!-- /.container -->
</section>
</x-app-layout>
