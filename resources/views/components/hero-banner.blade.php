<section class="main-slider-one">
    <div class="main-slider-one__wrapper">
        <div class="main-slider-one__carousel citylife-owl__carousel owl-carousel" data-owl-options='{
    "loop": true,
    "animateOut": "fadeOut",
    "animateIn": "fadeIn",
    "items": 1,
    "autoplay": true,
    "autoplayTimeout": 7000,
    "smartSpeed": 1000,
    "nav": false,
    "navText": ["<span class=\"icon-left-arrow\"></span>","<span class=\"icon-right-arrow\"></span>"],
    "dots": true,
    "margin": 0
    }'>
            @foreach($banners as $banner)
            <div class="item">
                <div class="main-slider-one__item">
                    <div class="main-slider-one__bg" style="background-image: url('{{ $banner->background_image_url }}')"></div>
                    <div class="main-slider-one__shape-one" style="background-image: url({{ asset('assets') }}/images/shapes/slider-1-shape-1.png);"></div>
                    <div class="main-slider-one__shape-two" style="background-image: url({{ asset('assets') }}/images/shapes/slider-1-shape-2.png);"></div>
                    <div class="main-slider-one__content">
                        @if($banner->subtitle)
                        <h5 class="main-slider-one__sub-title">{{ $banner->subtitle }}</h5>
                        @endif
                        <h2 class="main-slider-one__title">
                            <span class="main-slider-one__title__text">{{ $banner->title }}</span>
                            @if($banner->description)
                            <br><span class="main-slider-one__title__text">{{ $banner->description }}</span>
                            @endif
                        </h2>
                        <div class="main-slider-one__btn">
                            <a href="{{ route('our-ministry') }}" class="citylife-btn">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                </div>
                                <span class="citylife-btn__text">join with us</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


