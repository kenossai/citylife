<x-app-layout>
    @section('title', $ministry->name . ' - City Life Church')

 <section class="page-header @@extraClassName">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/backgrounds/worship-banner-1.jpg') }});"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">{{ $ministry->name }}</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><i class="icon-home"></i> <a href="{{ route('ministries.index') }}">Ministries</a></li>
            <li><span>{{ $ministry->name }}</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>

<section class="event-details section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="event-details__content">
                    <div class="event-details__image wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                        <img src="{{ Storage::url('' . $ministry->featured_image) }}" alt="{{ $ministry->name }}">
                    </div><!-- /.event-details__image -->
                    @if ($ministry->meeting_time)
                        <div class="event-details__time">
                            <i class="event-details__time__icon fa fa-clock"></i>
                            <span class="event-details__time__text">{{ $ministry->meeting_time }}</span>
                        </div><!-- /.event-details__time -->
                    @endif
                    <h3 class="event-details__title">{{ $ministry->name }}</h3><!-- /.event-details__title -->
                    <div class="event-details__text">
                        <p class="event-details__text__inner">{!! $ministry->description !!}</p>
                    </div><!-- /.event-details__text -->
                    <div class="event-details__inner">
                        <div class="row gutter-y-30">
                            <div class="col-md-6 wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                                    <div class="event-details__inner__image">
                                        <img src="{{ Storage::url('' . $ministry->leader_image) }}" alt="{{ $ministry->leader }}">
                                    </div><!-- /.event-details__inner__image -->
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6 wow fadeInUp animated" data-wow-delay="300ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                                <div class="event-details__inner__image">
                                    <img src="{{ Storage::url('' . $ministry->assistant_leader_image) }}" alt="{{ $ministry->assistant_leader }}">
                                </div><!-- /.event-details__inner__image -->
                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->
                        <div class="event-details__inner__content">
                            <p class="event-details__inner__text">{!! $ministry->content !!}</p>
                        </div>
                    </div><!-- /.event-details__inner -->
                </div><!-- /.event-details__content -->

                <div class="event-details__contact contact-information">
                    @if($ministry->contact_email)
                    <a href="{{ route('ministries.contact', $ministry->slug) }}" class="contact-information__btn citylife-btn">
                        <div class="citylife-btn__icon-box">
                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                        </div>
                        <span class="citylife-btn__text">join now</span>
                    </a><!-- /.contact-information__btn -->
                    @endif
                </div><!-- /.contact-information -->
            </div><!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <aside class="sidebar-event">
                    <div class="sidebar-event__contact contact-one sidebar-event__item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                        <div class="contact-one__info">
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-user"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Leaders:</h4>
                                    <address class="contact-one__info__text">{{ $ministry->leader }}</address>
                                    <address class="contact-one__info__text">{{ $ministry->assistant_leader }}</address>
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                             @if($ministry->contact_email)
                            <div class="contact-one__info__item">
                                <div class="contact-one__info__icon">
                                    <span class="icon-envelope"></span>
                                </div><!-- /.contact-one__info__icon -->
                                <div class="contact-one__info__content">
                                    <h4 class="contact-one__info__title">Email Us:</h4>
                                    <a href="mailto:{{ $ministry->contact_email }}" class="contact-one__info__text contact-one__info__text--link">{{ $ministry->contact_email }}</a>
                                </div><!-- /.contact-one__info__content -->
                            </div><!-- /.contact-one__info__item -->
                            @endif
                        </div><!-- /.contact-one__info -->
                        <div class="sidebar-event__contact__image">
                            <img src="{{ $ministry->featured_image ? Storage::url($ministry->featured_image) : asset('assets/images/ministry/default-ministry.jpg') }}" alt="events">
                        </div><!-- /.sidebar-event__contact__image -->
                    </div><!-- /.sidebar-event__contact -->
                </aside><!-- /.sidebar-event -->
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
    </section>
</x-app-layout>
