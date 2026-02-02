<x-app-layout>
@section('title', 'Resources - Bible School International')
@section('description', 'Browse our collection of teaching resources from renowned speakers.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">Our Bible School</h2>
        <h2 class="page-header__title">Teaching Resources</h2>
        <p class="section-header__text">Browse sessions from our speakers organized by year</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('bible-school-international.about') }}">Bible School</a></li>
            <li><span>Resources</span></li>
        </ul>
    </div>
</section>

<section class="product-page section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-9">
                <div class="product__info-top">
                    <div class="product__showing-text-box">
                        <p class="product__showing-text">
                            Showing {{ $speakers->count() }} Speakers
                        </p>
                    </div>
                </div>

                <!-- Speakers Grid -->
                <div class="row gutter-y-30">
                    @forelse($speakers as $speaker)
                        @php
                            $totalVideos = 0;
                            $totalAudios = 0;
                            foreach($speaker->events as $event) {
                                $totalVideos += $event->videos->count();
                                $totalAudios += $event->audios->count();
                            }
                        @endphp

                        @if($speaker->events->count() > 0)
                        <div class="col-sm-6">
                            <div class="product-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="000ms">
                                <a href="{{ route('bible-school-international.speaker', $speaker->id) }}" class="product-item__img">
                                    @if($speaker->photo)
                                        <img src="{{ Storage::url($speaker->photo) }}" alt="{{ $speaker->name }}" style="object-fit: cover; height: 300px;">
                                    @else
                                        <img src="{{ asset('assets/images/events/event-speaker-1-1.png') }}" alt="{{ $speaker->name }}">
                                    @endif
                                </a>
                                <div class="product-item__content">
                                    <h4 class="product-item__title">
                                        <a href="{{ route('bible-school-international.speaker', $speaker->id) }}">{{ $speaker->name }}</a>
                                    </h4>
                                    <div class="product-item__meta">
                                        @if($speaker->title)
                                            <span class="d-block text-primary">{{ $speaker->title }}</span>
                                        @endif
                                        @if($speaker->organization)
                                            <span class="d-block text-muted small">{{ $speaker->organization }}</span>
                                        @endif
                                        <span class="d-block mt-2">
                                            <small>
                                                <i class="icon-video"></i> {{ $totalVideos }} Videos |
                                                <i class="icon-music"></i> {{ $totalAudios }} Audios
                                            </small>
                                        </span>
                                        <span class="d-block mt-1">
                                            <small class="text-muted">
                                                {{ $speaker->events->count() }} {{ Str::plural('Session', $speaker->events->count()) }}
                                            </small>
                                        </span>
                                    </div>
                                    <a href="{{ route('bible-school-international.speaker', $speaker->id) }}" class="citylife-btn citylife-btn--border product-item__link">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-play"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">View Sessions</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No speakers found</h4>
                                <p class="text-muted">Please check back later for updates</p>
                                <a href="{{ route('bible-school-international.about') }}" class="citylife-btn mt-3">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Back to About</span>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="product__sidebar">
                    <div class="product__categories product__sidebar__item">
                        <h3 class="product__sidebar__title">Archive</h3>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('bible-school-international.resources') }}"
                                   class="{{ !request()->is('*archive*') ? 'active' : '' }}"
                                   data-text="All">
                                    <span>All Years</span>
                                </a>
                            </li>
                            @foreach($years as $year)
                                <li>
                                    <a href="{{ route('bible-school-international.archive', $year) }}"
                                       class="{{ request()->is('*archive/' . $year) ? 'active' : '' }}"
                                       data-text="{{ $year }}">
                                        <span>{{ $year }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
