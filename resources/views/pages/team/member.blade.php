<x-app-layout>
    @section('title', '{{ $teamMember->full_name }} - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ $teamMember->featured_image_url }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">{{ $teamMember->full_name }}</h2>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('team.index') }}">Team</a></li>
            <li><span>{{ $teamMember->display_name }}</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="volunteer-details section-space">
    <div class="container">
        <div class="row gutter-y-40">
            <div class="col-lg-6 wow fadeInLeft animated" data-wow-duration="1500ms" data-wow-delay="100ms">
                <div class="volunteer-details__image">
                    <img src="{{ $teamMember->profile_image_url }}" alt="{{ $teamMember->full_name }}">
                    @if($teamMember->hasContactInfo())
                    <div class="volunteer-details__social">
                        <ul class="person-social">
                            @if($teamMember->email)
                            <li>
                                <a href="mailto:{{ $teamMember->email }}"><span class="icon-envelope"></span></a>
                            </li>
                            @endif
                            @if($teamMember->phone)
                            <li>
                                <a href="tel:{{ $teamMember->phone }}"><span class="icon-phone"></span></a>
                            </li>
                            @endif
                        </ul>
                    </div><!-- /.volunteer-details__social -->
                    @endif
                </div><!-- /.volunteer-details__image -->
            </div><!-- /.col-lg-6 -->
            <div class="col-lg-6">
                <div class="volunteer-details__info">
                    <div class="volunteer-details__info__top">
                        <div class="volunteer-details__info__left">
                            <h3 class="volunteer-details__name">{{ $teamMember->full_name }}</h3><!-- /.volunteer-details__name -->
                            <p class="volunteer-details__designation">{{ $teamMember->position }}</p><!-- /.volunteer-details__designation -->
                            <div class="volunteer-details__team-type">
                                <span class="badge badge-{{ $teamMember->team_type === 'pastoral' ? 'primary' : 'secondary' }}">
                                    {{ $teamMember->team_type_display }}
                                </span>
                            </div>
                        </div><!-- /.volunteer-details__info__left -->
                    </div><!-- /.volunteer-details__info__top -->

                    @if($teamMember->short_description)
                        <div class="volunteer-details__text">
                            {!! $teamMember->short_description !!}
                        </div><!-- /.volunteer-details__text -->
                    @endif

                    @if($teamMember->ministry_focus || $teamMember->responsibilities || $teamMember->ministry_areas)
                    <div class="volunteer-details__ministry">
                        {{-- @if($teamMember->ministry_focus)
                            <h4 class="volunteer-details__ministry-title">Ministry Focus</h4>
                            <p>{{ $teamMember->ministry_focus }}</p>
                        @endif --}}

                        @if ($teamMember->team_type === 'pastoral')

                        @elseif($teamMember->responsibilities && count($teamMember->responsibilities) > 0)
                            <h4 class="volunteer-details__ministry-title">Responsibilities</h4>
                            <ul class="volunteer-details__list">
                                @foreach($teamMember->responsibilities as $responsibility)
                                    <li>{{ $responsibility }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- @if($teamMember->ministry_areas && count($teamMember->ministry_areas) > 0)
                            <h4 class="volunteer-details__ministry-title">Ministry Areas</h4>
                            <div class="volunteer-details__areas">
                                @foreach($teamMember->ministry_areas as $area)
                                    <span class="badge badge-light me-2">{{ $area }}</span>
                                @endforeach
                            </div>
                        @endif --}}
                    </div>
                    @endif

                    @if($teamMember->years_of_service || $teamMember->years_in_ministry || $teamMember->spouse_name)
                    <div class="volunteer-details__personal">
                        @if($teamMember->years_in_ministry)
                            <div class="volunteer-details__item">
                                <strong>Years in Ministry:</strong> {{ $teamMember->years_in_ministry }} years
                            </div>
                        @endif
                    </div>
                    @endif

                    @if($teamMember->calling_testimony)
                    <div class="volunteer-details__testimony">
                        <h4 class="volunteer-details__ministry-title">Calling & Testimony</h4>
                        <p>{{ $teamMember->calling_testimony }}</p>
                    </div>
                    @endif

                    @if($teamMember->achievements)
                    <div class="volunteer-details__achievements">
                        <h4 class="volunteer-details__ministry-title">Notable Achievements</h4>
                        <p>{{ $teamMember->achievements }}</p>
                    </div>
                    @endif

                    @if($teamMember->books_with_images && count($teamMember->books_with_images) > 0)
                    <div class="volunteer-details__books">
                        <h4 class="volunteer-details__ministry-title">Books & Publications</h4>
                        <div class="row gutter-y-20">
                            @foreach($teamMember->books_with_images as $book)
                                <div class="col-md-6">
                                    <div class="book-item">
                                        @if(isset($book['cover_image_url']))
                                            <img src="{{ $book['cover_image_url'] }}" alt="{{ $book['title'] }}" class="book-cover">
                                        @endif
                                        <h5>{{ $book['title'] }}</h5>
                                        @if(isset($book['subtitle']))
                                            <p class="book-subtitle">{{ $book['subtitle'] }}</p>
                                        @endif
                                        @if(isset($book['description']))
                                            <p class="book-description">{{ $book['description'] }}</p>
                                        @endif
                                        @if(isset($book['purchase_link']) || isset($book['download_link']))
                                            <div class="book-links">
                                                @if(isset($book['purchase_link']))
                                                    <a href="{{ $book['purchase_link'] }}" class="btn btn-primary btn-sm" target="_blank">Purchase</a>
                                                @endif
                                                @if(isset($book['download_link']))
                                                    <a href="{{ $book['download_link'] }}" class="btn btn-secondary btn-sm" target="_blank">Download</a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div><!-- /.volunteer__info -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<section class="volunteer-details__background">
    <div class="container">
        <div class="volunteer-details__background__Inner">
            <h4 class="volunteer-details__background__heading">Biography</h4><!-- /.volunteer-details__background__heading -->
            <div class="volunteer-details__background__content wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                <h5 class="volunteer-details__background__title">About {{ $teamMember->full_name }}<span class="volunteer-details__background__year">2010</span></h5><!-- /.volunteer-details__background__title -->
                <div class="volunteer-details__background__content__inner">
                    <p class="volunteer-details__background__text">{!! $teamMember->bio !!}</p><!-- /.volunteer-details__background__text -->
                </div><!-- /.volunteer-details__background__content__inner -->
            </div><!-- /.volunteer-details__background__content -->
        </div><!-- /.volunteer-details__background__Inner -->
    </div><!-- /.container -->
</section>
<section class="volunteer-details section-space">
    <div class="container">
        @if($relatedMembers && $relatedMembers->count() > 0)
        <div class="related-team-members mt-5">
            <h3 class="text-center mb-4">Other {{ ucfirst($teamMember->team_type) }} Team Members</h3>
            <div class="row gutter-y-30">
                @foreach($relatedMembers as $member)
                    <div class="col-md-4">
                        <div class="volunteer-card">
                            <div class="volunteer-card__image">
                                <img src="{{ $member->profile_image_url }}" alt="{{ $member->full_name }}">
                            </div>
                            <div class="volunteer-card__content">
                                <h3 class="volunteer-card__name">
                                    <a href="{{ route('team.member', $member->slug) }}">{{ $member->full_name }}</a>
                                </h3>
                                <h6 class="volunteer-card__designation">{{ $member->position }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div><!-- /.container -->
</section>
</x-app-layout>
