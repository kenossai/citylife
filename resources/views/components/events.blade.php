<section class="events-one section-space">
    <div class="container">
        <div class="sec-title">
            <h6 class="sec-title__tagline sec-title__tagline--center">Church Events</h6><!-- /.sec-title__tagline -->
            <h3 class="sec-title__title">See Upcoming <span class="sec-title__title__inner">Events</span></h3><!-- /.sec-title__title -->
        </div><!-- /.sec-title -->

        @if($events->count() > 0)
        <div class="horizontal-accordion">
            @foreach($events as $index => $event)
            <div class="events-one__card card choice {{ $index === 1 ? 'expand' : '' }}">
                <div class="card-body">
                    <div class="events-one__card__top" style="background-image: url('{{ $event->featured_image_url }}'); background-size: cover; background-position: center;">
                        <h4 class="events-one__card__title">{{ $event->title }}</h4>
                        <span class="events-one__card__icon icon-plus"></span><!-- /.accordion-title__icon -->
                    </div><!-- /.accordian-title -->
                    <div class="event-card-two">
                        <a href="#" class="event-card-two__image">
                            <img src="{{ $event->featured_image_url }}" alt="{{ $event->title }}">
                            <div class="event-card-two__time">
                                <span class="event-card-two__time__icon fa fa-clock"></span>{{ $event->formatted_start_date }}
                            </div><!-- /.event-card-four__time -->
                        </a><!-- /.event-card-four__image -->
                        <div class="event-card-two__content">
                            <h4 class="event-card-two__title"><a href="#">{{ $event->title }}</a></h4><!-- /.event-card-four__title -->
                            <div class="event-card-two__text">{{ Str::limit($event->description, 100) }}</div><!-- /.event-card-two__text -->
                            <div class="event-card-two__meta">
                                <h5 class="event-card-two__meta__title">Venue</h5>
                                {{ $event->location }}
                            </div><!-- /.event-card-four__meta -->
                        </div><!-- /.event-card-four__content -->
                    </div><!-- /.event-card-two -->
                </div>
            </div>
            @endforeach
        </div>

        @else
        <div class="text-center">
            <p>No upcoming events at this time. Please check back later for updates.</p>
        </div>
        @endif
        <div class="text-center mt-5">
            <a href="{{ route('events.index') }}" class="cleenhearts-btn">
                <div class="cleenhearts-btn__icon-box">
                    <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                </div>
                <span class="cleenhearts-btn__text">See All Events</span>
            </a>
        </div>
    </div><!-- /.container -->
</section>
