<x-app-layout>
    @section('title', $aboutPage->title . ' - ' . $aboutPage->church_name)
    @section('description', $aboutPage->meta_description ?: $aboutPage->introduction)
    @section('keywords', $aboutPage->meta_keywords ? implode(', ', $aboutPage->meta_keywords) : '')

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ $aboutPage->featured_image_url ?: asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <!-- /.page-header__bg -->
        <div class="container">
            <h2 class="page-header__title">{{ $aboutPage->title }}</h2>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>{{ $aboutPage->title }}</span></li>
            </ul><!-- /.thm-breadcrumb list-unstyled -->
        </div><!-- /.container -->
    </section>

    {{-- Church Introduction Section --}}
    <section class="about-one section-space">
        <div class="about-one__bg">
            <div class="about-one__bg__border"></div><!-- /.about-one__bg__border -->
            <div class="about-one__bg__inner" style="background-image: url('{{ asset('assets/images/shapes/about-shape-1-1.png') }}');"></div><!-- /.about-one__left__bg__inner -->
        </div><!-- /.about-one__left__bg -->
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
                    <div class="about-one__left">
                        <div class="about-one__image">
                            <img src="{{ $aboutPage->featured_image_url ?: asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}" alt="about" class="about-one__image__one">
                            @if($aboutPage->social_media_links && isset($aboutPage->social_media_links['youtube']))
                            <div class="about-one__video" style="background-image: url('{{ asset('assets/images/about/about-1-2.png') }}');">
                                <a href="{{ $aboutPage->social_media_links['youtube'] }}" class="about-one__video__btn video-button video-popup">
                                    <span class="icon-play"></span>
                                    <i class="video-button__ripple"></i>
                                </a><!-- /.about-one__video__btn -->
                            </div><!-- /.about-one__video -->
                            @endif
                            <div class="about-one__profile volunteer-profile">
                                <div class="volunteer-profile__inner">
                                    <img src="{{ asset('assets/images/resources/robert-joe-kerry.png') }}" alt="Pastor" class="volunteer-profile__image">
                                    <div class="volunteer-profile__info">
                                        <h4 class="volunteer-profile__name"><a href="#">Lead Pastor</a></h4><!-- /.volunteer-profile__name -->
                                        <p class="volunteer-profile__designation">{{ $aboutPage->church_name }}</p><!-- /.volunteer-profile__designation -->
                                    </div><!-- /.volunteer-profile__info -->
                                </div><!-- /.volunteer-profile__inner -->
                            </div><!-- /.about-one__profile -->
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
                            <p class="about-one__text">{{ $aboutPage->introduction }}</p>

                            @if($aboutPage->affiliation)
                            <div class="about-one__affiliation mt-3">
                                <strong>Affiliated with:</strong> {{ $aboutPage->affiliation }}
                                @if($aboutPage->location_description)
                                    <br><strong>Location:</strong> {{ $aboutPage->location_description }}
                                @endif
                            </div>
                            @endif
                        </div><!-- /.about-one__text-box -->

                        {{-- Contact Information --}}
                        @if($aboutPage->phone_number || $aboutPage->email_address)
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
                        @endif

                        <div class="contact-information mt-4">
                            <a href="{{ route('courses.index') }}" class="contact-information__btn cleenhearts-btn">
                                <div class="cleenhearts-btn__icon-box">
                                    <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="cleenhearts-btn__text">more about us</span>
                            </a><!-- /.contact-information__btn -->
                            <div class="contact-information__phone">
                                <div class="contact-information__phone__icon">
                                    <span class="icon-phone"></span>
                                </div><!-- /.contact-information__phone__icon -->
                                <div class="contact-information__phone__text">
                                    <span>call any time</span>
                                    <h5><a href="tel:+912659302003">+91 2659 302 003</a></h5>
                                </div><!-- /.contact-information__phone__text -->
                            </div><!-- /.contact-information__phone -->
                        </div><!-- /.contact-information -->
                    </div><!-- /.about-one__content -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
        <img src="assets/images/shapes/about-shape-1-2.png" alt="cleenhearts" class="about-one__hand">
    </section>

    {{-- Core Values Section --}}

<section class="events-list-page section-space">
            <div class="container">
                <div class="sec-title text-center">
                    <h6 class="sec-title__tagline">our values</h6>
                    <h3 class="sec-title__title">Core Values That Guide Us</h3>
                </div>
                <div class="events-list-page__carousel cleenhearts-owl__carousel cleenhearts-owl__carousel--basic-nav owl-theme owl-carousel owl-loaded owl-drag" data-owl-options="{
            &quot;items&quot;: 2,
            &quot;margin&quot;: 30,
            &quot;smartSpeed&quot;: 700,
            &quot;loop&quot;:true,
            &quot;autoplay&quot;: 6000,
            &quot;nav&quot;:false,
            &quot;dots&quot;:true,
            &quot;navText&quot;: [&quot;&lt;span class=\&quot;icon-arrow-left\&quot;&gt;&lt;/span&gt;&quot;,&quot;&lt;span class=\&quot;icon-arrow-right\&quot;&gt;&lt;/span&gt;&quot;],
            &quot;responsive&quot;:{
                &quot;0&quot;:{
                    &quot;items&quot;: 1,
                    &quot;margin&quot;: 20
                },
                &quot;575&quot;:{
                    &quot;items&quot;: 1,
                    &quot;margin&quot;: 20
                },
                &quot;768&quot;:{
                    &quot;items&quot;: 1,
                    &quot;margin&quot;: 20
                },
                &quot;992&quot;:{
                    &quot;items&quot;: 1,
                    &quot;margin&quot;: 20
                },
                &quot;1200&quot;:{
                    &quot;items&quot;: 1,
                    &quot;margin&quot;: 20
                }
            }
            }">
                    <!-- /.item -->
                    <!-- /.item -->
                    <!-- /.item -->
                    <!-- /.item -->
                <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-4760px, 0px, 0px); transition: 0.7s; width: 9520px;">
                    @foreach($coreValues as $index => $value)
                        <div class="owl-item" style="width: 1170px; margin-right: 20px;"><div class="item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
                            <div class="event-card-four">
                                <a href="javascript:void(0)" class="event-card-four__image">
                                    <img src="assets/images/events/event-2-4.jpg" alt="The generated Lorem Ipsum is therefore always free from repetition">
                                </a><!-- /.event-card-four__image -->
                                <div class="event-card-four__content">
                                    <div class="event-card-four__time">
                                       {{ $value->title }}
                                    </div><!-- /.event-card-four__time -->
                                    <h4 class="event-card-four__title">{{ $value->excerpt }}</h4><!-- /.event-card-four__title -->
                                     @if($value->bible_verse)
                                    <div class="event-card-four__text"><em>{{ $value->bible_verse }}</em></div><!-- /.event-card-four__text -->
                                    <ul class="event-card-four__meta">
                                        @if($value->bible_reference)
                                        <li>
                                            <h5 class="event-card-four__meta__title">{{ $value->bible_reference }}</h5>
                                        </li>
                                        @endif
                                    </ul><!-- /.event-card-four__meta -->
                                    @endif
                                </div><!-- /.event-card-four__content -->
                            </div><!-- /.event-card-four -->
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="owl-nav disabled"><button type="button" role="presentation" class="owl-prev" aria-label="carousel button"><span class="icon-arrow-left"></span></button><button type="button" role="presentation" class="owl-next" aria-label="carousel button"><span class="icon-arrow-right"></span></button></div></div><!-- /.row -->
            </div><!-- /.container -->
        </section>
    {{-- City Life Story --}}
    <section class="story-one section-space-top cleenhearts-jarallax" data-jarallax data-speed="0.3" style="background-image: url('assets/images/backgrounds/story-bg-1-1.jpg');">
        <div class="container">
            <div class="sec-title">
                <h6 class="sec-title__tagline">{{ strtoupper($aboutPage->church_name) }} STORY</h6>
                <h3 class="sec-title__title">{{ $aboutPage->history_title ?? 'A Journey Through Our Story' }} <span class="sec-title__title__inner">Story</span></h3>
            </div>

            <div class="story-one__tabs-box tabs-box">
                <div class="tabs-content">
                    <div class="tab active-tab" id="year1992" style="display: block;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-1.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">{{ $aboutPage->mission_title ?? 'Our Mission and Vision' }}</h3>
                                    @if($aboutPage->mission_statement)
                                    <p class="story-one__text story-one__text--one">{{ $aboutPage->mission_statement }}</p>
                                    @endif
                                    @if($aboutPage->vision_statement)
                                    <p class="story-one__text story-one__text--two">{{ $aboutPage->vision_statement }}</p>
                                    @endif
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="{{ asset('assets/images/resources/robert-joe-kerry.png') }}" alt="Lead Pastor" class="lead-pastor">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="#">{{ $aboutPage->lead_pastor_name ?? 'Lead Pastor' }}</a></h4>
                                                <p class="volunteer-profile__designation">{{ $aboutPage->lead_pastor_title ?? 'Lead Pastor' }}</p>
                                            </div>
                                        </div>
                                        @if($aboutPage->lead_pastor_signature)
                                        <img src="{{ $aboutPage->pastor_signature_url }}" alt="Pastor signature" class="volunteer-profile__signature">
                                        @else
                                        <img src="{{ asset('assets/images/resources/volunteer-d-signature.png') }}" alt="Pastor signature" class="volunteer-profile__signature">
                                        @endif
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->

                    <div class="tab" id="year2003" style="display: none;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-2.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">Program Export</h3><!-- /.story-one__title -->
                                    <p class="story-one__text story-one__text--one">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit felis quis pellentesque porttitor. Aenean lobortis bibendum turpis et auctor. Nam iaculis, lectus vulputate cursus interdum</p><!-- /.story-one__text story-one__text--one -->
                                    <p class="story-one__text story-one__text--two">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit</p><!-- /.story-one__text story-one__text--two -->
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="assets/images/resources/robert-joe-kerry.png" alt="Velma P. Hawkins" class="Robert Joe Kerry">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="volunteer-details.html">Robert Joe Kerry</a></h4><!-- /.volunteer-profile__name -->
                                                <p class="volunteer-profile__designation">Founder</p><!-- /.volunteer-profile__designation -->
                                            </div><!-- /.volunteer-profile__info -->
                                        </div><!-- /.volunteer-profile__inner -->
                                        <img src="assets/images/resources/volunteer-d-signature.png" alt="Robert Joe Kerry signature" class="volunteer-profile__signature">
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->

                    <div class="tab" id="year2010" style="display: none;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-3.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">Children treatment</h3><!-- /.story-one__title -->
                                    <p class="story-one__text story-one__text--one">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit felis quis pellentesque porttitor. Aenean lobortis bibendum turpis et auctor. Nam iaculis, lectus vulputate cursus interdum</p><!-- /.story-one__text story-one__text--one -->
                                    <p class="story-one__text story-one__text--two">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit</p><!-- /.story-one__text story-one__text--two -->
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="assets/images/resources/robert-joe-kerry.png" alt="Velma P. Hawkins" class="Robert Joe Kerry">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="volunteer-details.html">Robert Joe Kerry</a></h4><!-- /.volunteer-profile__name -->
                                                <p class="volunteer-profile__designation">Founder</p><!-- /.volunteer-profile__designation -->
                                            </div><!-- /.volunteer-profile__info -->
                                        </div><!-- /.volunteer-profile__inner -->
                                        <img src="assets/images/resources/volunteer-d-signature.png" alt="Robert Joe Kerry signature" class="volunteer-profile__signature">
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->

                    <div class="tab" id="year2015" style="display: none;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-4.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">Medical treatment</h3><!-- /.story-one__title -->
                                    <p class="story-one__text story-one__text--one">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit felis quis pellentesque porttitor. Aenean lobortis bibendum turpis et auctor. Nam iaculis, lectus vulputate cursus interdum</p><!-- /.story-one__text story-one__text--one -->
                                    <p class="story-one__text story-one__text--two">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit</p><!-- /.story-one__text story-one__text--two -->
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="assets/images/resources/robert-joe-kerry.png" alt="Velma P. Hawkins" class="Robert Joe Kerry">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="volunteer-details.html">Robert Joe Kerry</a></h4><!-- /.volunteer-profile__name -->
                                                <p class="volunteer-profile__designation">Founder</p><!-- /.volunteer-profile__designation -->
                                            </div><!-- /.volunteer-profile__info -->
                                        </div><!-- /.volunteer-profile__inner -->
                                        <img src="assets/images/resources/volunteer-d-signature.png" alt="Robert Joe Kerry signature" class="volunteer-profile__signature">
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->

                    <div class="tab" id="year2020" style="display: none;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-5.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">School of Catalog</h3><!-- /.story-one__title -->
                                    <p class="story-one__text story-one__text--one">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit felis quis pellentesque porttitor. Aenean lobortis bibendum turpis et auctor. Nam iaculis, lectus vulputate cursus interdum</p><!-- /.story-one__text story-one__text--one -->
                                    <p class="story-one__text story-one__text--two">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit</p><!-- /.story-one__text story-one__text--two -->
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="assets/images/resources/robert-joe-kerry.png" alt="Velma P. Hawkins" class="Robert Joe Kerry">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="volunteer-details.html">Robert Joe Kerry</a></h4><!-- /.volunteer-profile__name -->
                                                <p class="volunteer-profile__designation">Founder</p><!-- /.volunteer-profile__designation -->
                                            </div><!-- /.volunteer-profile__info -->
                                        </div><!-- /.volunteer-profile__inner -->
                                        <img src="assets/images/resources/volunteer-d-signature.png" alt="Robert Joe Kerry signature" class="volunteer-profile__signature">
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->

                    <div class="tab" id="year2023" style="display: none;">
                        <div class="row gutter-y-40">
                            <div class="col-xl-6 animated fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__image">
                                    <img src="assets/images/story/story-1-6.jpg" alt="story">
                                </div><!-- /.story-one__image -->
                            </div><!-- /.col-xl-6 -->
                            <div class="col-xl-6 animated fadeInRight" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <div class="story-one__content">
                                    <h3 class="story-one__title">Fundraise Goals</h3><!-- /.story-one__title -->
                                    <p class="story-one__text story-one__text--one">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit felis quis pellentesque porttitor. Aenean lobortis bibendum turpis et auctor. Nam iaculis, lectus vulputate cursus interdum</p><!-- /.story-one__text story-one__text--one -->
                                    <p class="story-one__text story-one__text--two">Nam ultrices odio a felis lobortis convallis. In ex nunc, ornare non condimentum et, egestas vel massa. Nullam hendrerit</p><!-- /.story-one__text story-one__text--two -->
                                    <div class="volunteer-profile">
                                        <div class="volunteer-profile__inner">
                                            <img src="assets/images/resources/robert-joe-kerry.png" alt="Velma P. Hawkins" class="Robert Joe Kerry">
                                            <div class="volunteer-profile__info">
                                                <h4 class="volunteer-profile__name"><a href="volunteer-details.html">Robert Joe Kerry</a></h4><!-- /.volunteer-profile__name -->
                                                <p class="volunteer-profile__designation">Founder</p><!-- /.volunteer-profile__designation -->
                                            </div><!-- /.volunteer-profile__info -->
                                        </div><!-- /.volunteer-profile__inner -->
                                        <img src="assets/images/resources/volunteer-d-signature.png" alt="Robert Joe Kerry signature" class="volunteer-profile__signature">
                                    </div><!-- /.volunteer-profile -->
                                </div><!-- /.story-one__content -->
                            </div><!-- /.col-xl-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.tab -->
                </div><!-- /.tabs-content -->
                <div class="story-one__divider"></div><!-- /.story-one__divider -->
                <div class="tab-buttons">
                    <div class="row gutter-y-20">
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year1992" class="tab-btn tab-btn--one active-btn wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">1993</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year2003" class="tab-btn tab-btn--two wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">2003</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year2010" class="tab-btn tab-btn--three wow fadeInUp" data-wow-delay="400ms" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">2010</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year2015" class="tab-btn tab-btn--four wow fadeInUp" data-wow-delay="600ms" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">2015</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year2020" class="tab-btn tab-btn--five wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">2020</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                        <div class="col-lg-2 col-sm-4 col-6">
                            <div data-tab="#year2023" class="tab-btn tab-btn--six wow fadeInUp" data-wow-delay="1s" data-wow-duration="1500ms">
                                <h3 class="tab-btn__text">2023</h3>
                            </div>
                        </div><!-- /.col-lg-2 col-sm-4 col-6 -->
                    </div><!-- /.row -->
                </div><!-- /.tab-buttons -->
            </div><!-- /.story-one__tabs-box -->
        </div><!-- /.container -->
    </section>
    {{-- City Life Story end --}}

    {{-- Leadership Section Start --}}
    <section class="team-one section-space">
        <div class="container">
            <div class="team-one__top">
                <div class="row gutter-y-30 align-items-center">
                    <div class="col-xxl-8 col-lg-7">
                        <div class="sec-title">

                            <h6 class="sec-title__tagline @@extraClassName">Leadership Team</h6><!-- /.sec-title__tagline -->

                            <h3 class="sec-title__title">Meet The Leaders Behind Their <span class="sec-title__title__inner">Success</span> Story</h3><!-- /.sec-title__title -->
                        </div><!-- /.sec-title -->
                    </div><!-- /.col-xxl-8 col-lg-7 -->
                    <div class="col-xxl-4 col-lg-5 wow fadeInRight" data-wow-duration="1500ms">
                        <p class="team-one__text">We help companies develop powerful corporate social responsibility, grantmaking, and employee engagement strategies.</p><!-- /.team-one__text -->
                    </div><!-- /.col-xxl-4 col-lg-5 -->
                </div><!-- /.row gutter-y-40 -->
            </div><!-- /.team-one__top -->
            <div class="team-one__carousel cleenhearts-owl__carousel cleenhearts-owl__carousel--with-shadow cleenhearts-owl__carousel--basic-nav owl-theme owl-carousel" data-owl-options='{
        "items": 3,
        "margin": 30,
        "smartSpeed": 700,
        "loop":true,
        "autoplay": 6000,
        "nav":true,
        "dots":false,
        "navText": ["<span class=\"icon-arrow-left\"></span>","<span class=\"icon-arrow-right\"></span>"],
        "responsive":{
            "0":{
                "items": 1,
                "margin": 20
            },
            "575":{
                "items": 1,
                "margin": 30
            },
            "768":{
                "items": 2,
                "margin": 30
            },
            "992":{
                "items": 3,
                "margin": 30
            },
            "1200":{
                "items": 3,
                "margin": 30
            }
        }
        }'>
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-1.jpg" alt="Harry P. Finch">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Harry P. Finch</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-2.jpg" alt="Patricia E. Wall">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Patricia E. Wall</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-3.jpg" alt="Alan P. Moe">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Alan P. Moe</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-1.jpg" alt="Harry P. Finch">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Harry P. Finch</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-2.jpg" alt="Patricia E. Wall">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Patricia E. Wall</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="assets/images/team/team-1-3.jpg" alt="Alan P. Moe">
                            <div class="team-single__content">
                                <ul class="team-single__social person-social">
                                    <li>
                                        <a href="https://facebook.com"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li>
                                        <a href="ttps://twitter.com"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://linkedin.com"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li>
                                        <a href="https://youtube.com"><span class="icon-youtube"></span></a>
                                    </li>
                                </ul><!-- /.team-single__social -->
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">Alan P. Moe</h4><!-- /.team-single__name -->
                                    <p class="team-single__designation">Co-Founder & CEO</p><!-- /.team-single__designation -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
            </div><!-- /.team-one__carousel -->
        </div><!-- /.container -->
    </section>
    {{-- Leadership Section End --}}

    <section class="faq-one faq-one--about section-space">
            <div class="faq-one__bg" style="background-image: url('assets/images/backgrounds/faq-bg-2.png');"></div>
            <!-- /.faq-one__bg -->
            <div class="container">
                <div class="row gutter-y-50">
                    <div class="col-xl-6 col-lg-6 wow fadeInLeft" data-wow-duration="1500ms" data-wow-delay="100ms">
                        <div class="faq-one__image">
                            <img src="assets/images/faq/faq-1-1.jpg" alt="faq-image">
                        </div><!-- /.faq-one__image -->
                    </div><!-- /.col-xl-6 col-lg-6 -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="faq-one__content">
                            <div class="sec-title">

                                <h6 class="sec-title__tagline @@extraClassName">Recently asked questions</h6>
                                <!-- /.sec-title__tagline -->

                                <h3 class="sec-title__title">People Are Frequently Asking Some <span
                                        class="sec-title__title__inner">Questions</span></h3><!-- /.sec-title__title -->
                            </div><!-- /.sec-title -->
                            <p class="faq-one__text">We help companies develop powerful corporate social responsibility,
                                grantmaking, and employee engagement strategies.</p><!-- /.faq-one__text -->
                            <div class="cleenhearts-accordion wow fadeInUp" data-wow-duration="1500ms"
                                data-wow-delay="100ms" data-grp-name="cleenhearts-accordion">
                                <div class="accordion @@extraClassName">
                                    <div class="accordion-title">
                                        <h4>
                                            How can i donation peoples?
                                            <span class="accordion-title__icon"></span><!-- /.accordion-title__icon -->
                                        </h4>
                                    </div><!-- /.accordian-title -->
                                    <div class="accordion-content">
                                        <div class="inner">
                                            <p>We help companies develop powerful corporate social responsibility,
                                                grantmaking, and employee engagement strategies.</p>
                                        </div><!-- /.accordian-content -->
                                    </div>
                                </div><!-- /.accordian-item -->
                                <div class="accordion active">
                                    <div class="accordion-title">
                                        <h4>
                                            It service for business network?
                                            <span class="accordion-title__icon"></span><!-- /.accordion-title__icon -->
                                        </h4>
                                    </div><!-- /.accordian-title -->
                                    <div class="accordion-content">
                                        <div class="inner">
                                            <p>We help companies develop powerful corporate social responsibility,
                                                grantmaking, and employee engagement strategies.</p>
                                        </div><!-- /.accordian-content -->
                                    </div>
                                </div><!-- /.accordian-item -->
                                <div class="accordion @@extraClassName">
                                    <div class="accordion-title">
                                        <h4>
                                            Is this non profitable organization?
                                            <span class="accordion-title__icon"></span><!-- /.accordion-title__icon -->
                                        </h4>
                                    </div><!-- /.accordian-title -->
                                    <div class="accordion-content">
                                        <div class="inner">
                                            <p>We help companies develop powerful corporate social responsibility,
                                                grantmaking, and employee engagement strategies.</p>
                                        </div><!-- /.accordian-content -->
                                    </div>
                                </div><!-- /.accordian-item -->
                            </div>
                        </div><!-- /.faq-one__content -->
                    </div><!-- /.col-xl-6 col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section>
</x-app-layout>
