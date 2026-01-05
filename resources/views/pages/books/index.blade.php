<x-app-layout>
    @section('title', 'Books & Publications - CityLife Church')
    @section('description', 'Explore books and publications by our team members. Discover inspiring Christian literature, theological works, and spiritual guidance.')
    @section('keywords', 'Christian books, publications, theological books, spiritual literature, CityLife authors')

    {{-- Page Header --}}
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Books & Publications</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Books</span></li>
            </ul>
        </div>
    </section>

    {{-- Books Introduction Section --}}
    <section class="about-two section-space">
        <div class="container">
            <div class="row gutter-y-50 align-items-center">
                <div class="col-lg-6">
                    <div class="about-two__image">
                        <img src="{{ asset('assets/images/resources/about-2-1.png') }}" alt="books" class="about-two__image__one wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <img src="{{ asset('assets/images/shapes/about-2-shape-1.png') }}" alt="shape" class="about-two__image__shape">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-two__content">
                        <div class="sec-title">
                            <h6 class="sec-title__tagline">OUR PUBLICATIONS</h6>
                            <h3 class="sec-title__title">Discover Inspiring <span class="sec-title__title__inner">Christian Literature</span></h3>
                        </div>
                        <div class="about-two__text-box">
                            <p class="about-two__text">
                                Explore a collection of books and publications authored by our dedicated team members. 
                                From theological insights to practical Christian living, each work reflects years of 
                                ministry experience and deep biblical understanding.
                            </p>
                        </div>
                        <div class="about-two__text-box">
                            <p class="about-two__text">
                                Whether you're seeking spiritual growth, biblical knowledge, or inspiration for your 
                                faith journey, our publications offer wisdom and guidance rooted in Scripture and 
                                enriched by real-life ministry experience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Books Grid Section --}}
    <section class="products section-space-bottom">
        <div class="container">
            @if($books->count() > 0)
                <div class="row gutter-y-30">
                    @foreach($books as $book)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="product-card wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="product-card__image">
                                <img src="{{ $book->cover_image ? Storage::disk('s3')->url($book->cover_image) : asset('assets/images/products/product-1-1.png') }}" alt="{{ $book->title }}">
                                @if($book->is_featured)
                                <div class="product-card__sale">Featured</div>
                                @endif
                                <div class="product-card__quick-view">
                                    <a href="{{ route('books.show', $book->slug) }}" class="product-card__quick-view__icon">
                                        <span class="icon-eye"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="product-card__content">
                                <div class="product-card__content__top">
                                    @if($book->category)
                                    <div class="product-card__category">
                                        <a href="{{ route('books.index', ['category' => $book->category]) }}">{{ $book->category }}</a>
                                    </div>
                                    @endif
                                    @if($book->teamMember)
                                    <div class="product-card__author">
                                        <span class="icon-user"></span>
                                        <a href="{{ route('team.show', $book->teamMember->slug) }}">{{ $book->teamMember->full_name }}</a>
                                    </div>
                                    @endif
                                </div>
                                <h3 class="product-card__title">
                                    <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
                                </h3>
                                @if($book->subtitle)
                                <p class="product-card__subtitle">{{ $book->subtitle }}</p>
                                @endif
                                @if($book->short_description)
                                <p class="product-card__text">{{ Str::limit($book->short_description, 100) }}</p>
                                @endif
                                <div class="product-card__bottom">
                                    @if($book->price)
                                    <span class="product-card__price">£{{ number_format($book->price, 2) }}</span>
                                    @endif
                                    <div class="product-card__format">
                                        <span class="badge badge-{{ $book->format == 'ebook' ? 'info' : 'secondary' }}">
                                            {{ ucfirst($book->format) }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('books.show', $book->slug) }}" class="product-card__link citylife-btn">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">View Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($books->hasPages())
                <div class="pagination-wrapper text-center mt-5">
                    {{ $books->links() }}
                </div>
                @endif
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="empty-state text-center py-5">
                            <div class="empty-state__icon mb-4">
                                <span class="icon-book-open" style="font-size: 72px; color: var(--citylife-base);"></span>
                            </div>
                            <h3>No Books Available Yet</h3>
                            <p class="text-muted">We're currently building our library of publications. Check back soon!</p>
                            <a href="{{ route('home') }}" class="citylife-btn mt-4">
                                <span class="citylife-btn__icon-box">
                                    <span class="citylife-btn__icon-box__inner"><span class="icon-home"></span></span>
                                </span>
                                <span class="citylife-btn__text">Return Home</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Featured Authors Section --}}
    @if($featuredAuthors->count() > 0)
    <section class="team-one section-space" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">OUR AUTHORS</h6>
                <h3 class="sec-title__title">Meet Our <span class="sec-title__title__inner">Published Authors</span></h3>
            </div>
            <div class="row gutter-y-30">
                @foreach($featuredAuthors as $author)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="team-card wow fadeInUp" data-wow-delay="{{ $loop->index * 100 }}ms" data-wow-duration="1500ms">
                        <div class="team-card__image">
                            <img src="{{ $author->photo ? Storage::disk('s3')->url($author->photo) : asset('assets/images/team/team-1-1.png') }}" alt="{{ $author->full_name }}">
                            <div class="team-card__hover">
                                <a href="{{ route('team.show', $author->slug) }}">
                                    <span class="icon-right-arrow"></span>
                                </a>
                            </div>
                        </div>
                        <div class="team-card__content">
                            <h3 class="team-card__title">
                                <a href="{{ route('team.show', $author->slug) }}">{{ $author->full_name }}</a>
                            </h3>
                            <p class="team-card__designation">{{ $author->position }}</p>
                            @if($author->books_count)
                            <p class="team-card__books">
                                <span class="icon-book-open"></span> {{ $author->books_count }} {{ Str::plural('Book', $author->books_count) }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @push('styles')
    <style>
        .product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .product-card__image {
            position: relative;
            overflow: hidden;
            height: 350px;
            background: #f5f5f5;
        }
        .product-card__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-card__image img {
            transform: scale(1.05);
        }
        .product-card__sale {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--citylife-base);
            color: #fff;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .product-card__quick-view {
            position: absolute;
            bottom: 20px;
            right: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .product-card:hover .product-card__quick-view {
            opacity: 1;
        }
        .product-card__quick-view__icon {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--citylife-base);
            font-size: 20px;
            transition: all 0.3s ease;
        }
        .product-card__quick-view__icon:hover {
            background: var(--citylife-base);
            color: #fff;
        }
        .product-card__content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card__content__top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .product-card__category a {
            color: var(--citylife-base);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .product-card__author {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #666;
        }
        .product-card__author a {
            color: #666;
        }
        .product-card__author a:hover {
            color: var(--citylife-base);
        }
        .product-card__title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .product-card__title a {
            color: #222;
        }
        .product-card__title a:hover {
            color: var(--citylife-base);
        }
        .product-card__subtitle {
            font-size: 14px;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }
        .product-card__text {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        .product-card__bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .product-card__price {
            font-size: 24px;
            font-weight: 700;
            color: var(--citylife-base);
        }
        .product-card__format .badge {
            font-size: 12px;
            padding: 5px 12px;
            text-transform: uppercase;
        }
        .product-card__link {
            width: 100%;
            justify-content: center;
        }
        .empty-state {
            padding: 60px 20px;
        }
        .empty-state__icon {
            opacity: 0.3;
        }
        .team-card__books {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }
    </style>
    @endpush
</x-app-layout>
