<x-app-layout>
@section('title', 'Contact Us - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">Contact Us</h2>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Contact Us</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="contact-one section-space @@extraClassName">
    <div class="container">
        <div class="row gutter-y-30">
            <div class="col-lg-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                <div class="contact-one__map">
                    <div class="google-map contact-one__google__map">
                        <iframe title="template google map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd" class="map__contact-one__google__map" allowfullscreen=""></iframe>
                    </div>
                    <!-- /.google-map -->
                    <div class="contact-one__info">
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-location"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Mailing Address</h4>
                                <address class="contact-one__info__text">901 N Pitt Str., Suite 170 Alexandria, USA</address>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-phone"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Quick Contact</h4>
                                <a href="tel:(406)555-0120" class="contact-one__info__text contact-one__info__text--link">(406) 555-0120</a>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-envelope"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">support email</h4>
                                <a href="mailto:info@cleanheart.com" class="contact-one__info__text contact-one__info__text--link">info@cleanheart.com</a>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                    </div><!-- /.contact-one__info -->
                </div><!-- /.contact-one__map -->
            </div><!-- /.col-lg-6 -->
            <div class="col-lg-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
                <div class="contact-one__form">
                    <div class="contact-one__form__bg" style="background-image: url('assets/images/backgrounds/contact-bg-1-1.png');"></div><!-- /.contact-one__form__bg -->
                    <h2 class="contact-one__title">Leave us a Message</h2>
                    <form class="contact-one__form__inner contact-form-validated form-one wow fadeInUp animated" data-wow-duration="1500ms" action="inc/sendemail.php" novalidate="novalidate" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">
                        <div class="row gutter-y-20">
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="text" name="name" id="name" placeholder="enter your name" class="form-one__control__input">
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="text" name="email" id="email" placeholder="your email" class="form-one__control__input">
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="tel" name="phone" id="phone" placeholder="phone no" class="form-one__control__input">
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <div class="dropdown bootstrap-select"><select class="selectpicker" aria-label="Default select example">
                                        <option selected="">subject</option>
                                        <option value="1">Volunteer</option>
                                        <option value="2">Donations</option>
                                        <option value="3">Foods Support</option>
                                        <option value="4">Education Support</option>
                                        <option value="4">Medical Support</option>
                                        <option value="4">Sports Support</option>
                                    </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-1" aria-haspopup="listbox" aria-expanded="false" title="subject"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">subject</div></div> </div></button><div class="dropdown-menu "><div class="inner show" role="listbox" id="bs-select-1" tabindex="-1"><ul class="dropdown-menu inner show" role="presentation"></ul></div></div></div>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <textarea name="message" id="message" cols="30" rows="10" placeholder="write message . . ." class="form-one__control__input form-one__control__message"></textarea>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="contact-one__btn-box form-one__control">
                                    <button type="submit" class="cleenhearts-btn @@extraClassNameBtn">
                                        <span class="cleenhearts-btn__icon-box">
                                            <span class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                        </span>
                                        <span class="cleenhearts-btn__text">send message</span>
                                    </button>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                        </div><!-- /.row -->
                    </form><!-- /.contact-one__form__inner -->
                </div><!-- /.contact-one__form -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
</x-app-layout>
