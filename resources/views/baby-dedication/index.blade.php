<x-app-layout>
@section('title', 'Baby Dedication')
@section('meta_description', 'Dedicate your baby to God in a special ceremony at City Life Church. Learn about our baby dedication service and register your child.')

{{-- Page Header --}}
<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="page-header__shape-one float-bob-x"></div>
    <div class="page-header__shape-two float-bob-y"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Baby Dedication</h2>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li>Baby Dedication</li>
                </ul>
            </div>
        </div>
    </div>
</section>
{{-- End Page Header --}}

{{-- Baby Dedication Section --}}
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/resources/baby-dedication.jpg') }}" alt="Baby Dedication">
                    <div class="about-one__image__caption">
                        <h3 class="about-one__image__caption__title">Dedicating Children to God</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="sec-title">
                        <div class="sec-title__img">
                            <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
                        </div>
                        <h6 class="sec-title__tagline">A Sacred Commitment</h6>
                        <h3 class="sec-title__title">Baby Dedication at City Life</h3>
                    </div>
                    <p class="about-one__content__text">
                        Baby dedication is a beautiful ceremony where parents publicly commit to raising their child
                        according to Christian principles and values. It's an opportunity for the church family to
                        pledge their support in helping you raise your child in faith.
                    </p>

                    <div class="about-one__content__text">
                        <h4 style="margin-bottom: 15px; color: #2c5aa0;">What Baby Dedication Means</h4>
                        <ul class="list-unstyled about-one__content__list">
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                A commitment by parents to raise their child in Christian faith
                            </li>
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                A pledge by the church community to support the family
                            </li>
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                A celebration of God's gift of life and family
                            </li>
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                A special blessing over the child and family
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Process Section --}}
<section class="feature-one section-space" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="sec-title text-center">
            <div class="sec-title__img">
                <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
            </div>
            <h6 class="sec-title__tagline">Simple Process</h6>
            <h3 class="sec-title__title">How to Register for Baby Dedication</h3>
        </div>

        <div class="row gutter-y-30">
            <div class="col-lg-4 col-md-6">
                <div class="feature-one__item text-center">
                    <div class="feature-one__item__icon">
                        <span style="font-size: 2.5rem; color: #2c5aa0;">1</span>
                    </div>
                    <h4 class="feature-one__item__title">Complete Registration</h4>
                    <p class="feature-one__item__text">
                        Fill out our comprehensive registration form with your baby's and family's information.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-one__item text-center">
                    <div class="feature-one__item__icon">
                        <span style="font-size: 2.5rem; color: #2c5aa0;">2</span>
                    </div>
                    <h4 class="feature-one__item__title">Pastoral Review</h4>
                    <p class="feature-one__item__text">
                        Our pastoral team will review your application and contact you within 2-3 business days.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-one__item text-center">
                    <div class="feature-one__item__icon">
                        <span style="font-size: 2.5rem; color: #2c5aa0;">3</span>
                    </div>
                    <h4 class="feature-one__item__title">Dedication Service</h4>
                    <p class="feature-one__item__text">
                        Attend the scheduled dedication service where your baby will be blessed before the congregation.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Requirements Section --}}
<section class="about-two section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="about-two__content">
                    <div class="sec-title">
                        <div class="sec-title__img">
                            <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
                        </div>
                        <h6 class="sec-title__tagline">Requirements</h6>
                        <h3 class="sec-title__title">What You Need to Know</h3>
                    </div>

                    <div class="about-two__content__text">
                        <h4 style="margin-bottom: 15px; color: #2c5aa0;">Eligibility</h4>
                        <ul class="list-unstyled about-one__content__list">
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                At least one parent should be a regular attendee or member
                            </li>
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                Parents commit to raising the child in Christian faith
                            </li>
                            <li>
                                <span class="about-one__content__list__icon"><i class="tolak-icons-two-check"></i></span>
                                Baby should be under 2 years old (flexible)
                            </li>
                        </ul>

                        <h4 style="margin-bottom: 15px; margin-top: 25px; color: #2c5aa0;">What to Expect</h4>
                        <p>
                            During the dedication service, parents will be asked to make public commitments regarding
                            their child's spiritual upbringing. The pastor will offer a special prayer and blessing
                            over the child and family. Photos and videos are welcome with your consent.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-two__image">
                    <img src="{{ asset('assets/images/resources/family-church.jpg') }}" alt="Family in Church">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="cta-one section-space" style="background-color: #2c5aa0;">
    <div class="container">
        <div class="cta-one__inner">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="cta-one__title" style="color: white;">Ready to Register Your Baby?</h3>
                    <p class="cta-one__text" style="color: white; opacity: 0.9;">
                        Take the first step in your child's spiritual journey. Our registration process is simple and our team is here to help.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('baby-dedication.create') }}" class="tolak-btn tolak-btn--base">
                        <b>Register Now</b><span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="faq-one section-space">
    <div class="container">
        <div class="sec-title text-center">
            <div class="sec-title__img">
                <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
            </div>
            <h6 class="sec-title__tagline">Common Questions</h6>
            <h3 class="sec-title__title">Frequently Asked Questions</h3>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="accrodion-grp faq-one-accrodion" data-grp-name="faq-one-accrodion">

                    <div class="accrodion">
                        <div class="accrodion-title">
                            <h4>What's the difference between baby dedication and baptism?</h4>
                        </div>
                        <div class="accrodion-content">
                            <div class="inner">
                                <p>Baby dedication is a commitment by parents to raise their child in faith, while baptism is a personal decision made by an individual who can understand and choose to follow Christ. We practice believer's baptism by immersion for those old enough to make their own decision.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accrodion active">
                        <div class="accrodion-title">
                            <h4>Do both parents need to be members of City Life?</h4>
                        </div>
                        <div class="accrodion-content">
                            <div class="inner">
                                <p>While we encourage membership, it's not required for both parents. However, at least one parent should be a regular attendee who is committed to raising the child according to Christian principles.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accrodion">
                        <div class="accrodion-title">
                            <h4>When do baby dedications take place?</h4>
                        </div>
                        <div class="accrodion-content">
                            <div class="inner">
                                <p>Baby dedications typically take place during our regular Sunday services, either morning or evening. We schedule them monthly or as needed based on the number of registrations.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accrodion">
                        <div class="accrodion-title">
                            <h4>Can family and friends attend the dedication?</h4>
                        </div>
                        <div class="accrodion-content">
                            <div class="inner">
                                <p>Absolutely! Baby dedication is a celebration for the whole family. We encourage you to invite grandparents, family members, and friends to witness this special moment.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
