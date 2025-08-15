<x-app-layout>
    @section('title', 'Ministries - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Our Ministries</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Ministries</span></li>
            </ul>
        </div>
    </section>

    <!-- Ministries Start -->
    <section class="causes-page section-space">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-title text-center">
                        <h6 class="section-title__tagline">Get Involved</h6>
                        <h2 class="section-title__title">Connect Through Ministry</h2>
                        <p class="section-title__text">
                            Discover your calling and make a difference in our community and beyond. 
                            Join one of our vibrant ministries and grow in faith while serving others.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row gutter-y-30">
                @forelse($ministries as $ministry)
                <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                    <div class="causes-card">
                        <div class="causes-card__image">
                            @if($ministry->featured_image)
                                <img src="{{ Storage::url($ministry->featured_image) }}" alt="{{ $ministry->name }}">
                            @else
                                <img src="{{ asset('assets/images/ministry/default-ministry.jpg') }}" alt="{{ $ministry->name }}">
                            @endif
                            <div class="causes-card__category">{{ $ministry->name }}</div>
                        </div>
                        <div class="causes-card__content">
                            <h3 class="causes-card__title">
                                <a href="{{ route('ministries.show', $ministry->slug) }}">{{ $ministry->name }}</a>
                            </h3>
                            <p class="causes-card__text">{{ Str::limit($ministry->description, 120) }}</p>
                            
                            <div class="causes-card__info">
                                @if($ministry->leader)
                                <div class="causes-card__info-item">
                                    <i class="icon-user"></i>
                                    <span>Led by {{ $ministry->leader }}</span>
                                </div>
                                @endif
                                
                                @if($ministry->meeting_time)
                                <div class="causes-card__info-item">
                                    <i class="icon-clock"></i>
                                    <span>{{ $ministry->meeting_time }}</span>
                                </div>
                                @endif
                                
                                @if($ministry->meeting_location)
                                <div class="causes-card__info-item">
                                    <i class="icon-location"></i>
                                    <span>{{ $ministry->meeting_location }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="causes-card__bottom">
                                <a href="{{ route('ministries.show', $ministry->slug) }}" class="cleenhearts-btn">
                                    <div class="cleenhearts-btn__icon-box">
                                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="cleenhearts-btn__text">Learn More</span>
                                </a>
                                
                                @if($ministry->contact_email)
                                <a href="{{ route('ministries.contact', $ministry->slug) }}" class="causes-card__btn">
                                    <i class="icon-heart"></i>Get Involved
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center">
                        <h3>No Ministries Available</h3>
                        <p>Please check back later for ministry opportunities.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Ministries End -->

    <!-- Call to Action -->
    <section class="cta-one cta-one--page">
        <div class="container">
            <div class="cta-one__inner">
                <h3 class="cta-one__title">Ready to Get Involved?</h3>
                <p class="cta-one__text">Contact our ministry coordinator to find the perfect ministry for you.</p>
                <a href="{{ route('contact') }}" class="cleenhearts-btn">
                    <div class="cleenhearts-btn__icon-box">
                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                    </div>
                    <span class="cleenhearts-btn__text">Contact Us</span>
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
