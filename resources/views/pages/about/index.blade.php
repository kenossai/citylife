<x-app-layout>
    @section('title', $aboutPage->title . ' - ' . $aboutPage->church_name)
    @section('description', $aboutPage->meta_description ?: $aboutPage->introduction)
    @section('keywords', $aboutPage->meta_keywords ? implode(', ', $aboutPage->meta_keywords) : '')

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
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
            <div class="about-one__bg__inner" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div><!-- /.about-one__left__bg__inner -->
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
                            <a href="{{ route('courses.index') }}" class="contact-information__btn cleenhearts-btn">
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
                                    <img src="{{ $value->featured_image_url ?: asset('assets/images/events/event-2-4.jpg') }}" alt="{{ $value->title }}" style="object-fit: cover;">
                                </a><!-- /.event-card-four__image -->
                                <div class="event-card-four__content">
                                    <div class="event-card-four__time">
                                       {{ $value->title }}
                                    </div><!-- /.event-card-four__time -->
                                    <h4 class="event-card-four__title">{{ $value->excerpt }}</h4><!-- /.event-card-four__title -->
                                     @if($value->bible_verse)
                                    <div class="event-card-four__text"><em>{{ $value->description }}</em></div><!-- /.event-card-four__text -->
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
    

    {{-- Leadership Section Start --}}
    <section class="team-one section-space">
        <div class="container">
            <div class="team-one__top">
                <div class="row gutter-y-30 align-items-center">
                    <div class="col-xxl-8 col-lg-7">
                        <div class="sec-title">
                            <h6 class="sec-title__tagline @@extraClassName">Pastoral Team</h6><!-- /.sec-title__tagline -->
                            <h3 class="sec-title__title">Pastoral Team</h3><!-- /.sec-title__title -->
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

            @foreach ($pastoralTeam as $pastor)
                <div class="item">
                    <div class="team-single">
                        <div class="team-single__image">
                            <img src="{{ asset('storage/' . $pastor->profile_image) }}" alt="{{ $pastor->full_name }}">
                            <div class="team-single__content">
                                <div class="team-single__content__inner">
                                    <h4 class="team-single__name">{{ $pastor->full_name }}</h4><!-- /.team-single__name -->
                                </div><!-- /.team-single__content__inner -->
                            </div><!-- /.team-single__content -->
                        </div><!-- /.team-single__image -->
                    </div><!-- /.team-single -->
                </div><!-- /.item -->
            @endforeach

            </div><!-- /.team-one__carousel -->
        </div><!-- /.container -->
    </section>
    {{-- Leadership Section End --}}


</x-app-layout>
