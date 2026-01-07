<x-app-layout>
    @section('title', $book->meta_title ?: $book->title . ' - CityLife Church')
    @section('description', $book->meta_description ?: $book->short_description)
    @section('keywords', $book->meta_keywords ? implode(', ', $book->meta_keywords) : '')

    {{-- Page Header --}}
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <!-- /.page-header__bg -->
        <div class="container">
            <h2 class="page-header__title">Books & Publications</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Books & Publications</span></li>
            </ul><!-- /.thm-breadcrumb list-unstyled -->
        </div><!-- /.container -->
    </section>

    <section class="product-details section-space">
        <div class="container">
            <!-- /.product-details -->
            <div class="row gutter-y-40">
                <div class="col-lg-6 col-xl-6 wow fadeInLeft animated" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fadeInLeft;">
                    <div class="product-details__img">
                        <div class="swiper product-details__gallery-top swiper-initialized swiper-horizontal swiper-backface-hidden">
                            <div class="swiper-wrapper" id="swiper-wrapper-75f137845266299e" aria-live="off" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px); transition-delay: 0ms;">
                                <div class="swiper-slide swiper-slide-active" style="width: 570px;" role="group" aria-label="1 / 3" data-swiper-slide-index="0">
                                    <img src="{{ $book->cover_image ? Storage::disk('s3')->url($book->cover_image) : asset('assets/images/products/product-1-1.png') }}" alt="product details image" class="product-details__gallery-top__img">
                                </div>
                            </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                        <div class="swiper product-details__gallery-thumb swiper-initialized swiper-horizontal swiper-watch-progress swiper-backface-hidden swiper-thumbs">
                            <div class="swiper-wrapper" id="swiper-wrapper-a6774edf74c9306d" aria-live="off" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms; transition-delay: 0ms;">
                            </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                    </div>
                </div><!-- /.column -->
                <div class="col-lg-6 col-xl-6 wow fadeInRight animated" data-wow-delay="300ms" style="visibility: visible; animation-delay: 300ms; animation-name: fadeInRight;">
                    <div class="product-details__content">
                        <div class="product-details__top">
                            <div class="product-details__top__left">
                                <h3 class="product-details__name">{{ $book->title }}</h3><!-- /.product-title -->
                                <h4 class="product-details__price">£{{ number_format($book->price, 2) }}</h4><!-- /.product-price -->
                            </div><!-- /.product-details__price -->
                            <a href="https://www.youtube.com/watch?v=h9MbznbxlLc" class="product-details__video video-button video-popup">
                                <span class="icon-play"></span>
                                <i class="video-button__ripple"></i>
                            </a><!-- /.video-button -->
                        </div>
                        <div class="product-details__review">
                            <div class="citylife-ratings @@extraClassName">
                                <span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span>
                            </div><!-- /.product-ratings -->
                            <a href="product-details.html">(2 customer reviews)</a>
                        </div><!-- /.review-ratings -->
                        <div class="product-details__excerpt">
                            <p class="product-details__excerpt__text1">
                                {{ $book->short_description }}
                            </p>
                        </div><!-- /.excerp-text -->
                        <div class="product-details__color">
                            @if($book->teamMember)
                            <h3 class="product-details__content__title">Author: {{ $book->teamMember->full_name }}</h3>
                            @endif
                        </div><!-- /.product-details__color -->
                        <div class="product-details__size">
                            @if($book->publisher)
                            <h3 class="product-details__content__title">Publisher: {{ $book->publisher }}</h3>
                            @endif
                            @if($book->published_date)
                            <h3 class="product-details__content__title">Published: {{ $book->published_date->format('F Y') }}</h3>
                            @endif
                            @if($book->edition)
                            <h3 class="product-details__content__title">Edition: {{ $book->edition }}</h3>
                            @endif
                        </div><!-- /.product-details__size -->
                        <div class="product-details__info">
                            <div class="product-details__quantity">
                                <h3 class="product-details__content__title">Quantity</h3>
                                <div class="quantity-box">
                                    <button type="button" class="sub"><i class="fa fa-minus"></i></button>
                                    <input type="text" id="1" value="1">
                                    <button type="button" class="add"><i class="fa fa-plus"></i></button>
                                </div>
                            </div><!-- /.quantity -->
                            <div class="product-details__socials">
                                <h3 class="product-details__content__title">share:</h3>
                                <div class="social-link">
                                    <a href="{{ url()->current() }}" aria-hidden="true">
                                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                        <span class="sr-only">Facebook</span>
                                    </a>
                                    <a href="{{ url()->current() }}">
                                        <i class="fab fa-twitter" aria-hidden="true"></i>
                                        <span class="sr-only">Twitter</span>
                                    </a>
                                </div><!-- /.social-link -->
                            </div><!-- /.product-details__socials -->
                        </div><!-- /.product-details__info -->
                        <div class="product-details__buttons">
                            <a href="{{ $book->amazon_link }}" class="product-details__btn-cart citylife-btn citylife-btn--border" target="_blank" rel="noopener noreferrer">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-trolley"></span></div>
                                </div>
                                <span class="citylife-btn__text">Buy on Amazon</span>
                            </a>
                            <a href="{{ $book->purchase_link }}" class="product-details__btn-wishlist citylife-btn citylife-btn--border" target="_blank" rel="noopener noreferrer">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-book"></span></div>
                                </div>
                                <span class="citylife-btn__text">Purchase</span>
                            </a>
                        </div><!-- /.qty-btn -->
                    </div>
                </div>
            </div>
            <!-- /.product-details -->
        </div>
        <div class="product-details__description-wrapper">
            <div class="container">
                <!-- /.product-description -->
                @if($book->description)
                <div class="product-details__description">
                    <h3 class="product-details__description__title">About This Book</h3>
                    <div class="product-details__text__box wow fadeInUp animated" data-wow-delay="300ms" style="visibility: visible; animation-delay: 300ms; animation-name: fadeInUp;">
                        <p class="product-details__description__text">
                            {!! $book->description !!}
                        </p>

                    </div><!-- /.product-details__text__box -->
                </div>
                @endif
                <!-- /.product-description -->
            </div><!-- /.container -->
        </div><!-- /.product-details__description__wrapper -->

        <div class="container">
            <!-- /.product-comment -->
            @if($book->teamMember)
                <div class="product-details__comment comments-one">
                    <h3 class="product-details__comment__title comments-one__title sec-title__title">About Author</h3><!-- /.comments-one__title -->
                    <ul class="list-unstyled comments-one__list">
                        <li class="comments-one__card wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                            <div class="comments-one__card__image">
                                <img src="{{ $book->teamMember->photo ? Storage::disk('s3')->url($book->teamMember->photo) : asset('assets/images/team/team-1-1.png') }}" alt="Kevin martin">
                            </div><!-- /.comments-one__card__image -->
                            <div class="comments-one__card__content">
                                <div class="comments-one__card__top">
                                    <div class="comments-one__card__info">
                                        <h3 class="comments-one__card__title">{{ $book->teamMember->full_name }}</h3><!-- /.comments-one__card__title -->
                                        <p class="comments-one__card__date">{{ $book->teamMember->position }}</p><!-- /.comments-one__card__date -->
                                    </div><!-- /.comments-one__card__info -->
                                </div><!-- /.comments-one__card__top -->
                                @if($book->teamMember->bio)
                                    <p class="comments-one__card__text">
                                        {{ $book->teamMember->bio }}
                                    </p>
                                @endif
                            </div><!-- /.comments-one__card__content -->
                        </li><!-- /.comments-one__card -->
                        <li class="comments-one__card wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                            <div class="comments-one__card__image">
                                <img src="assets/images/blog/blog-comment-1-2.png" alt="Sarah albert">
                            </div><!-- /.comments-one__card__image -->
                        </li><!-- /.comments-one__card -->
                    </ul><!-- /.list-unstyled comments-one__list -->
                </div><!-- /.product-details__comment comments-one -->
            @endif
            <!-- /.product-comment -->
        </div><!-- /.container -->

    </section>
    {{-- Book Details Section --}}

    <section class="product-details section-space">
        <div class="container">
            <div class="row gutter-y-50">
                {{-- Book Image --}}
                <div class="col-lg-5">
                    <div class="product-details__image">
                        <img src="{{ $book->cover_image ? Storage::disk('s3')->url($book->cover_image) : asset('assets/images/products/product-d-1.png') }}"
                             alt="{{ $book->title }}"
                             class="img-fluid">
                        @if($book->back_cover_image)
                        <div class="mt-3">
                            <img src="{{ Storage::disk('s3')->url($book->back_cover_image) }}"
                                 alt="{{ $book->title }} - Back Cover"
                                 class="img-fluid">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Book Info --}}
                <div class="col-lg-7">
                    <div class="product-details__content">
                        @if($book->category)
                        <div class="product-details__category mb-3">
                            <span class="badge badge-primary">{{ $book->category }}</span>
                            @if($book->is_featured)
                            <span class="badge badge-warning ml-2">Featured</span>
                            @endif
                        </div>
                        @endif

                        <h3 class="product-details__title">{{ $book->title }}</h3>

                        @if($book->subtitle)
                        <h5 class="product-details__subtitle text-muted">{{ $book->subtitle }}</h5>
                        @endif

                        @if($book->teamMember)
                        <div class="product-details__author mb-3">
                            <span class="icon-user"></span>
                            <span>by </span>
                            <a href="{{ route('team.member', $book->teamMember->slug) }}" class="font-weight-bold">
                                {{ $book->teamMember->full_name }}
                            </a>
                        </div>
                        @endif

                        @if($book->price)
                        <div class="product-details__price">
                            <span class="amount">£{{ number_format($book->price, 2) }}</span>
                            @if($book->currency != 'GBP')
                            <span class="currency-note text-muted ml-2">({{ $book->currency }})</span>
                            @endif
                        </div>
                        @endif

                        @if($book->short_description)
                        <div class="product-details__short-description">
                            <p>{{ $book->short_description }}</p>
                        </div>
                        @endif

                        {{-- Book Meta Information --}}
                        <div class="product-details__meta">
                            <table class="table table-borderless">
                                @if($book->publisher)
                                <tr>
                                    <th width="150">Publisher:</th>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                                @endif
                                @if($book->published_date)
                                <tr>
                                    <th>Published:</th>
                                    <td>{{ $book->published_date->format('F Y') }}</td>
                                </tr>
                                @endif
                                @if($book->edition)
                                <tr>
                                    <th>Edition:</th>
                                    <td>{{ $book->edition }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Format:</th>
                                    <td>
                                        <span class="badge badge-{{ $book->format == 'ebook' ? 'info' : 'secondary' }}">
                                            {{ ucfirst($book->format) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($book->pages)
                                <tr>
                                    <th>Pages:</th>
                                    <td>{{ $book->pages }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Language:</th>
                                    <td>{{ $book->language }}</td>
                                </tr>
                                @if($book->isbn)
                                <tr>
                                    <th>ISBN:</th>
                                    <td>{{ $book->isbn }}</td>
                                </tr>
                                @endif
                                @if($book->isbn13)
                                <tr>
                                    <th>ISBN-13:</th>
                                    <td>{{ $book->isbn13 }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        {{-- Purchase Links --}}
                        <div class="product-details__links mt-4">
                            @if($book->purchase_link)
                            <a href="{{ $book->purchase_link }}" target="_blank" class="citylife-btn mb-2 mr-2">
                                <span class="citylife-btn__icon-box">
                                    <span class="citylife-btn__icon-box__inner"><span class="icon-shopping-cart"></span></span>
                                </span>
                                <span class="citylife-btn__text">Purchase Book</span>
                            </a>
                            @endif
                            @if($book->amazon_link)
                            <a href="{{ $book->amazon_link }}" target="_blank" class="citylife-btn citylife-btn--border mb-2 mr-2">
                                <span class="citylife-btn__icon-box">
                                    <span class="citylife-btn__icon-box__inner"><span class="icon-shopping-cart"></span></span>
                                </span>
                                <span class="citylife-btn__text">Buy on Amazon</span>
                            </a>
                            @endif
                            @if($book->preview_link)
                            <a href="{{ $book->preview_link }}" target="_blank" class="citylife-btn citylife-btn--border mb-2">
                                <span class="citylife-btn__icon-box">
                                    <span class="citylife-btn__icon-box__inner"><span class="icon-eye"></span></span>
                                </span>
                                <span class="citylife-btn__text">Preview</span>
                            </a>
                            @endif
                        </div>

                        {{-- Tags --}}
                        @if($book->tags || $book->topics)
                        <div class="product-details__tags mt-4">
                            @if($book->tags)
                            <div class="mb-2">
                                <strong>Tags:</strong>
                                @foreach($book->tags as $tag)
                                <span class="badge badge-light">{{ $tag }}</span>
                                @endforeach
                            </div>
                            @endif
                            @if($book->topics)
                            <div>
                                <strong>Topics:</strong>
                                @foreach($book->topics as $topic)
                                <span class="badge badge-light">{{ $topic }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Full Description --}}
            @if($book->description)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-details__description">
                        <h3 class="mb-4">About This Book</h3>
                        <div class="content">
                            {!! $book->description !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Sample Pages --}}
            @if($book->sample_pages && count($book->sample_pages) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">Sample Pages</h3>
                    <div class="sample-pages-gallery">
                        <div class="row gutter-y-30">
                            @foreach($book->sample_pages as $sample)
                            <div class="col-md-4">
                                <a href="{{ Storage::disk('s3')->url($sample) }}" class="sample-page-link" data-fancybox="sample-pages">
                                    <img src="{{ Storage::disk('s3')->url($sample) }}" alt="Sample Page" class="img-fluid">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- About the Author --}}
            @if($book->teamMember)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="author-bio">
                        <h3 class="mb-4">About the Author</h3>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ $book->teamMember->photo ? Storage::disk('s3')->url($book->teamMember->photo) : asset('assets/images/team/team-1-1.png') }}"
                                     alt="{{ $book->teamMember->full_name }}"
                                     class="img-fluid rounded">
                            </div>
                            <div class="col-md-9">
                                <h4>{{ $book->teamMember->full_name }}</h4>
                                <p class="text-muted">{{ $book->teamMember->position }}</p>
                                @if($book->teamMember->bio)
                                <p>{{ $book->teamMember->bio }}</p>
                                @endif
                                <a href="{{ route('team.member', $book->teamMember->slug) }}" class="citylife-btn citylife-btn--border mt-3">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-user"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">View Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- Related Books --}}
    @if($relatedBooks && $relatedBooks->count() > 0)
    <section class="related-products section-space-bottom" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">MORE BOOKS</h6>
                <h3 class="sec-title__title">You Might Also <span class="sec-title__title__inner">Enjoy</span></h3>
            </div>
            <div class="row gutter-y-30">
                @foreach($relatedBooks as $relatedBook)
                <div class="col-lg-4 col-md-6">
                    <div class="product-card wow fadeInUp" data-wow-delay="00ms">
                        <div class="product-card__image">
                            <img src="{{ $relatedBook->cover_image ? Storage::disk('s3')->url($relatedBook->cover_image) : asset('assets/images/products/product-1-1.png') }}" alt="{{ $relatedBook->title }}">
                            <div class="product-card__quick-view">
                                <a href="{{ route('books.show', $relatedBook->slug) }}" class="product-card__quick-view__icon">
                                    <span class="icon-eye"></span>
                                </a>
                            </div>
                        </div>
                        <div class="product-card__content">
                            <h3 class="product-card__title">
                                <a href="{{ route('books.show', $relatedBook->slug) }}">{{ $relatedBook->title }}</a>
                            </h3>
                            @if($relatedBook->price)
                            <span class="product-card__price">£{{ number_format($relatedBook->price, 2) }}</span>
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
        .product-details__image img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .product-details__category .badge {
            font-size: 14px;
            padding: 8px 15px;
        }
        .product-details__title {
            font-size: 36px;
            font-weight: 700;
            margin: 20px 0 10px;
            line-height: 1.3;
        }
        .product-details__subtitle {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .product-details__author {
            font-size: 18px;
            color: #666;
        }
        .product-details__price {
            margin: 30px 0;
        }
        .product-details__price .amount {
            font-size: 42px;
            font-weight: 700;
            color: var(--citylife-base);
        }
        .product-details__short-description {
            font-size: 16px;
            line-height: 1.8;
            margin: 20px 0;
            color: #666;
        }
        .product-details__meta {
            margin: 30px 0;
        }
        .product-details__meta table th {
            font-weight: 600;
            color: #333;
        }
        .product-details__links a {
            display: inline-block;
        }
        .product-details__description .content {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
        }
        .sample-page-link {
            display: block;
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        .sample-page-link:hover {
            transform: scale(1.05);
        }
        .sample-page-link img {
            width: 100%;
            height: auto;
        }
        .author-bio {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .author-bio img {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .product-card__image {
            position: relative;
            overflow: hidden;
            height: 350px;
        }
        .product-card__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
        }
        .product-card__content {
            padding: 25px;
        }
        .product-card__title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .product-card__title a {
            color: #222;
        }
        .product-card__title a:hover {
            color: var(--citylife-base);
        }
        .product-card__price {
            font-size: 20px;
            font-weight: 700;
            color: var(--citylife-base);
        }
    </style>
    @endpush
</x-app-layout>
