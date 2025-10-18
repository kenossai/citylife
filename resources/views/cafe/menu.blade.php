<x-app-layout>
    @section('title', ($settings['cafe_name'] ?? 'CityLife Cafe') . ' - Menu')

    @section('meta_description', $settings['cafe_description'] ?? 'Enjoy great food and fellowship at our church cafe')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $settings['cafe_name'] ?? 'CityLife Cafe' }}</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><span>Cafe Menu</span></li>
            </ul>
        </div>
    </section>

    <!-- Cafe Hero Section -->
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
                            <h6 class="sec-title__tagline">WELCOME TO</h6>
                            <h3 class="sec-title__title">{{ $settings['cafe_name'] ?? 'CityLife Cafe' }} <span class="sec-title__title__inner">Menu</span></h3>
                        </div>
                        <div class="about-one__text-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <p class="about-one__text">{{ $settings['cafe_description'] ?? 'A warm and welcoming place to enjoy great food and fellowship' }}</p>
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

    <!-- Opening Hours Section -->
    <section class="pricing-one section-space" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">OPENING HOURS</h6>
                <h3 class="sec-title__title">Visit Us <span class="sec-title__title__inner">Anytime</span></h3>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="pricing-one__content">
                        <div class="row gutter-y-30">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div class="col-md-4 col-sm-6">
                                    <div class="pricing-one__item {{ $day === strtolower(now()->format('l')) ? 'pricing-one__item--active' : '' }}">
                                        <div class="pricing-one__item__inner">
                                            <h4 class="pricing-one__item__title">{{ ucfirst($day) }}</h4>
                                            <div class="pricing-one__item__price">
                                                <span class="pricing-one__item__price__amount">{{ $settings["opening_hours_{$day}"] ?? 'Closed' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Categories Section -->
    @if($categories->count() > 0)
    <section class="services-page section-space">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">OUR DELICIOUS</h6>
                <h3 class="sec-title__title">Cafe <span class="sec-title__title__inner">Menu</span></h3>
                <p class="sec-title__text">From freshly brewed coffee to delicious meals, we have something for everyone.</p>
            </div>

            <div class="row gutter-y-30">
                @foreach($categories as $category)
                    @if($category->products->count() > 0)
                        <div class="col-12">
                            <!-- Category Header -->
                            <div class="services-page__single wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1500ms">
                                <div class="services-page__single__content">
                                    <div class="services-page__single__content__inner">
                                        <div class="services-page__single__content__left">
                                            <h3 class="services-page__single__title">
                                                <a href="{{ route('cafe.category', $category->slug) }}">{{ $category->name }}</a>
                                            </h3>
                                            @if($category->description)
                                                <p class="services-page__single__text">{{ $category->description }}</p>
                                            @endif
                                        </div>
                                        <div class="services-page__single__content__right">
                                            <a href="{{ route('cafe.category', $category->slug) }}" class="services-page__single__btn citylife-btn">
                                                <div class="citylife-btn__icon-box">
                                                    <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                                </div>
                                                <span class="citylife-btn__text">View All</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div class="row gutter-y-30 mt-4">
                                @foreach($category->products->take(6) as $product)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="services-card wow fadeInUp" data-wow-delay="{{ 100 + ($loop->index * 100) }}ms" data-wow-duration="1500ms">
                                            <div class="services-card__content">
                                                @if($product->image)
                                                    <div class="services-card__image">
                                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
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
                                                        <p class="services-card__text">{{ Str::limit($product->description, 80) }}</p>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($category->products->count() > 6)
                                <div class="text-center mt-4">
                                    <a href="{{ route('cafe.category', $category->slug) }}" class="citylife-btn">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">View All {{ $category->name }} ({{ $category->products->count() }} items)</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="error-page section-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-page__content text-center">
                        <h2 class="error-page__title">Menu Coming Soon</h2>
                        <p class="error-page__text">Our delicious menu is being prepared. Please check back soon!</p>
                        <a href="{{ route('home') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Back to Home</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Information Section -->
    <section class="contact-information section-space" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">VISIT US</h6>
                <h3 class="sec-title__title">Cafe <span class="sec-title__title__inner">Information</span></h3>
            </div>

            <div class="row gutter-y-30">
                @if(isset($settings['cafe_phone']))
                <div class="col-lg-4 col-md-6">
                    <div class="contact-information__item text-center">
                        <div class="contact-information__item__icon">
                            <span class="icon-phone"></span>
                        </div>
                        <div class="contact-information__item__content">
                            <h4 class="contact-information__item__title">Phone</h4>
                            <p class="contact-information__item__text">
                                <a href="tel:{{ str_replace(' ', '', $settings['cafe_phone']) }}">{{ $settings['cafe_phone'] }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($settings['cafe_email']))
                <div class="col-lg-4 col-md-6">
                    <div class="contact-information__item text-center">
                        <div class="contact-information__item__icon">
                            <span class="icon-email"></span>
                        </div>
                        <div class="contact-information__item__content">
                            <h4 class="contact-information__item__title">Email</h4>
                            <p class="contact-information__item__text">
                                <a href="mailto:{{ $settings['cafe_email'] }}">{{ $settings['cafe_email'] }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-4 col-md-6">
                    <div class="contact-information__item text-center">
                        <div class="contact-information__item__icon">
                            <span class="icon-credit-card"></span>
                        </div>
                        <div class="contact-information__item__content">
                            <h4 class="contact-information__item__title">Payment Methods</h4>
                            <div class="contact-information__item__payment">
                                @if(($settings['accept_cash'] ?? 'false') === 'true')
                                    <span class="payment-badge payment-badge--cash">Cash</span>
                                @endif
                                @if(($settings['accept_card'] ?? 'false') === 'true')
                                    <span class="payment-badge payment-badge--card">Card</span>
                                    @if(isset($settings['minimum_card_amount']))
                                        <p class="payment-minimum">(Min £{{ number_format($settings['minimum_card_amount'], 2) }})</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
            <div class="text-center mt-5">
                <a href="{{ route('cafe.order.create') }}" class="citylife-btn citylife-btn--base">
                    <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-cart"></span></div>
                    </div>
                    <span class="citylife-btn__text">Start Your Order</span>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Custom Styles for Cafe Menu -->
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

        .payment-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0.125rem;
        }

        .payment-badge--cash {
            background-color: #dcfce7;
            color: #166534;
        }

        .payment-badge--card {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .payment-minimum {
            margin-top: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .contact-information__item__payment {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pricing-one__item--active {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
        }

        .pricing-one__item--active .pricing-one__item__title,
        .pricing-one__item--active .pricing-one__item__price__amount {
            color: white;
        }
    </style>

</x-app-layout>
