<x-app-layout>
@section('title', $mission->title . ' - CityLife Church')
@section('description', $mission->description)

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ $mission->featured_image ? Storage::url('' . $mission->featured_image) : asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">{{ $mission->title }}</h2>
        <p class="page-header__text">{{ $mission->description }}</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('missions.index') }}">Missions</a></li>
            <li><span>{{ $mission->title }}</span></li>
        </ul>
    </div>
</section>

<section class="blog-details section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="blog-details__content">
                    @if($mission->featured_image)
                    <div class="blog-details__image">
                        <img src="{{ Storage::url('' . $mission->featured_image) }}" alt="{{ $mission->title }}" class="img-fluid">
                    </div>
                    @endif

                    <div class="blog-details__meta">
                        <div class="blog-details__meta__item">
                            <i class="fa fa-map-marker"></i>
                            <span>{{ $mission->location }}</span>
                        </div>
                        <div class="blog-details__meta__item">
                            <i class="fa fa-tag"></i>
                            <span>{{ ucfirst($mission->mission_type) }} Mission</span>
                        </div>
                        @if($mission->target_group)
                        <div class="blog-details__meta__item">
                            <i class="fa fa-users"></i>
                            <span>{{ $mission->target_group }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="blog-details__text">
                        {!! nl2br(e($mission->content)) !!}
                    </div>

                    @if($mission->gallery_images && count($mission->gallery_images) > 0)
                    <div class="blog-details__gallery">
                        <h4>Gallery</h4>
                        <div class="row gutter-y-20">
                            @foreach($mission->gallery_images as $image)
                            <div class="col-md-4 col-sm-6">
                                <div class="blog-details__gallery__item">
                                    <img src="{{ Storage::url('' . $image) }}" alt="{{ $mission->title }}" class="img-fluid">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($mission->contact_person || $mission->contact_email)
                    <div class="blog-details__contact">
                        <h4>Contact Information</h4>
                        <div class="blog-details__contact__info">
                            @if($mission->contact_person)
                            <p><strong>Contact Person:</strong> {{ $mission->contact_person }}</p>
                            @endif
                            @if($mission->contact_email)
                            <p><strong>Email:</strong> <a href="mailto:{{ $mission->contact_email }}">{{ $mission->contact_email }}</a></p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Mission Details -->
                    <div class="sidebar__single sidebar__mission-details">
                        <h3 class="sidebar__title">Mission Details</h3>
                        <div class="sidebar__mission-details__content">
                            <div class="sidebar__mission-details__item">
                                <span class="sidebar__mission-details__label">Type:</span>
                                <span class="sidebar__mission-details__value">{{ ucfirst($mission->mission_type) }}</span>
                            </div>
                            @if($mission->location)
                            <div class="sidebar__mission-details__item">
                                <span class="sidebar__mission-details__label">Location:</span>
                                <span class="sidebar__mission-details__value">{{ $mission->location }}</span>
                            </div>
                            @endif
                            @if($mission->target_group)
                            <div class="sidebar__mission-details__item">
                                <span class="sidebar__mission-details__label">Target Group:</span>
                                <span class="sidebar__mission-details__value">{{ $mission->target_group }}</span>
                            </div>
                            @endif
                            <div class="sidebar__mission-details__item">
                                <span class="sidebar__mission-details__label">Status:</span>
                                <span class="sidebar__mission-details__value text-success">Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Get Involved -->
                    <div class="sidebar__single sidebar__get-involved">
                        <h3 class="sidebar__title">Get Involved</h3>
                        <div class="sidebar__get-involved__content">
                            <p>Support this mission through prayer, giving, or volunteering.</p>
                            <div class="sidebar__get-involved__actions">
                                <a href="{{ route('giving.index') }}" class="citylife-btn w-100 mb-3">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-donate"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Support Mission</span>
                                </a>
                                <a href="{{ route('contact') }}" class="citylife-btn citylife-btn--border w-100">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Contact Us</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Missions -->
                    @php
                        $relatedMissions = App\Models\Mission::active()
                            ->where('id', '!=', $mission->id)
                            ->where('mission_type', $mission->mission_type)
                            ->limit(3)
                            ->get();
                    @endphp

                    @if($relatedMissions->count() > 0)
                    <div class="sidebar__single sidebar__related">
                        <h3 class="sidebar__title">Related Missions</h3>
                        <div class="sidebar__related__content">
                            @foreach($relatedMissions as $related)
                            <div class="sidebar__related__item">
                                @if($related->featured_image)
                                <div class="sidebar__related__image">
                                    <img width="100" src="{{ Storage::url('' . $related->featured_image) }}" alt="{{ $related->title }}">
                                </div>
                                @endif
                                <div class="sidebar__related__content">
                                    <h4 class="sidebar__related__title">
                                        <a href="{{ route('missions.show', $related) }}">{{ $related->title }}</a>
                                    </h4>
                                    <p class="sidebar__related__text">{{ Str::limit($related->description, 80) }}</p>
                                </div>
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

</x-app-layout>
