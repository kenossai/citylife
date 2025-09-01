<x-app-layout>
    @section('title', 'Our Team - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">Our Team</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Our Team</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>

<section class="volunteer-page section-space">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="sec-title__title">Meet Our Team</h3>
            <p class="sec-title__text">Our dedicated team serves with passion to advance God's kingdom and shepherd our community with love and excellence.</p>
        </div>

        @php
            $allTeamMembers = $pastoralTeam->concat($leadershipTeam)->sortBy('sort_order');
        @endphp

        @if($allTeamMembers->count() > 0)
            <div class="row gutter-y-30">
                @foreach ($allTeamMembers as $member)
                    <div class="col-md-6 col-lg-4 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                        <div class="volunteer-card">
                            <div class="volunteer-card__image">
                                <img src="{{ $member->profile_image_url }}" style="object-fit: cover;  width: 100%;" alt="{{ $member->full_name }}">
                                @if($member->hasContactInfo())
                                <div class="volunteer-card__social">
                                    <span class="icon-share"></span>
                                    <div class="volunteer-card__social__list">
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}">
                                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                                <span class="sr-only">Email</span>
                                            </a>
                                        @endif
                                        @if($member->phone)
                                            <a href="tel:{{ $member->phone }}">
                                                <i class="fas fa-phone" aria-hidden="true"></i>
                                                <span class="sr-only">Phone</span>
                                            </a>
                                        @endif
                                    </div><!-- /.volunteer-card__social__list -->
                                </div><!-- /.volunteer-card__social -->
                                @endif
                            </div><!-- /.volunteer-card__image -->
                            <div class="volunteer-card__content">
                                <h3 class="volunteer-card__name">
                                    <a href="{{ route('team.member', $member->slug) }}">{{ $member->full_name }}</a>
                                </h3><!-- /.volunteer-card__name -->
                                <h6 class="volunteer-card__designation">{{ $member->position }}</h6><!-- /.volunteer-card__designation -->
                                <div class="volunteer-card__team-type">
                                    <span class="badge badge-{{ $member->team_type === 'pastoral' ? 'primary' : 'secondary' }}">
                                        {{ $member->team_type_display }}
                                    </span>
                                </div>
                            </div><!-- /.volunteer-card__content -->
                        </div><!-- /.volunteer-card -->
                    </div><!-- /.col-md-6 col-lg-4 -->
                @endforeach
            </div><!-- /.row -->

            <div class="text-center mt-5">
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <a href="{{ route('team.pastoral') }}" class="citylife-btn me-3">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-users"></span></div>
                            </div>
                            <span class="citylife-btn__text">Pastoral Team</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('team.leadership') }}" class="citylife-btn citylife-btn--base2">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-users"></span></div>
                            </div>
                            <span class="citylife-btn__text">Leadership Team</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <h3>No team members found</h3>
                <p>Please check back later for updates.</p>
            </div>
        @endif
    </div><!-- /.container -->
</section>
</x-app-layout>
