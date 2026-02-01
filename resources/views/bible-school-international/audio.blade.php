<x-app-layout>
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $audio->title }}</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('bible-school-international.index') }}">Bible School</a></li>
                <li><a href="{{ route('bible-school-international.event', $event->id) }}">{{ $event->title }}</a></li>
                <li><span>{{ $audio->title }}</span></li>
            </ul>
        </div>
    </section>

    <section class="event-details section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-lg-8">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(!$hasAccess)
                    <!-- Access Code Required -->
                    <div class="contact-information text-center p-5">
                        <i class="fa fa-lock text-warning mb-3" style="font-size: 4rem;"></i>
                        <h3 class="mb-3">Access Code Required</h3>
                        <p class="lead mb-4">Please enter your unique access code to listen to this audio.</p>

                        <form action="{{ route('bible-school-international.verify-resource-code', [$event->id, 'audio', $audio->id]) }}" method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <input type="text" name="access_code" class="form-control form-control-lg text-center mb-3" placeholder="Enter Access Code" required style="text-transform: uppercase; letter-spacing: 2px;">
                                    <button type="submit" class="citylife-btn w-100">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">Unlock Audio</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4">
                            <a href="{{ route('bible-school-international.event', $event->id) }}" class="citylife-btn citylife-btn--border">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="citylife-btn__text">Back to Event</span>
                            </a>
                        </div>
                    </div>
                    @else
                    <!-- Audio Player -->
                    <div class="event-details__content">
                        <div class="event-details__image wow fadeInUp animated">
                            <div class="bg-gradient-primary d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                                <i class="fa fa-headphones text-white" style="font-size: 5rem;"></i>
                            </div>
                            <div class="p-4 bg-light">
                                <audio controls class="w-100" controlsList="nodownload">
                                    <source src="{{ $audio->audio_url }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>

                        <h3 class="event-details__title mt-4">{{ $audio->title }}</h3>

                        <div class="event-details__time">
                            <i class="event-details__time__icon fa fa-calendar"></i>
                            <span class="event-details__time__text">{{ $event->title }} ({{ $event->year }})</span>
                        </div>

                        @if($audio->description)
                        <div class="event-details__text mt-4">
                            <h5>Description</h5>
                            <p>{{ $audio->description }}</p>
                        </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('bible-school-international.event', $event->id) }}" class="citylife-btn citylife-btn--border">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="citylife-btn__text">Back to Event</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="sidebar-event">
                        <div class="sidebar-event__contact contact-one sidebar-event__item wow fadeInUp animated">
                            <div class="contact-one__content">
                                <h4 class="contact-one__title">Audio Information</h4>
                                <ul class="list-unstyled">
                                    <li><strong>Event:</strong> {{ $event->title }}</li>
                                    <li><strong>Year:</strong> {{ $event->year }}</li>
                                    @if($audio->duration)
                                    <li><strong>Duration:</strong> {{ $audio->formatted_duration }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        @if($hasAccess)
                        @php
                            $otherAudios = $event->audios->where('id', '!=', $audio->id)->take(5);
                        @endphp
                        @if($otherAudios->count() > 0)
                        <div class="sidebar-event__item wow fadeInUp animated" data-wow-delay="100ms">
                            <h3 class="sidebar-event__title">More Audios</h3>
                            <ul class="list-unstyled sidebar-event__links">
                                @foreach($otherAudios as $otherAudio)
                                <li>
                                    <a href="{{ route('bible-school-international.audio', [$event->id, $otherAudio->id]) }}">
                                        <i class="fa fa-headphones"></i> {{ Str::limit($otherAudio->title, 40) }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
