<x-app-layout>
@section('title', 'Teaching Series')
@section('description', 'Explore our teaching series to deepen your understanding of faith and community.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">Teaching Series</h2>
        <h2 class="page-header__title">Deepen Your Faith Through Our Teaching Series</h2>
        <p class="section-header__text">Explore our collection of teaching series designed to help you grow in your faith and understanding of God's Word.</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Teaching Series</span></li>
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
                            Showing {{ $teachingSeries->firstItem() ?? 0 }}–{{ $teachingSeries->lastItem() ?? 0 }} of {{ $teachingSeries->total() }} Results
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
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Sort by popularity</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="row gutter-y-30">
                    @forelse($teachingSeries as $series)
                        <div class="col-sm-3">
                            <div class="product-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="000ms">
                                <a href="{{ route('teaching-series.show', $series->slug) }}" class="product-item__img" style="position: relative; display: block;">
                                    <img src="{{ $series->image_url }}" alt="{{ $series->title }}" style="width: 100%; display: block;">
                                    @if($series->is_upcoming)
                                        <div class="coming-soon-overlay" style="
                                            position: absolute;
                                            top: 0;
                                            left: 0;
                                            right: 0;
                                            bottom: 0;
                                            background: rgba(44, 90, 160, 0.562);
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            flex-direction: column;
                                            color: white;
                                            z-index: 5;
                                        ">
                                            <i class="icon-clock" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                                            <h4 style="color: white; font-size: 1.5rem; margin: 0; font-weight: 700;">Coming Soon</h4>
                                            <p style="color: white; margin: 0.5rem 0 0 0; font-size: 0.9rem;">{{ $series->series_date->format('M j, Y') }}</p>
                                        </div>
                                    @else
                                        <div class="play-overlay" style="
                                            position: absolute;
                                            top: 0;
                                            left: 0;
                                            right: 0;
                                            bottom: 0;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: rgba(0, 0, 0, 0.3);
                                            opacity: 0;
                                            transition: opacity 0.3s ease;
                                        " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                            <div style="
                                                width: 70px;
                                                height: 70px;
                                                border-radius: 50%;
                                                background: rgba(255, 255, 255, 0.9);
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                                            ">
                                                <span class="icon-play" style="font-size: 24px; color: #2c5aa0; margin-left: 4px;"></span>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No teaching series found</h4>
                                <p>Try adjusting your search criteria or check back later for new content.</p>
                            </div>
                        </div>
                    @endforelse

                    @if($teachingSeries->hasPages())
                        <div class="col-lg-12">
                            <div class="post-pagination product-page__pagination">
                                {{ $teachingSeries->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3">
                <aside class="product__sidebar">
                    <div class="product__search-box product__sidebar__item">
                        <form action="{{ route('teaching-series.index') }}" method="GET" class="product__search">
                            @foreach(request()->except(['search', 'page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="text" name="search" placeholder="Search Teaching Series..." value="{{ request('search') }}">
                            <button type="submit" aria-label="search submit">
                                <span class="icon-search"></span>
                            </button>
                        </form>
                    </div>

                    <div class="product__categories product__sidebar__item">
                        <h3 class="product__sidebar__title">Categories</h3>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('teaching-series.index') }}"
                                   class="{{ !request('category') ? 'active' : '' }}"
                                   data-text="All">
                                    <span>All Categories</span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('teaching-series.index', array_merge(request()->except('page'), ['category' => $category])) }}"
                                       class="{{ request('category') == $category ? 'active' : '' }}"
                                       data-text="{{ $category }}">
                                        <span>{{ $category }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($pastors->isNotEmpty())
                        <div class="product__categories product__sidebar__item">
                            <h3 class="product__sidebar__title">Pastors</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="{{ route('teaching-series.index') }}"
                                       class="{{ !request('pastor') ? 'active' : '' }}"
                                       data-text="All">
                                        <span>All Pastors</span>
                                    </a>
                                </li>
                                @foreach($pastors as $pastor)
                                    <li>
                                        <a href="{{ route('teaching-series.index', array_merge(request()->except('page'), ['pastor' => $pastor])) }}"
                                           class="{{ request('pastor') == $pastor ? 'active' : '' }}"
                                           data-text="{{ $pastor }}">
                                            <span>{{ $pastor }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($featuredSeries->isNotEmpty())
                        <div class="product__sidebar__item">
                            <h3 class="product__sidebar__title">Featured Series</h3>
                            @foreach($featuredSeries as $featured)
                                <div class="product__sidebar__featured-item mb-3">
                                    <a href="{{ route('teaching-series.show', $featured->slug) }}" class="d-flex">
                                        <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($featured->title, 40) }}</h6>
                                            <small class="text-muted">{{ $featured->pastor }}</small>
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
</x-app-layout>
