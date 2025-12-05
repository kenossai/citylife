
@if($section)
<section class="inspiring-one section-space" style="background-image: url('{{ $section->background_image ? Storage::url('' . $section->background_image) : 'assets/images/backgrounds/inspiring-bg-1-1.png' }}');">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-xl-6">
                <div class="sec-title">
                    <h6 class="sec-title__tagline @@extraClassName">{{ $section->tagline }}</h6><!-- /.sec-title__tagline -->
                    <h3 class="sec-title__title">{{ $section->title }} <span class="sec-title__title__inner">{{ $section->title_highlight }}</span></h3><!-- /.sec-title__title -->
                </div><!-- /.sec-title -->
                <p class="inspiring-one__text">{{ $section->description }}</p><!-- /.inspiring-one__text -->
                <div class="inspiring-one__inner">
                    <a href="{{ route('about') }}" class="contact-information__btn citylife-btn">
                        <div class="citylife-btn__icon-box">
                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                        </div>
                        <span class="citylife-btn__text">{{ $section->new_member_title }}</span>
                    </a><!-- /.contact-information__btn -->
                </div><!-- /.inspiring-one__inner -->
            </div><!-- /.col-xl-6 -->
            <div class="col-xl-6">
                <div class="inspiring-one__image">
                    <div class="inspiring-one__image__inner inspiring-one__image__inner--one wow fadeInRight" data-wow-duration="1500ms" data-wow-delay="00ms">
                        <img src="{{ $section->left_image ? Storage::url('' . $section->left_image) : 'assets/images/inspiring/inspiring-1-1.jpg' }}" alt="inspiring">
                    </div><!-- /.inspiring-one__image__one -->
                    <div class="inspiring-one__image__inner inspiring-one__image__inner--two wow fadeInRight" data-wow-duration="1500ms" data-wow-delay="200ms">
                        <img src="{{ $section->right_image ? Storage::url('' . $section->right_image) : 'assets/images/inspiring/inspiring-1-2.jpg' }}" alt="inspiring">
                    </div><!-- /.inspiring-one__image__two -->
                </div><!-- /.inspiring-one__image -->
            </div><!-- /.col-xl-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
    <div class="inspiring-one__shapes">
        <div class="inspiring-one__shape inspiring-one__shape--one"></div><!-- /.inspiring-one__shape__one -->
        <div class="inspiring-one__shape inspiring-one__shape--two"></div><!-- /.inspiring-one__shape__one -->
    </div><!-- /.inspiring-one__shape -->
</section>
@endif
