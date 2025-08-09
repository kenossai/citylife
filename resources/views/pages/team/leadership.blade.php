<x-app-layout>
    @section('title', 'Leadership Team - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">Leadership Team</h2>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Leadership Team</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="volunteer-page section-space">
    <div class="container">
        @if($leadershipTeam->count() > 0)
            <div class="text-center mb-5">
                <h3 class="sec-title__title">Leadership Team</h3>
                <p class="sec-title__text">Our dedicated leadership team serves with excellence in various ministry areas to support the growth and development of our church community.</p>
            </div>
            <div class="row gutter-y-30">
                @foreach ($leadershipTeam as $leader)
                    <div class="col-md-6 col-lg-4 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                        <div class="volunteer-card">
                            <div class="volunteer-card__image">
                                <img src="{{ $leader->profile_image_url }}" alt="{{ $leader->full_name }}">
                                @if($leader->hasContactInfo())
                                <div class="volunteer-card__social">
                                    <span class="icon-share"></span>
                                    <div class="volunteer-card__social__list">
                                        @if($leader->email)
                                            <a href="mailto:{{ $leader->email }}">
                                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                                <span class="sr-only">Email</span>
                                            </a>
                                        @endif
                                        @if($leader->phone)
                                            <a href="tel:{{ $leader->phone }}">
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
                                    <a href="{{ route('team.member', $leader->slug) }}">{{ $leader->full_name }}</a>
                                </h3><!-- /.volunteer-card__name -->
                                <h6 class="volunteer-card__designation">{{ $leader->position }}</h6><!-- /.volunteer-card__designation -->
                            </div><!-- /.volunteer-card__content -->
                        </div><!-- /.volunteer-card -->
                    </div><!-- /.col-md-6 col-lg-4 -->
                @endforeach
            </div><!-- /.row -->
        @else
            <div class="text-center">
                <h3>No leadership team members found</h3>
                <p>Please check back later for updates.</p>
            </div>
        @endif
    </div><!-- /.container -->
</section>
</x-app-layout>
