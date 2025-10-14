<x-app-layout>
@section('title', 'Registration Successful - Baby Dedication')
@section('meta_description', 'Your baby dedication registration has been submitted successfully. We will contact you soon to schedule the ceremony.')

{{-- Page Header --}}
<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="page-header__shape-one float-bob-x"></div>
    <div class="page-header__shape-two float-bob-y"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Registration Successful</h2>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><a href="{{ route('baby-dedication.index') }}">Baby Dedication</a></li>
                    <li><span class="sep">.</span></li>
                    <li>Success</li>
                </ul>
            </div>
        </div>
    </div>
</section>
{{-- End Page Header --}}

{{-- Success Section --}}
<section class="contact-one section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="contact-one__content text-center">

                    {{-- Success Icon --}}
                    <div class="mb-4">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 100px; height: 100px; background-color: #d4edda; border-radius: 50%; margin-bottom: 1rem;">
                            <i class="fas fa-check" style="font-size: 3rem; color: #155724;"></i>
                        </div>
                    </div>

                    <div class="sec-title">
                        <div class="sec-title__img">
                            <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
                        </div>
                        <h6 class="sec-title__tagline">Thank You!</h6>
                        <h3 class="sec-title__title">Registration Submitted Successfully</h3>
                    </div>

                    <div class="contact-one__content__text">
                        <p style="font-size: 1.1rem; margin-bottom: 2rem;">
                            Your baby dedication registration has been received and is being reviewed by our pastoral team.
                            We're excited to celebrate this special milestone with your family!
                        </p>

                        <div class="row gutter-y-30" style="margin-bottom: 2rem;">
                            <div class="col-md-4">
                                <div class="feature-one__item text-center">
                                    <div class="feature-one__item__icon">
                                        <i class="fas fa-clock" style="font-size: 2rem; color: #2c5aa0;"></i>
                                    </div>
                                    <h5 class="feature-one__item__title">What's Next?</h5>
                                    <p class="feature-one__item__text">
                                        Our team will review your application within 2-3 business days.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="feature-one__item text-center">
                                    <div class="feature-one__item__icon">
                                        <i class="fas fa-phone" style="font-size: 2rem; color: #2c5aa0;"></i>
                                    </div>
                                    <h5 class="feature-one__item__title">We'll Contact You</h5>
                                    <p class="feature-one__item__text">
                                        We'll call or email you to schedule the dedication service.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="feature-one__item text-center">
                                    <div class="feature-one__item__icon">
                                        <i class="fas fa-calendar" style="font-size: 2rem; color: #2c5aa0;"></i>
                                    </div>
                                    <h5 class="feature-one__item__title">Dedication Day</h5>
                                    <p class="feature-one__item__text">
                                        Celebrate as your baby is dedicated during our service.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
                            <h5 style="color: #2c5aa0; margin-bottom: 1rem;">
                                <i class="fas fa-info-circle"></i> Important Information
                            </h5>
                            <ul style="margin: 0; padding-left: 1.5rem; color: #666;">
                                <li style="margin-bottom: 0.5rem;">Please check your email (including spam folder) for confirmation</li>
                                <li style="margin-bottom: 0.5rem;">If you don't hear from us within 3 business days, please contact the church office</li>
                                <li style="margin-bottom: 0.5rem;">We may need to schedule a brief meeting before the dedication service</li>
                                <li style="margin-bottom: 0.5rem;">Feel free to invite family and friends to witness this special moment</li>
                                <li>If you have any questions, please don't hesitate to contact us</li>
                            </ul>
                        </div>

                        <div style="background-color: #e3f2fd; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
                            <h5 style="color: #1976d2; margin-bottom: 1rem;">
                                <i class="fas fa-heart"></i> Preparing for Dedication
                            </h5>
                            <p style="margin: 0; color: #666;">
                                While you wait for our call, you might want to think about the commitments you'll be making during the
                                dedication service. This is a wonderful time to reflect on your hopes and prayers for your child's
                                spiritual journey. We're here to support you every step of the way!
                            </p>
                        </div>
                    </div>

                    <div class="contact-one__content__btn">
                        <div class="row gutter-y-20">
                            <div class="col-md-6">
                                <a href="{{ route('baby-dedication.index') }}" class="tolak-btn tolak-btn--base">
                                    <b>Learn More About Dedication</b><span></span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('home') }}" class="tolak-btn tolak-btn--base-two">
                                    <b>Return to Home</b><span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact Information Section --}}
<section class="contact-info section-space" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="sec-title text-center">
            <div class="sec-title__img">
                <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
            </div>
            <h6 class="sec-title__tagline">Need Help?</h6>
            <h3 class="sec-title__title">Contact Our Team</h3>
        </div>

        <div class="row gutter-y-30">
            <div class="col-lg-4 col-md-6">
                <div class="contact-info__item text-center">
                    <div class="contact-info__item__icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4 class="contact-info__item__title">Call Us</h4>
                    <p class="contact-info__item__text">
                        <a href="tel:+441234567890">+44 123 456 7890</a>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-info__item text-center">
                    <div class="contact-info__item__icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4 class="contact-info__item__title">Email Us</h4>
                    <p class="contact-info__item__text">
                        <a href="mailto:pastoral@citylife.church">pastoral@citylife.church</a>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-info__item text-center">
                    <div class="contact-info__item__icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="contact-info__item__title">Office Hours</h4>
                    <p class="contact-info__item__text">
                        Mon-Fri: 9:00 AM - 5:00 PM<br>
                        Sat-Sun: By appointment
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
