<x-app-layout>
@section('title', $event->title . ' - Bible School International')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">{{ $event->title }}</h2>
        <p class="section-header__text text-white">{{ Str::limit(strip_tags($event->description), 200) }}</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('bible-school-international.index') }}">Bible School International</a></li>
            <li><span>{{ $event->title }}</span></li>
        </ul>
    </div>
</section>

<section class="event-details section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="event-details__content">
                    <div class="event-details__image wow fadeInUp animated" data-wow-duration="1500ms">
                        @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                        @else
                        <img src="{{ asset('assets/images/events/event-2-1.jpg') }}" alt="{{ $event->title }}">
                        @endif
                        @if($event->location)
                        <div class="event-details__hall">
                            <span>Location:</span>
                            <span>{{ $event->location }}</span>
                        </div>
                        @endif
                        <div class="event-details__date">
                            <span>{{ $event->year }}</span>
                        </div>
                    </div>

                    @if($event->start_date)
                    <div class="event-details__time">
                        <i class="event-details__time__icon fa fa-clock"></i>
                        <span class="event-details__time__text">
                            {{ $event->start_date->format('F d, Y') }}
                            @if($event->end_date && !$event->start_date->eq($event->end_date))
                            - {{ $event->end_date->format('F d, Y') }}
                            @endif
                        </span>
                    </div>
                    @endif

                    <h3 class="event-details__title">{{ $event->title }}</h3>

                    @if($event->description)
                    <div class="event-details__text">
                        {!! $event->description !!}
                    </div>
                    @endif
                </div>

                <!-- Speakers Section -->
                @if($event->speakers->count() > 0)
                <div class="event-details__speaker">
                    <h3 class="event-details__speaker__title event-details__title">Event Speakers</h3>
                    <div class="row gutter-y-30">
                        @foreach($event->speakers as $index => $speaker)
                        <div class="col-md-3 text-center">
                            <div class="event-details__speaker__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="{{ $index * 100 }}ms">
                                <div class="event-details__speaker__image">
                                    @if($speaker->photo)
                                    <img src="{{ Storage::url($speaker->photo) }}" alt="{{ $speaker->name }}">
                                    @else
                                    <img src="{{ asset('assets/images/events/event-speaker-1-1.png') }}" alt="{{ $speaker->name }}">
                                    @endif
                                </div>
                                <div class="event-details__speaker__content">
                                    <div class="event-details__speaker__content__inner">
                                        <div class="event-details__speaker__indentity">
                                            <h4 class="event-details__speaker__name">{{ $speaker->name }}</h4>
                                            <p class="event-details__speaker__designation">{{ $speaker->title ?? 'Speaker' }}</p>
                                            @if($speaker->organization)
                                            <p class="text-muted small">{{ $speaker->organization }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Access Code & Resources Section -->
                <div class="event-details__contact contact-information mt-5">
                    @if(!$hasAccess)
                    <h4 class="contact-one__title"><i class="fa fa-lock me-2"></i>Access Code Required</h4>
                    <p class="contact-one__text">To access the videos and audio resources for this event, please enter your unique access code below.</p>

                    @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('bible-school-international.verify-code', $event->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input type="text" name="access_code" class="form-control form-control-lg" placeholder="Enter your access code" required style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="citylife-btn w-100">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Unlock Resources</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle me-2"></i>Access Granted! You can now view all resources for this event.
                    </div>
                    @endif
                </div>

                <!-- Videos Section -->
                @if($hasAccess && $event->videos->count() > 0)
                <div class="mt-5">
                    <h3 class="event-details__title mb-4"><i class="fa fa-video me-2"></i>Video Resources</h3>
                    <div class="row gutter-y-30">
                        @foreach($event->videos as $video)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    @if($video->thumbnail)
                                    <img src="{{ Storage::url($video->thumbnail) }}" class="card-img-top" alt="{{ $video->title }}">
                                    @else
                                    <div class="bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa fa-play-circle text-white" style="font-size: 3rem;"></i>
                                    </div>
                                    @endif
                                    @if($video->duration)
                                    <span class="badge bg-dark position-absolute bottom-0 end-0 m-2">{{ $video->formatted_duration }}</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $video->title }}</h5>
                                    @if($video->description)
                                    <p class="card-text text-muted small">{{ Str::limit($video->description, 100) }}</p>
                                    @endif
                                    <a href="{{ route('bible-school-international.video', [$event->id, $video->id]) }}" class="citylife-btn citylife-btn--border w-100">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">Watch Video</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Audios Section -->
                @if($hasAccess && $event->audios->count() > 0)
                <div class="mt-5">
                    <h3 class="event-details__title mb-4"><i class="fa fa-headphones me-2"></i>Audio Resources</h3>
                    <div class="row gutter-y-30">
                        @foreach($event->audios as $audio)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 120px;">
                                    <i class="fa fa-headphones text-white" style="font-size: 2.5rem;"></i>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $audio->title }}</h5>
                                    @if($audio->description)
                                    <p class="card-text text-muted small">{{ Str::limit($audio->description, 100) }}</p>
                                    @endif
                                    @if($audio->duration)
                                    <p class="small text-muted mb-3">
                                        <i class="fa fa-clock me-1"></i>{{ $audio->formatted_duration }}
                                    </p>
                                    @endif
                                    <a href="{{ route('bible-school-international.audio', [$event->id, $audio->id]) }}" class="citylife-btn citylife-btn--border w-100">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">Listen</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar-event">
                    <div class="sidebar-event__contact contact-one sidebar-event__item wow fadeInUp animated" data-wow-duration="1500ms">
                        <div class="contact-one__info">
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-calendar"></span>
                                </div>
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Event Year</h4>
                                    <p class="contact-one__info__text">{{ $event->year }}</p>
                                </div>
                            </div>
                            @if($event->location)
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-location"></span>
                                </div>
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Location</h4>
                                    <address class="contact-one__info__text">{{ $event->location }}</address>
                                </div>
                            </div>
                            @endif
                            @if($event->start_date)
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-clock"></span>
                                </div>
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Dates</h4>
                                    <p class="contact-one__info__text">
                                        {{ $event->start_date->format('M d, Y') }}
                                        @if($event->end_date && !$event->start_date->eq($event->end_date))
                                        - {{ $event->end_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-video"></span>
                                </div>
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Resources</h4>
                                    <p class="contact-one__info__text">
                                        {{ $event->videos->count() }} Videos<br>
                                        {{ $event->audios->count() }} Audio Files
                                        @if($event->speakers->count() > 0)
                                        <br>{{ $event->speakers->count() }} Speakers
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-envelope"></span>
                                </div>
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Need Help?</h4>
                                    <p class="contact-one__info__text">Contact us for access codes or assistance</p>
                                    <a href="{{ route('contact') }}" class="contact-one__info__text contact-one__info__text--link">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Speakers Sidebar -->
                    @if($event->speakers->count() > 0)
                    <div class="sidebar-event__item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="50ms">
                        <h3 class="sidebar-event__title">Speakers</h3>
                        <ul class="list-unstyled">
                            @foreach($event->speakers as $speaker)
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    @if($speaker->photo)
                                    <img src="{{ Storage::url($speaker->photo) }}" alt="{{ $speaker->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong class="d-block">{{ $speaker->name }}</strong>
                                        @if($speaker->full_title)
                                        <small class="text-muted">{{ $speaker->full_title }}</small>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Archive Link -->
                    <div class="sidebar-event__item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="100ms">
                        <a href="{{ route('bible-school-international.archive', $event->year) }}" class="citylife-btn citylife-btn--border w-100">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">View {{ $event->year }} Archive</span>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
</x-app-layout>
