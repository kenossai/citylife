<x-app-layout>
@section('title', 'CityLife Music')
@section('description', 'Discover our collection of inspiring worship music and songs from CityLife Church.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">CityLife Music</h2>
        <h2 class="page-header__title">Worship Songs & Music</h2>
        <p class="section-header__text">Experience the heart of worship through our collection of inspiring songs and music that lift your spirit and draw you closer to God.</p>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>CityLife Music</span></li>
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
                            Showing {{ $music->firstItem() ?? 0 }}–{{ $music->lastItem() ?? 0 }} of {{ $music->total() }} Results
                        </p>
                    </div>
                    <div class="product__showing-sort">
                        <form method="GET" class="d-inline">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" class="form-select" onchange="this.form.submit()" aria-label="Sort by">
                                <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Sort by featured</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by newest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Sort by oldest</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Sort by title</option>
                                <option value="artist" {{ request('sort') == 'artist' ? 'selected' : '' }}>Sort by artist</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="row gutter-y-30">
                    @forelse($music as $song)
                        <div class="col-sm-6 col-lg-4">
                            <div class="product-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="000ms">
                                <a href="{{ route('citylife-music.show', $song->slug) }}" class="product-item__img">
                                    <img src="{{ $song->image_url }}" alt="{{ $song->title }}">
                                    @if($song->is_featured)
                                        <div class="product-item__featured-badge">
                                            <span class="badge bg-primary">Featured</span>
                                        </div>
                                    @endif
                                    <div class="product-item__play-overlay">
                                        <span class="icon-play"></span>
                                    </div>
                                </a>
                                <div class="product-item__content">
                                    <h4 class="product-item__title">
                                        <a href="{{ route('citylife-music.show', $song->slug) }}">{{ $song->title }}</a>
                                    </h4>
                                    <div class="product-item__meta">
                                        @if($song->artist)
                                            <span class="d-block"><strong>Artist:</strong> {{ $song->artist }}</span>
                                        @endif
                                        @if($song->album)
                                            <span class="d-block"><strong>Album:</strong> {{ $song->album }}</span>
                                        @endif
                                        @if($song->genre)
                                            <span class="d-block text-muted">{{ $song->genre }}</span>
                                        @endif
                                    </div>
                                    @if($song->description)
                                        <p class="product-item__excerpt">{{ Str::limit($song->description, 100) }}</p>
                                    @endif

                                    <div class="product-item__actions">
                                        <a href="{{ route('citylife-music.show', $song->slug) }}" class="cleenhearts-btn cleenhearts-btn--border product-item__link">
                                            <div class="cleenhearts-btn__icon-box">
                                                <div class="cleenhearts-btn__icon-box__inner"><span class="icon-play"></span></div>
                                            </div>
                                            <span class="cleenhearts-btn__text">Listen</span>
                                        </a>

                                        <!-- Quick Links -->
                                        <div class="product-item__quick-links mt-2">
                                            @if($song->spotify_url)
                                                <a href="{{ $song->spotify_url }}" target="_blank" class="quick-link" title="Listen on Spotify">
                                                    <i class="fab fa-spotify"></i>
                                                </a>
                                            @endif
                                            @if($song->apple_music_url)
                                                <a href="{{ $song->apple_music_url }}" target="_blank" class="quick-link" title="Listen on Apple Music">
                                                    <i class="fab fa-apple"></i>
                                                </a>
                                            @endif
                                            @if($song->youtube_url)
                                                <a href="{{ $song->youtube_url }}" target="_blank" class="quick-link" title="Watch on YouTube">
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No music found</h4>
                                <p>Try adjusting your search criteria or check back later for new music.</p>
                            </div>
                        </div>
                    @endforelse

                    @if($music->hasPages())
                        <div class="col-lg-12">
                            <div class="post-pagination product-page__pagination">
                                {{ $music->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="product__sidebar">
                    <div class="product__search-box product__sidebar__item">
                        <form action="{{ route('citylife-music.index') }}" method="GET" class="product__search">
                            @foreach(request()->except(['search', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="text" name="search" placeholder="Search music..." value="{{ request('search') }}">
                            <button type="submit" aria-label="search submit">
                                <span class="icon-search"></span>
                            </button>
                        </form>
                    </div>

                    @if($artists->isNotEmpty())
                        <div class="product__categories product__sidebar__item">
                            <h3 class="product__sidebar__title">Artists</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('citylife-music.index') }}"
                                       class="{{ !request('artist') ? 'active' : '' }}"
                                       data-text="All">
                                        <span>All Artists</span>
                                    </a>
                                </li>
                                @foreach($artists as $artist)
                                    <li>
                                        <a href="{{ route('citylife-music.index', array_merge(request()->except('page'), ['artist' => $artist])) }}"
                                           class="{{ request('artist') == $artist ? 'active' : '' }}"
                                           data-text="{{ $artist }}">
                                            <span>{{ $artist }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($genres->isNotEmpty())
                        <div class="product__categories product__sidebar__item">
                            <h3 class="product__sidebar__title">Genres</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('citylife-music.index') }}"
                                       class="{{ !request('genre') ? 'active' : '' }}"
                                       data-text="All">
                                        <span>All Genres</span>
                                    </a>
                                </li>
                                @foreach($genres as $genre)
                                    <li>
                                        <a href="{{ route('citylife-music.index', array_merge(request()->except('page'), ['genre' => $genre])) }}"
                                           class="{{ request('genre') == $genre ? 'active' : '' }}"
                                           data-text="{{ $genre }}">
                                            <span>{{ $genre }}</span>
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
                                <a href="{{ route('citylife-music.index', array_merge(request()->except(['featured', 'page']), ['featured' => '1'])) }}"
                                   class="{{ request('featured') == '1' ? 'active' : '' }}"
                                   data-text="Featured">
                                    <span>Featured Music</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if($featuredMusic->isNotEmpty())
                        <div class="product__sidebar__item">
                            <h3 class="product__sidebar__title">Featured Music</h3>
                            @foreach($featuredMusic as $featured)
                                <div class="product__sidebar__featured-item mb-3">
                                    <a href="{{ route('citylife-music.show', $featured->slug) }}" class="d-flex">
                                        <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($featured->title, 40) }}</h6>
                                            @if($featured->artist)
                                                <small class="text-muted">{{ $featured->artist }}</small>
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

.product-item__play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.7);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-item__img:hover .product-item__play-overlay {
    opacity: 1;
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
    overflow: hidden;
}

.product-item__quick-links {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.quick-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-link:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

.quick-link i {
    font-size: 0.875rem;
}
</style>
</x-app-layout>
