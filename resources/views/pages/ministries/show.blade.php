<x-app-layout>
    @section('title', $ministry->name . ' - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">{{ $ministry->name }}</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('ministries.index') }}">Ministries</a></li>
                <li><span>{{ $ministry->name }}</span></li>
            </ul>
        </div>
    </section>

    <!-- Ministry Details Start -->
    <section class="causes-details section-space">
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-lg-8">
                    <div class="causes-details__content">
                        <div class="causes-details__image">
                            @if($ministry->featured_image)
                                <img src="{{ Storage::url($ministry->featured_image) }}" alt="{{ $ministry->name }}">
                            @else
                                <img src="{{ asset('assets/images/ministry/default-ministry.jpg') }}" alt="{{ $ministry->name }}">
                            @endif
                        </div>
                        
                        <h3 class="causes-details__title">{{ $ministry->name }}</h3>
                        <p class="causes-details__text">{{ $ministry->description }}</p>
                        
                        <div class="causes-details__content-text">
                            {!! $ministry->content !!}
                        </div>

                        @if($members->where('pivot.role', 'Leader')->count() > 0)
                        <div class="causes-details__leadership">
                            <h4>Leadership Team</h4>
                            <div class="row gutter-y-20">
                                @foreach($members->where('pivot.role', 'Leader') as $leader)
                                <div class="col-md-6">
                                    <div class="team-card-two">
                                        @if($leader->photo)
                                            <div class="team-card-two__image">
                                                <img src="{{ Storage::url($leader->photo) }}" alt="{{ $leader->first_name }} {{ $leader->last_name }}">
                                            </div>
                                        @endif
                                        <div class="team-card-two__content">
                                            <h3 class="team-card-two__title">{{ $leader->first_name }} {{ $leader->last_name }}</h3>
                                            <p class="team-card-two__designation">{{ $leader->pivot->role }}</p>
                                            @if($leader->email)
                                                <a href="mailto:{{ $leader->email }}" class="team-card-two__email">{{ $leader->email }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success mt-4">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Ministry Info -->
                        <div class="sidebar__single sidebar__info">
                            <h4 class="sidebar__title">Ministry Information</h4>
                            <ul class="sidebar__info-list">
                                @if($ministry->leader)
                                <li>
                                    <i class="icon-user"></i>
                                    <span class="sidebar__info-label">Leader:</span>
                                    <span class="sidebar__info-text">{{ $ministry->leader }}</span>
                                </li>
                                @endif
                                
                                @if($ministry->meeting_time)
                                <li>
                                    <i class="icon-clock"></i>
                                    <span class="sidebar__info-label">Meeting Time:</span>
                                    <span class="sidebar__info-text">{{ $ministry->meeting_time }}</span>
                                </li>
                                @endif
                                
                                @if($ministry->meeting_location)
                                <li>
                                    <i class="icon-location"></i>
                                    <span class="sidebar__info-label">Location:</span>
                                    <span class="sidebar__info-text">{{ $ministry->meeting_location }}</span>
                                </li>
                                @endif
                                
                                <li>
                                    <i class="icon-users"></i>
                                    <span class="sidebar__info-label">Active Members:</span>
                                    <span class="sidebar__info-text">{{ $members->count() }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Get Involved -->
                        @if($ministry->contact_email)
                        <div class="sidebar__single sidebar__cta">
                            <div class="sidebar__cta-bg" style="background-image: url('{{ asset('assets/images/backgrounds/sidebar-cta-bg.jpg') }}');"></div>
                            <h4 class="sidebar__cta-title">Get Involved</h4>
                            <p class="sidebar__cta-text">Interested in joining this ministry? Contact us to learn more.</p>
                            <a href="{{ route('ministries.contact', $ministry->slug) }}" class="cleenhearts-btn">
                                <div class="cleenhearts-btn__icon-box">
                                    <div class="cleenhearts-btn__icon-box__inner"><span class="icon-heart"></span></div>
                                </div>
                                <span class="cleenhearts-btn__text">Join Ministry</span>
                            </a>
                        </div>
                        @endif

                        <!-- Contact Info -->
                        @if($ministry->contact_email)
                        <div class="sidebar__single sidebar__contact">
                            <h4 class="sidebar__title">Contact Information</h4>
                            <div class="sidebar__contact-info">
                                <a href="mailto:{{ $ministry->contact_email }}">
                                    <i class="icon-email"></i>{{ $ministry->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Ministry Details End -->

    <!-- Related Ministries -->
    <section class="causes-one causes-one--page">
        <div class="container">
            <div class="section-title text-center">
                <h6 class="section-title__tagline">Explore More</h6>
                <h2 class="section-title__title">Other Ministries</h2>
            </div>
            
            @php
                $relatedMinistries = \App\Models\Ministry::active()
                    ->where('id', '!=', $ministry->id)
                    ->orderBy('sort_order')
                    ->take(3)
                    ->get();
            @endphp

            <div class="row gutter-y-30">
                @foreach($relatedMinistries as $relatedMinistry)
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="causes-card">
                        <div class="causes-card__image">
                            @if($relatedMinistry->featured_image)
                                <img src="{{ Storage::url($relatedMinistry->featured_image) }}" alt="{{ $relatedMinistry->name }}">
                            @else
                                <img src="{{ asset('assets/images/ministry/default-ministry.jpg') }}" alt="{{ $relatedMinistry->name }}">
                            @endif
                            <div class="causes-card__category">{{ $relatedMinistry->name }}</div>
                        </div>
                        <div class="causes-card__content">
                            <h3 class="causes-card__title">
                                <a href="{{ route('ministries.show', $relatedMinistry->slug) }}">{{ $relatedMinistry->name }}</a>
                            </h3>
                            <p class="causes-card__text">{{ Str::limit($relatedMinistry->description, 100) }}</p>
                            <div class="causes-card__bottom">
                                <a href="{{ route('ministries.show', $relatedMinistry->slug) }}" class="cleenhearts-btn">
                                    <div class="cleenhearts-btn__icon-box">
                                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="cleenhearts-btn__text">Learn More</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
