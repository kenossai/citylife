<section class="about-one section-space">
        <div class="about-one__bg">
            <div class="about-one__bg__border"></div><!-- /.about-one__bg__border -->
            <div class="about-one__bg__inner" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div><!-- /.about-one__left__bg__inner -->
        </div><!-- /.about-one__left__bg -->
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
                    <div class="about-one__left">
                        <div class="about-one__image">
                            @if($aboutPage->social_media_links && isset($aboutPage->social_media_links['youtube']))
                            <div class="about-one__video" style="background-image: url('{{ asset('assets/images/about/about-1-2.png') }}');">
                                <a href="{{ $aboutPage->social_media_links['youtube'] }}" class="about-one__video__btn video-button video-popup">
                                    <span class="icon-play"></span>
                                    <i class="video-button__ripple"></i>
                                </a><!-- /.about-one__video__btn -->
                            </div><!-- /.about-one__video -->
                            @endif
                        </div><!-- /.about-one__image -->
                    </div><!-- /.about-one__left -->
                </div>
                <div class="col-xl-6">
                    <div class="about-one__content">
                        <div class="sec-title">
                            <h6 class="sec-title__tagline">{{ strtoupper($aboutPage->title) }}</h6><!-- /.sec-title__tagline -->
                            <h3 class="sec-title__title">{{ $aboutPage->church_name }} <span class="sec-title__title__inner">{{ $aboutPage->church_description }}</span></h3><!-- /.sec-title__title -->
                        </div><!-- /.sec-title -->
                        <div class="about-one__text-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <p class="about-one__text"><strong>Affiliated with:</strong> {{ $aboutPage->affiliation }}</p>
                        </div><!-- /.about-one__text-box -->
                        <div class="about-one__text-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <p class="about-one__text">{{ $aboutPage->introduction }}</p>
                        </div><!-- /.about-one__text-box -->

                        {{-- Contact Information --}}
                        {{-- @if($aboutPage->phone_number || $aboutPage->email_address)
                        <div class="about-one__contact mt-4">
                            <div class="row">
                                @if($aboutPage->phone_number)
                                <div class="col-md-6">
                                    <div class="contact-info-item">
                                        <span class="icon-phone"></span>
                                        <a href="tel:{{ str_replace(' ', '', $aboutPage->phone_number) }}">{{ $aboutPage->phone_number }}</a>
                                    </div>
                                </div>
                                @endif
                                @if($aboutPage->email_address)
                                <div class="col-md-6">
                                    <div class="contact-info-item">
                                        <span class="icon-email"></span>
                                        <a href="mailto:{{ $aboutPage->email_address }}">{{ $aboutPage->email_address }}</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif --}}

                        <div class="contact-information mt-4">
                            <a href="{{ route('about') }}" class="contact-information__btn cleenhearts-btn">
                                <div class="cleenhearts-btn__icon-box">
                                    <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="cleenhearts-btn__text">more about us</span>
                            </a><!-- /.contact-information__btn -->
                            
                        </div><!-- /.contact-information -->
                    </div><!-- /.about-one__content -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
        <img src="{{ asset('assets/images/shapes/about-shape-1-2.png') }}" alt="citylife" class="about-one__hand">
    </section>