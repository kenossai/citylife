<x-app-layout>
@section('title', 'Bible School International')
@section('description', 'Access Bible School resources and teachings from our international events.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">Our Bible School</h2>
        <h2 class="page-header__title">Bible School International</h2>
        <p class="section-header__text">Access Bible School resources and teachings from our international events.</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Bible School</span></li>
        </ul>
    </div>
</section>

<section class="product-page section-space-bottom">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-9">
                <div class="product__info-top">
                    <div class="product__showing-text-box">
                        <p class="product__showing-text">
                            Showing {{ $events->count() }} of {{ $events->count() }} Results
                        </p>
                    </div>
                    <div class="product__showing-sort">
                        <form method="GET" class="d-inline">
                            <select name="sort" class="form-select" onchange="this.form.submit()" aria-label="Sort by">
                                <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Sort by latest year</option>
                                <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Sort by oldest year</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Sort by title</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="row gutter-y-30">
                    @forelse($events as $event)
                        <div class="col-sm-4">
                            <div class="product-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="000ms">
                                <a href="{{ route('bible-school-international.event', $event->id) }}" class="product-item__img">
                                    @if($event->image)
                                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/events/event-2-1.jpg') }}" alt="{{ $event->title }}">
                                    @endif
                                </a>
                                <div class="product-item__content">
                                    <h4 class="product-item__title">
                                        <a href="{{ route('bible-school-international.event', $event->id) }}" style="font-size: 14px;">{{ $event->title }}</a>
                                    </h4>
                                    <div class="product-item__meta">
                                        @if($event->start_date)
                                            <span class="d-block">{{ $event->start_date->format('M d, Y') }}</span>
                                        @endif
                                        @if($event->location)
                                            <span class="d-block text-muted"><i class="icon-location"></i> {{ $event->location }}</span>
                                        @endif
                                        <span class="d-block mt-2">
                                            <small>{{ $event->videos->count() }} Videos | {{ $event->audios->count() }} Audios</small>
                                        </span>
                                    </div>
                                    <a href="{{ route('bible-school-international.event', $event->id) }}" class="citylife-btn citylife-btn--border product-item__link">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-play"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">View Resources</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No events found</h4>
                                <p>There are currently no Bible School events available.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="product__sidebar">
                    <div class="product__search-box product__sidebar__item">
                        <form action="{{ route('bible-school-international.index') }}" method="GET" class="product__search">
                            <input type="text" name="search" placeholder="Search Events..." value="{{ request('search') }}">
                            <button type="submit" aria-label="search submit">
                                <span class="icon-search"></span>
                            </button>
                        </form>
                    </div>

                    @if($years->count() > 0)
                        <div class="product__categories product__sidebar__item">
                            <h3 class="product__sidebar__title">Archive by Year</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('bible-school-international.index') }}"
                                       class="{{ !request('year') ? 'active' : '' }}"
                                       data-text="All">
                                        <span>All Years</span>
                                    </a>
                                </li>
                                @foreach($years as $archiveYear)
                                    <li>
                                        <a href="{{ route('bible-school-international.archive', $archiveYear) }}"
                                           class="{{ isset($year) && $year == $archiveYear ? 'active' : '' }}"
                                           data-text="{{ $archiveYear }}">
                                            <span>{{ $archiveYear }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="product__sidebar__item">
                        <div class="sidebar-event__contact contact-one">
                            <div class="contact-one__content">
                                <h4 class="contact-one__title">Need Access?</h4>
                                <p class="contact-one__text">Each event requires a unique access code. Contact us if you need assistance.</p>
                                <a href="{{ route('contact') }}" class="citylife-btn citylife-btn--border">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Contact Us</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
</x-app-layout>
