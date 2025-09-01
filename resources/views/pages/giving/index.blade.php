<x-app-layout>
@section('title', 'Giving - CityLife Church')
@section('description', 'Support the ministry of CityLife Church through your generous giving. Discover biblical principles of giving and various ways to contribute.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Giving</h2>
        <p class="page-header__text">Honor the LORD with your possessions and support His work</p>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Giving</span></li>
        </ul>
    </div>
</section>

<!-- Biblical Verses Section -->
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <!-- Verse 1 -->
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Malachi 3:10</h6>
                        <h3 class="section-title__title">"Bring all the tithes into the storehouse..."</h3>
                        <p class="about-one__content__text">
                            "Bring all the tithes into the storehouse, that there may be food in My house, and try Me now in this," Says the LORD of hosts, "If I will not open for you the windows of heaven and pour out for you such blessing that there will not be room enough to receive it."
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}" alt="Biblical Giving" class="img-fluid">
                </div>
            </div>

            <!-- Verse 2 -->
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}" alt="Biblical Giving" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Proverbs 3:9-10</h6>
                        <h3 class="section-title__title">"Honour the LORD with your possessions..."</h3>
                        <p class="about-one__content__text">
                            "Honour the LORD with your possessions, and with the firstfruits of all your increase; so your barns will be filled with plenty and your vats will overflow with new wine."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Verse 3 -->
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Luke 6:38</h6>
                        <h3 class="section-title__title">"Give, and it will be given to you..."</h3>
                        <p class="about-one__content__text">
                            "Give, and it will be given to you: good measure, pressed down, shaken together, and running over will be put into your bosom. For with the same measure that you use, it will be measured back to you."
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}" alt="Biblical Giving" class="img-fluid">
                </div>
            </div>

            <!-- Verse 4 -->
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}" alt="Biblical Giving" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">2 Corinthians 9:7</h6>
                        <h3 class="section-title__title">"God loves a cheerful giver"</h3>
                        <p class="about-one__content__text">
                            "So let each one give as he purposes in his heart, not grudgingly or of necessity; for God loves a cheerful giver."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Giving Methods Section -->
<section class="donate-now section-space-bottom">
    <div class="container">
        <div class="section-title text-center">
            <h6 class="section-title__tagline">Support Our Ministry</h6>
            <h2 class="section-title__title">Ways to Give</h2>
            <p class="section-title__text">
                These are just a few verses showing how God loves His people to be generous (as he is) and how He blesses those who give.
                Giving should not be done grudgingly or as a result of coercion, but if you have a heart to give to the Church and support its work,
                you can donate using the methods below.
            </p>
        </div>

        <div class="row gutter-y-30">
            <!-- Online Giving -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center">
                    <div class="donate-card__icon">
                        <span class="icon-donation"></span>
                    </div>
                    <h3 class="donate-card__title">Online Giving</h3>
                    <p class="donate-card__text">Give securely online using our SumUp payment system. Scan the QR code or click the link below.</p>
                    <div class="donate-card__actions">
                        <a href="https://pay.sumup.com/b2c/Q5WMU9IP" target="_blank" class="cleenhearts-btn">
                            <div class="cleenhearts-btn__icon-box">
                                <div class="cleenhearts-btn__icon-box__inner"><span class="icon-donate"></span></div>
                            </div>
                            <span class="cleenhearts-btn__text">Give via SumUp</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PayPal Giving -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center">
                    <div class="donate-card__icon">
                        <span class="fab fa-paypal"></span>
                    </div>
                    <h3 class="donate-card__title">PayPal Donation</h3>
                    <p class="donate-card__text">Give through PayPal for a quick and secure donation experience.</p>
                    <div class="donate-card__actions">
                        <a href="https://www.paypal.com/donate/?hosted_button_id=4KEE89F86PPQG" target="_blank" class="cleenhearts-btn">
                            <div class="cleenhearts-btn__icon-box">
                                <div class="cleenhearts-btn__icon-box__inner"><span class="fab fa-paypal"></span></div>
                            </div>
                            <span class="cleenhearts-btn__text">Donate via PayPal</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer -->
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center">
                    <div class="donate-card__icon">
                        <span class="icon-bank"></span>
                    </div>
                    <h3 class="donate-card__title">Bank Transfer</h3>
                    <p class="donate-card__text">Transfer directly to our church bank account using the details below.</p>
                    <div class="bank-details">
                        <p><strong>Account Number:</strong> 20057965</p>
                        <p><strong>Sort Code:</strong> 05-08-48</p>
                        <p><strong>Bank Name:</strong> Virgin Money</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gift Aid Section -->
<section class="contact-one section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="section-title text-start">
                    <h6 class="section-title__tagline">Gift Aid</h6>
                    <h2 class="section-title__title">Increase Your Giving Through Gift Aid</h2>
                    <p class="section-title__text">
                        If you are a UK taxpayer we would love for you to increase your giving through Gift Aid.
                        This means that for every £1 you give, we can claim 25p back from HM Revenue & Customs.
                    </p>
                    <p class="section-title__text">
                        <a href="https://www.gov.uk/donating-to-charity/gift-aid" target="_blank" class="text-primary">
                            Learn more about Gift Aid
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-one__form">
                    <div class="contact-one__form__bg" style="background-image: url('{{ asset('assets/images/backgrounds/become-volunteer-bg-1-1.png') }}');"></div>
                    <h3 class="contact-one__title">Gift Aid Declaration</h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="contact-one__form__box form-one" method="POST" action="{{ route('giving.gift-aid') }}">
                        @csrf
                        <div class="row gutter-y-20">
                            <div class="col-md-6">
                                <div class="form-one__control">
                                    <input type="text" name="first_name" placeholder="First Name *"
                                           class="form-one__control__input" value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-one__control">
                                    <input type="text" name="last_name" placeholder="Last Name *"
                                           class="form-one__control__input" value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-one__control">
                                    <input type="text" name="address" placeholder="Address *"
                                           class="form-one__control__input" value="{{ old('address') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-one__control">
                                    <input type="text" name="postcode" placeholder="Postcode *"
                                           class="form-one__control__input" value="{{ old('postcode') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-one__control">
                                    <input type="tel" name="phone" placeholder="Phone Number *"
                                           class="form-one__control__input" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-one__control">
                                    <input type="email" name="email" placeholder="Email Address *"
                                           class="form-one__control__input" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="text" name="gift_aid_code" placeholder="Your Gift Aid Code *"
                                           class="form-one__control__input" value="{{ old('gift_aid_code') }}" required>
                                    <small class="form-text text-muted">
                                        Create your code using two initials + donation type (e.g., JS-tithe, AB-offering, CD-missions)
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="date" name="confirmation_date"
                                           class="form-one__control__input" value="{{ old('confirmation_date') }}" required>
                                    <label class="form-text">Declaration Date</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-one__control">
                                    <div class="form-check">
                                        <input type="checkbox" name="confirm_declaration" value="1"
                                               class="form-check-input" id="confirm_declaration" required {{ old('confirm_declaration') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="confirm_declaration">
                                            I confirm I have paid or will pay an amount of Income Tax and/or Capital Gains Tax for each tax year that is at least equal to the amount of tax that CityLife International will reclaim on my gifts. I understand that if I pay less tax than the amount of Gift Aid claimed, it is my responsibility to pay any difference.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-one__control">
                                    <button type="submit" class="cleenhearts-btn">
                                        <div class="cleenhearts-btn__icon-box">
                                            <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                        </div>
                                        <span class="cleenhearts-btn__text">Submit Declaration</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Information -->
<section class="cta-one section-space-bottom">
    <div class="container">
        <div class="cta-one__inner text-center">
            <h3 class="cta-one__title">Supporting Our Community & Missions</h3>
            <p class="cta-one__text">
                For more information about the work City Life does in supporting the local community and overseas projects,
                please see our "Missions" section. Detailed information on the church's finances can be found on the
                <a href="https://register-of-charities.charitycommission.gov.uk/charity-search/-/charity-details/1052593"
                   target="_blank" class="text-white text-decoration-underline">Charity Commission website</a>.
            </p>
            <p class="cta-one__text">
                <strong>CityLife International is a registered charity (no: 1052593) in England and Wales.</strong>
            </p>
        </div>
    </div>
</section>

<style>
.donate-card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.donate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.donate-card__icon {
    font-size: 3rem;
    color: var(--cleenhearts-primary);
    margin-bottom: 20px;
}

.donate-card__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--cleenhearts-black);
}

.donate-card__text {
    color: #666;
    margin-bottom: 25px;
    line-height: 1.6;
}

.bank-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    border-left: 4px solid var(--cleenhearts-primary);
}

.bank-details p {
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 5px;
}

.form-check {
    text-align: left;
}

.form-check-label {
    font-size: 0.9rem;
    line-height: 1.4;
    color: #555;
    cursor: pointer;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>
</x-app-layout>
