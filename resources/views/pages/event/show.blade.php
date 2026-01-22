<x-app-layout>
@section('title', 'Events - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">{{ $event->title }}</h2>
        <p class="section-header__text">{{ $event->description }}</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Events</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="event-details section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="event-details__content">
                    <div class="event-details__image wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                        <img src="{{ $event->featured_image_url }}" alt="{{ $event->title }}">
                        @if($event->location)
                        <div class="event-details__hall">
                            <span>Location:</span>
                            <span>{{ $event->location }}</span>
                        </div><!-- /.event-details__hall -->
                        @endif
                        <div class="event-details__date">
                            <span>{{ $event->start_date->format('d') }}</span>
                            <span>{{ $event->start_date->format('M') }}</span>
                        </div><!-- /.event-details__date -->
                    </div><!-- /.event-details__image -->
                    <div class="event-details__time">
                        <i class="event-details__time__icon fa fa-clock"></i>
                        <span class="event-details__time__text">{{ $event->formatted_start_date }}</span>
                    </div><!-- /.event-details__time -->
                    <h3 class="event-details__title">{{ $event->title }}</h3><!-- /.event-details__title -->
                    <div class="event-details__text">
                        {!! $event->content !!}
                    </div><!-- /.event-details__text -->
                </div><!-- /.event-details__content -->

                <div class="event-details__contact contact-information">
                    @if($event->requires_registration)
                        <a href="#" class="contact-information__btn citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Register</span>
                        </a><!-- /.contact-information__btn -->
                        @if($event->registration_details)
                            <div class="event-details__registration">
                                <h4>Registration Information:</h4>
                                <p>{{ $event->registration_details }}</p>
                                @if($event->max_attendees)
                                <p><strong>Maximum Attendees:</strong> {{ $event->max_attendees }}</p>
                                @endif
                        </div>
                        @endif
                    @endif
                </div><!-- /.contact-information -->

            @if($event->eventAnchor || $event->event_anchor || $event->speakers->count() > 0 || $event->guest_speaker)
                <div class="event-details__speaker">
                    <h3 class="event-details__speaker__title event-details__title">Host & Guest Speakers</h3><!-- /.event-details__speaker__title -->

                    <div class="row gutter-y-30">
                        {{-- Event Host/Anchor --}}
                        @if($event->eventAnchor)
                        <div class="col-md-3 text-center">
                            <div class="event-details__speaker__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                <div class="event-details__speaker__image">
                                    <img src="{{ $event->eventAnchor->profile_image_url }}" alt="{{ $event->eventAnchor->first_name }} {{ $event->eventAnchor->last_name }}">
                                </div><!-- /.event-details__speaker__image -->
                                <div class="event-details__speaker__content">
                                    <div class="event-details__speaker__content__inner">
                                        <div class="event-details__speaker__indentity">
                                            <h4 class="event-details__speaker__name">{{ $event->eventAnchor->first_name }} {{ $event->eventAnchor->last_name }}</h4><!-- /.event-details__speaker__name -->
                                            <p class="event-details__speaker__designation">{{ $event->eventAnchor->position ?? 'Event Host' }}</p><!-- /.event-details__speaker__designation -->
                                        </div><!-- /.event-details__speaker__indentity -->
                                    </div>
                                </div><!-- /.event-details__speaker__content -->
                            </div><!-- /.event-details__speaker__info -->
                        </div><!-- /.col-md-3 -->
                        @elseif($event->event_anchor)
                        <div class="col-md-3 text-center">
                            <div class="event-details__speaker__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                <div class="event-details__speaker__image">
                                    <img src="{{ asset('assets/images/events/event-speaker-1-1.png') }}" alt="{{ $event->event_anchor }}">
                                </div><!-- /.event-details__speaker__image -->
                                <div class="event-details__speaker__content">
                                    <div class="event-details__speaker__content__inner">
                                        <div class="event-details__speaker__indentity">
                                            <h4 class="event-details__speaker__name">{{ $event->event_anchor }}</h4><!-- /.event-details__speaker__name -->
                                            <p class="event-details__speaker__designation">Host</p><!-- /.event-details__speaker__designation -->
                                        </div><!-- /.event-details__speaker__indentity -->
                                    </div>
                                </div><!-- /.event-details__speaker__content -->
                            </div><!-- /.event-details__speaker__info -->
                        </div><!-- /.col-md-3 -->
                        @endif

                        {{-- Guest Speakers from relationship --}}
                        @foreach($event->speakers as $index => $speaker)
                        <div class="col-md-3 text-center">
                            <div class="event-details__speaker__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="{{ ($index + 1) * 100 }}ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: {{ ($index + 1) * 100 }}ms; animation-name: fadeInUp;">
                                <div class="event-details__speaker__image">
                                    <img src="{{ $speaker->image_url }}" alt="{{ $speaker->name }}">
                                </div><!-- /.event-details__speaker__image -->
                                <div class="event-details__speaker__content">
                                    <div class="event-details__speaker__content__inner">
                                        <div class="event-details__speaker__indentity">
                                            <h4 class="event-details__speaker__name">{{ $speaker->name }}</h4><!-- /.event-details__speaker__name -->
                                            <p class="event-details__speaker__designation">{{ $speaker->title ?? 'Guest Speaker' }}</p><!-- /.event-details__speaker__designation -->
                                        </div><!-- /.event-details__speaker__indentity -->
                                    </div>
                                </div><!-- /.event-details__speaker__content -->
                            </div><!-- /.event-details__speaker__info -->
                        </div><!-- /.col-md-3 -->
                        @endforeach

                        {{-- Legacy guest speaker --}}
                        @if($event->guest_speaker && $event->speakers->count() == 0)
                        <div class="col-md-3 text-center">
                            <div class="event-details__speaker__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="100ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                                <div class="event-details__speaker__image">
                                    <img src="{{ asset('assets/images/events/event-speaker-1-2.png') }}" alt="{{ $event->guest_speaker }}">
                                </div><!-- /.event-details__speaker__image -->
                                <div class="event-details__speaker__content">
                                    <div class="event-details__speaker__content__inner">
                                        <div class="event-details__speaker__indentity">
                                            <h4 class="event-details__speaker__name">{{ $event->guest_speaker }}</h4><!-- /.event-details__speaker__name -->
                                            <p class="event-details__speaker__designation">Guest Speaker</p><!-- /.event-details__speaker__designation -->
                                        </div><!-- /.event-details__speaker__indentity -->
                                    </div>
                                </div><!-- /.event-details__speaker__content -->
                            </div><!-- /.event-details__speaker__info -->
                        </div><!-- /.col-md-3 -->
                        @endif
                    </div><!-- /.row -->
                </div><!-- /.event-details__speaker -->
                @endif
            </div><!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <aside class="sidebar-event">
                    <div class="sidebar-event__contact contact-one sidebar-event__item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                        <div class="contact-one__info">
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-location"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Event Location</h4>
                                    <address class="contact-one__info__text">{{ $event->location }}</address>
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-clock"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Date & Time</h4>
                                    <p class="contact-one__info__text">{{ $event->formatted_start_date }}</p>
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-phone"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Quick Contact</h4>
                                    @if($event->contact_phone)
                                        <a href="tel:{{ $event->contact_phone }}" class="contact-one__info__text contact-one__info__text--link">{{ $event->contact_phone }}</a>
                                    @elseif($event->contactPerson && $event->contactPerson->phone)
                                        <a href="tel:{{ $event->contactPerson->phone }}" class="contact-one__info__text contact-one__info__text--link">{{ $event->contactPerson->phone }}</a>
                                    @else
                                        <a href="tel:(406)555-0120" class="contact-one__info__text contact-one__info__text--link">(406) 555-0120</a>
                                    @endif
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-envelope"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">
                                        @if($event->contactPerson)
                                            Contact {{ $event->contactPerson->first_name }}
                                        @else
                                            Support Email
                                        @endif
                                    </h4>
                                    @if($event->contact_email)
                                        <a href="mailto:{{ $event->contact_email }}" class="contact-one__info__text contact-one__info__text--link">{{ $event->contact_email }}</a>
                                    @elseif($event->contactPerson && $event->contactPerson->email)
                                        <a href="mailto:{{ $event->contactPerson->email }}" class="contact-one__info__text contact-one__info__text--link">{{ $event->contactPerson->email }}</a>
                                    @else
                                        <a href="mailto:info@citylifecc.com" class="contact-one__info__text contact-one__info__text--link">info@citylifecc.com</a>
                                    @endif
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                        </div><!-- /.contact-one__info -->
                        <div class="sidebar-event__contact__image">
                            <img src="assets/images/events/event-d-1-4.jpg" alt="events">
                        </div><!-- /.sidebar-event__contact__image -->
                    </div><!-- /.sidebar-event__contact -->
                </aside><!-- /.sidebar-event -->
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
</x-app-layout>
