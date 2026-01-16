<x-base-layout>
    <section class="error-404 section-space">
        <div class="container">
            <div class="error-404__content">
                <div class="error-404__content__top">
                    <img src="{{ asset('assets/images/error/error-image-1-1.png') }}" alt="error-image" class="error-404__image">
                    <h2 class="error-404__title error-404__title--one">5</h2>
                    <h2 class="error-404__title error-404__title--two">0</h2>
                    <h2 class="error-404__title error-404__title--three">0</h2>
                </div><!-- /.error-404__content__top -->
                <h3 class="sec-title__title error-404__sub-title wow fadeInUp animated" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">Server Error</h3><!-- /.error-404__sub-title -->
                <p class="error-404__text wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="50ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 50ms; animation-name: fadeInUp;">
                    Oops! Something went wrong on our end. We're working to fix it.
                </p>
                <a href="{{ route('home') }}" class="citylife-btn wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="100ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                    <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                    </div>
                    <span class="citylife-btn__text">back to home</span>
                </a>
            </div><!-- /.error-404__content -->
        </div><!-- /.container -->
    </section>
</x-base-layout>
