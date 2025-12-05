<x-app-layout>
@section('title', 'CityLife TalkTime')
@section('description', 'Explore our CityLife TalkTime episodes featuring inspiring conversations and discussions.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">CityLife TalkTime</h2>
        <h2 class="page-header__title">Inspiring Conversations and Discussions</h2>
        <p class="section-header__text">Join us for engaging conversations that inspire, encourage, and build community through meaningful dialogue.</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>CityLife TalkTime</span></li>
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
                            Showing {{ $talkTimes->firstItem() ?? 0 }}–{{ $talkTimes->lastItem() ?? 0 }} of {{ $talkTimes->total() }} Results
                        </p>
                    </div>
                    <div class="product__showing-sort">
                        <form method="GET" class="d-inline">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" class="form-select" onchange="this.form.submit()" aria-label="Sort by">
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Sort by latest</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Sort by oldest</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Sort by title</option>
                                <option value="host" {{ request('sort') == 'host' ? 'selected' : '' }}>Sort by host</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="row gutter-y-30">
                    @forelse($talkTimes as $talkTime)
                        <div class="col-sm-6">
                            <div class="product-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="000ms">
                                <a href="{{ route('citylife-talktime.show', $talkTime->slug) }}" class="product-item__img">
                                    @if($talkTime->image)
                                        <img src="{{ Storage::url('' . $talkTime->image) }}" alt="{{ $talkTime->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/defaults/talktime-default.jpg') }}" alt="{{ $talkTime->title }}">
                                    @endif
                                    @if($talkTime->is_featured)
                                        <div class="product-item__featured-badge">
                                            <span class="badge bg-primary">Featured</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="product-item__content">
                                    <h4 class="product-item__title">
                                        <a href="{{ route('citylife-talktime.show', $talkTime->slug) }}">{{ $talkTime->title }}</a>
                                    </h4>
                                    <div class="product-item__meta">
                                        @if($talkTime->host)
                                            <span class="d-block"><strong>Host:</strong> {{ $talkTime->host }}</span>
                                        @endif
                                        @if($talkTime->guest)
                                            <span class="d-block"><strong>Guest:</strong> {{ $talkTime->guest }}</span>
                                        @endif
                                        @if($talkTime->episode_date)
                                            <span class="d-block text-muted">{{ $talkTime->episode_date->format('M d, Y') }}</span>
                                        @endif
                                        @if($talkTime->duration)
                                            <span class="d-block text-muted">Duration: {{ $talkTime->duration }}</span>
                                        @endif
                                    </div>
                                    @if($talkTime->description)
                                        <p class="product-item__excerpt">{{ Str::limit($talkTime->description, 100) }}</p>
                                    @endif
                                    <a href="{{ route('citylife-talktime.show', $talkTime->slug) }}" class="citylife-btn citylife-btn--border product-item__link">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-play"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">Watch/Listen</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No TalkTime episodes found</h4>
                                <p>Try adjusting your search criteria or check back later for new episodes.</p>
                            </div>
                        </div>
                    @endforelse

                    @if($talkTimes->hasPages())
                        <div class="col-lg-12">
                            <div class="post-pagination product-page__pagination">
                                {{ $talkTimes->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="product__sidebar">
                    <div class="product__search-box product__sidebar__item">
                        <form action="{{ route('citylife-talktime.index') }}" method="GET" class="product__search">
                            @foreach(request()->except(['search', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="text" name="search" placeholder="Search TalkTime episodes..." value="{{ request('search') }}">
                            <button type="submit" aria-label="search submit">
                                <span class="icon-search"></span>
                            </button>
                        </form>
                    </div>

                    @if($hosts->isNotEmpty())
                        <div class="product__categories product__sidebar__item">
                            <h3 class="product__sidebar__title">Hosts</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('citylife-talktime.index') }}"
                                       class="{{ !request('host') ? 'active' : '' }}"
                                       data-text="All">
                                        <span>All Hosts</span>
                                    </a>
                                </li>
                                @foreach($hosts as $host)
                                    <li>
                                        <a href="{{ route('citylife-talktime.index', array_merge(request()->except('page'), ['host' => $host])) }}"
                                           class="{{ request('host') == $host ? 'active' : '' }}"
                                           data-text="{{ $host }}">
                                            <span>{{ $host }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="product__categories product__sidebar__item">
                        <h3 class="product__sidebar__title">Filter</h3>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('citylife-talktime.index', array_merge(request()->except(['featured', 'page']), ['featured' => '1'])) }}"
                                   class="{{ request('featured') == '1' ? 'active' : '' }}"
                                   data-text="Featured">
                                    <span>Featured Episodes</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if($featuredTalkTimes->isNotEmpty())
                        <div class="product__sidebar__item">
                            <h3 class="product__sidebar__title">Featured Episodes</h3>
                            @foreach($featuredTalkTimes as $featured)
                                <div class="product__sidebar__featured-item mb-3">
                                    <a href="{{ route('citylife-talktime.show', $featured->slug) }}" class="d-flex">
                                        @if($featured->image)
                                            <img src="{{ Storage::url('' . $featured->image) }}" alt="{{ $featured->title }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/images/defaults/talktime-default.jpg') }}" alt="{{ $featured->title }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($featured->title, 40) }}</h6>
                                            @if($featured->host)
                                                <small class="text-muted">{{ $featured->host }}</small>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>

<style>
.product-item__featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.product-item__meta span {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.product-item__excerpt {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.product-item__img {
    position: relative;
    display: block;
}
</style>
</x-app-layout>
