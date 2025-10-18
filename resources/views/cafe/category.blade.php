<x-app-layout>
    @section('title', $category->name . ' - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

    @section('meta_description', $category->description ?? 'Browse our ' . $category->name . ' menu')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $category->name }}</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('cafe.menu') }}">Cafe Menu</a></li>
                <li><span>{{ $category->name }}</span></li>
            </ul>
        </div>
    </section>

    <!-- Category Hero Section -->
    <section class="about-one section-space">
        <div class="about-one__bg">
            <div class="about-one__bg__border"></div>
            <div class="about-one__bg__inner" style="background-image: url('{{ asset('assets/images/backgrounds/worship-image.jpg') }}');"></div>
        </div>
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
                    <div class="about-one__left">
                        <div class="about-one__image">
                            <div class="about-one__video" style="background-image: url('{{ asset('assets/images/about/about-1-1.png') }}');">
                                @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                                    <a href="{{ route('cafe.order.create') }}" class="about-one__video__btn">
                                        <span class="icon-cart"></span>
                                        <i class="video-button__ripple"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-one__content">
                        <div class="sec-title">
                            <h6 class="sec-title__tagline">{{ strtoupper($category->name) }}</h6>
                            <h3 class="sec-title__title">Our <span class="sec-title__title__inner">{{ $category->name }}</span></h3>
                        </div>
                        <div class="about-one__text-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <p class="about-one__text">{{ $category->description ?? 'Discover our delicious ' . strtolower($category->name) . ' selection' }}</p>
                        </div>

                        @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                        <div class="contact-information">
                            <a href="{{ route('cafe.order.create') }}" class="contact-information__btn citylife-btn">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-cart"></span></div>
                                </div>
                                <span class="citylife-btn__text">Order Online</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('assets/images/shapes/about-shape-1-2.png') }}" alt="citylife" class="about-one__hand">
    </section>

    <!-- Products Section -->
    @if($category->products->count() > 0)
    <section class="services-page section-space">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">DELICIOUS</h6>
                <h3 class="sec-title__title">{{ $category->name }} <span class="sec-title__title__inner">Selection</span></h3>
                <p class="sec-title__text">{{ $category->products->count() }} delicious items to choose from</p>
            </div>

            <div class="row gutter-y-30">
                @foreach($category->products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="services-card wow fadeInUp" data-wow-delay="{{ 100 + ($loop->index * 100) }}ms" data-wow-duration="1500ms">
                            <div class="services-card__content">
                                @if($product->image)
                                    <div class="services-card__image">
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                    </div>
                                @else
                                    <div class="services-card__image services-card__image--placeholder">
                                        <div class="services-card__placeholder">
                                            <span class="icon-coffee"></span>
                                            <p>{{ $category->name }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="services-card__content__inner">
                                    <div class="services-card__title-box">
                                        <h3 class="services-card__title">
                                            <a href="{{ route('cafe.product', [$category->slug, $product->slug]) }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="services-card__price">£{{ number_format($product->price, 2) }}</div>
                                    </div>

                                    @if($product->description)
                                        <p class="services-card__text">{{ $product->description }}</p>
                                    @endif

                                    <!-- Product Details -->
                                    <div class="services-card__meta">
                                        @if($product->size)
                                            <span class="services-card__meta__item">{{ ucfirst($product->size) }}</span>
                                        @endif
                                        @if($product->temperature)
                                            <span class="services-card__meta__item">{{ ucfirst(str_replace('_', ' ', $product->temperature)) }}</span>
                                        @endif
                                        @if($product->preparation_time)
                                            <span class="services-card__meta__item">{{ $product->preparation_time }} min</span>
                                        @endif
                                    </div>

                                    <!-- Dietary Info -->
                                    @if($product->dietary_info && count($product->dietary_info) > 0)
                                        <div class="services-card__dietary">
                                            @foreach($product->dietary_info as $diet)
                                                <span class="services-card__dietary__tag">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($product->ingredients)
                                        <div class="services-card__ingredients">
                                            <h4 class="services-card__ingredients__title">Ingredients:</h4>
                                            <p class="services-card__ingredients__text">{{ $product->ingredients }}</p>
                                        </div>
                                    @endif

                                    <div class="services-card__btn">
                                        <a href="{{ route('cafe.product', [$category->slug, $product->slug]) }}" class="citylife-btn citylife-btn--base">
                                            <div class="citylife-btn__icon-box">
                                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                            </div>
                                            <span class="citylife-btn__text">View Details</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                <div class="text-center mt-5">
                    <a href="{{ route('cafe.order.create') }}" class="citylife-btn citylife-btn--base2">
                        <div class="citylife-btn__icon-box">
                            <div class="citylife-btn__icon-box__inner"><span class="icon-cart"></span></div>
                        </div>
                        <span class="citylife-btn__text">Start Your Order</span>
                    </a>
                </div>
            @endif
        </div>
    </section>
    @else
    <section class="error-page section-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-page__content text-center">
                        <h2 class="error-page__title">No Items Available</h2>
                        <p class="error-page__text">There are currently no items in this category. Please check back soon!</p>
                        <a href="{{ route('cafe.menu') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Back to Menu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Custom Styles for Cafe Category -->
    <style>
        .services-card__price {
            color: #7c3aed;
            font-weight: 700;
            font-size: 1.2rem;
            margin-left: auto;
        }

        .services-card__title-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .services-card__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .services-card__meta__item {
            background-color: #f3f4f6;
            color: #374151;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .services-card__dietary {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .services-card__dietary__tag {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .services-card__ingredients {
            margin-bottom: 1rem;
            padding: 0.75rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }

        .services-card__ingredients__title {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .services-card__ingredients__text {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .services-card__image--placeholder {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .services-card__placeholder {
            text-align: center;
            color: #9ca3af;
        }

        .services-card__placeholder span {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .services-card__placeholder p {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .services-card__btn {
            margin-top: 1rem;
        }
    </style>

</x-app-layout>
