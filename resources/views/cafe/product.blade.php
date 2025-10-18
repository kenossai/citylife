<x-app-layout>
    @section('title', $product->name . ' - ' . ($settings['cafe_name'] ?? 'CityLife Cafe'))

    @section('meta_description', $product->description ?? 'View details for ' . $product->name)

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $product->name }}</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('cafe.menu') }}">Cafe Menu</a></li>
                <li><a href="{{ route('cafe.category', $category->slug) }}">{{ $category->name }}</a></li>
                <li><span>{{ $product->name }}</span></li>
            </ul>
        </div>
    </section>

    <!-- Product Details -->
    <section class="product-details section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-lg-6">
                    <div class="product-details__image">
                        <div class="product-details__image__main">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" id="main-product-image">
                            @else
                                <div class="product-details__image__placeholder">
                                    <span class="icon-coffee"></span>
                                    <p>{{ $product->name }}</p>
                                </div>
                            @endif
                        </div>

                        @if($product->gallery && count($product->gallery) > 0)
                        <div class="product-details__image__thumb">
                            @foreach($product->gallery as $galleryImage)
                                <img src="{{ Storage::url($galleryImage) }}"
                                     alt="{{ $product->name }}"
                                     onclick="changeMainImage('{{ Storage::url($galleryImage) }}')"
                                     class="product-thumb-item">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-details__content">
                        <h3 class="product-details__title">{{ $product->name }}</h3>

                        <div class="product-details__price">
                            <span class="product-details__price__amount">£{{ number_format($product->price, 2) }}</span>
                        </div>

                        @if($product->description)
                        <div class="product-details__text">
                            <p>{{ $product->description }}</p>
                        </div>
                        @endif

                        <!-- Product Meta Information -->
                        <div class="product-details__meta">
                            @if($product->size || $product->temperature || $product->preparation_time)
                            <div class="product-details__meta__item">
                                <span class="product-details__meta__label">Details:</span>
                                <div class="product-details__meta__content">
                                    @if($product->size)
                                        <span class="product-meta-tag">Size: {{ ucfirst($product->size) }}</span>
                                    @endif
                                    @if($product->temperature)
                                        <span class="product-meta-tag">{{ ucfirst(str_replace('_', ' ', $product->temperature)) }}</span>
                                    @endif
                                    @if($product->preparation_time)
                                        <span class="product-meta-tag">{{ $product->preparation_time }} minutes</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($product->dietary_info && count($product->dietary_info) > 0)
                            <div class="product-details__meta__item">
                                <span class="product-details__meta__label">Dietary:</span>
                                <div class="product-details__meta__content">
                                    @foreach($product->dietary_info as $diet)
                                        <span class="product-dietary-tag">{{ ucfirst(str_replace('_', ' ', $diet)) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($product->ingredients)
                            <div class="product-details__meta__item">
                                <span class="product-details__meta__label">Ingredients:</span>
                                <div class="product-details__meta__content">
                                    <span class="product-ingredients">{{ $product->ingredients }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="product-details__buttons">
                            @if(($settings['allow_online_ordering'] ?? 'false') === 'true')
                            <a href="{{ route('cafe.order.create') }}" class="citylife-btn citylife-btn--base">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-cart"></span></div>
                                </div>
                                <span class="citylife-btn__text">Add to Order</span>
                            </a>
                            @endif

                            <a href="{{ route('cafe.category', $category->slug) }}" class="citylife-btn citylife-btn--border">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="citylife-btn__text">Back to {{ $category->name }}</span>
                            </a>
                        </div>

                        <!-- Nutritional Information (if available) -->
                        @if($product->nutritional_info && count($product->nutritional_info) > 0)
                        <div class="product-details__nutrition">
                            <h4 class="product-details__nutrition__title">Nutritional Information</h4>
                            <div class="product-details__nutrition__content">
                                @foreach($product->nutritional_info as $nutrition)
                                <div class="nutrition-item">
                                    <span class="nutrition-label">{{ $nutrition['nutrient'] }}:</span>
                                    <span class="nutrition-value">{{ $nutrition['value'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="products-one section-space" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="sec-title text-center">
                <h6 class="sec-title__tagline">MORE FROM</h6>
                <h3 class="sec-title__title">{{ $category->name }} <span class="sec-title__title__inner">Collection</span></h3>
            </div>

            <div class="row gutter-y-30">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 100 + ($loop->index * 100) }}ms" data-wow-duration="1500ms">
                    <div class="product-card">
                        <div class="product-card__image">
                            @if($relatedProduct->image)
                                <img src="{{ Storage::url($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                            @else
                                <div class="product-card__image__placeholder">
                                    <span class="icon-coffee"></span>
                                </div>
                            @endif
                        </div>

                        <div class="product-card__content">
                            <h3 class="product-card__title">
                                <a href="{{ route('cafe.product', [$category->slug, $relatedProduct->slug]) }}">{{ $relatedProduct->name }}</a>
                            </h3>

                            @if($relatedProduct->description)
                            <p class="product-card__text">{{ Str::limit($relatedProduct->description, 80) }}</p>
                            @endif

                            <div class="product-card__price">
                                <span class="product-card__price__amount">£{{ number_format($relatedProduct->price, 2) }}</span>
                            </div>

                            <div class="product-card__btn">
                                <a href="{{ route('cafe.product', [$category->slug, $relatedProduct->slug]) }}" class="citylife-btn citylife-btn--border citylife-btn--sm">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">View Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Custom Styles for Product Details Page -->
    <style>
        /* Product Details Styles */
        .product-details {
            background-color: #ffffff;
        }

        .product-details__image__main {
            position: relative;
            margin-bottom: 20px;
        }

        .product-details__image__main img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-details__image__placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }

        .product-details__image__placeholder span {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .product-details__image__thumb {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .product-thumb-item {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: opacity 0.3s ease;
            flex-shrink: 0;
        }

        .product-thumb-item:hover {
            opacity: 0.7;
        }

        .product-details__title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .product-details__price {
            margin-bottom: 1.5rem;
            background-color: transparent !important
        }

        .product-details__price__amount {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }

        .product-details__text {
            margin-bottom: 2rem;
        }

        .product-details__text p {
            font-size: 1.125rem;
            line-height: 1.6;
            color: #6b7280;
        }

        .product-details__meta {
            margin-bottom: 2rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 2rem;
        }

        .product-details__meta__item {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .product-details__meta__label {
            font-weight: 600;
            color: #374151;
            min-width: 100px;
            flex-shrink: 0;
        }

        .product-details__meta__content {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .product-meta-tag {
            background-color: #f3f4f6;
            color: #374151;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .product-dietary-tag {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .product-ingredients {
            background-color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #7c3aed;
            color: #6b7280;
            line-height: 1.6;
            width: 100%;
        }

        .product-details__buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .product-details__buttons .citylife-btn {
            flex: 1;
            min-width: 200px;
        }

        .product-details__nutrition {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .product-details__nutrition__title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }

        .product-details__nutrition__content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .nutrition-item {
            display: flex;
            justify-content: space-between;
            background-color: #ffffff;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .nutrition-label {
            color: #6b7280;
            font-weight: 500;
        }

        .nutrition-value {
            font-weight: 600;
            color: #374151;
        }

        /* Product Card Styles for Related Products */
        .product-card {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }

        .product-card__image {
            position: relative;
            height: 200px;
            overflow: hidden;
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

        .product-card__image__placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }

        .product-card__image__placeholder span {
            font-size: 2rem;
        }

        .product-card__content {
            padding: 1.5rem;
        }

        .product-card__title {
            margin-bottom: 0.75rem;
        }

        .product-card__title a {
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
            text-decoration: none;
            line-height: 1.2;
        }

        .product-card__title a:hover {
            color: #7c3aed;
        }

        .product-card__text {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .product-card__price {
            margin-bottom: 1.5rem;
        }

        .product-card__price__amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .product-card__btn {
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-details__title {
                font-size: 2rem;
            }

            .product-details__price__amount {
                font-size: 1.5rem;
            }

            .product-details__buttons {
                flex-direction: column;
            }

            .product-details__buttons .citylife-btn {
                width: 100%;
                min-width: auto;
            }

            .product-details__meta__item {
                flex-direction: column;
                gap: 0.5rem;
            }

            .product-details__meta__label {
                min-width: auto;
            }
        }
    </style>

    <script>
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('main-product-image');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
    }
    </script>

</x-app-layout>
