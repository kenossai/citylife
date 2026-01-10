<x-app-layout>
    @section('title', 'Member Registration - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Member Registration</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Register</span></li>
            </ul>
        </div>
    </section>

    <!-- Registration Start -->
    <section class="login-page section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="login-page__image">
                        <img src="{{ asset('assets/images/login/login-1-1.jpg') }}" alt="register">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="login-page__form">
                        <span class="login-page__form__top-title">Complete Your Registration</span>
                        <h2 class="login-page__form__title">Welcome to CityLife Church</h2>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> You've been approved! Complete the form below to create your account.
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form class="login-page__form__box form-one" method="POST" action="{{ route('register.with-token.submit', $token) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="login-page__form__input-box form-one__control">
                                        <input type="text"
                                               name="first_name"
                                               placeholder="First Name"
                                               class="form-one__control__input @error('first_name') is-invalid @enderror"
                                               value="{{ old('first_name') }}"
                                               required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="login-page__form__input-box form-one__control">
                                        <input type="text"
                                               name="last_name"
                                               placeholder="Last Name"
                                               class="form-one__control__input @error('last_name') is-invalid @enderror"
                                               value="{{ old('last_name') }}"
                                               required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="login-page__form__input-box form-one__control mt-3">
                                <input type="email"
                                       name="email"
                                       placeholder="Email address"
                                       class="form-one__control__input @error('email') is-invalid @enderror"
                                       value="{{ $interest->email }}"
                                       readonly
                                       required>
                                <small class="text-muted">This email was pre-filled from your registration request.</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="login-page__form__input-box form-one__control">
                                <input type="tel"
                                       name="phone"
                                       placeholder="Phone Number (Optional)"
                                       class="form-one__control__input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                <small class="text-muted">Only required if you want to receive SMS notifications</small>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="login-page__form__input-box form-one__control">
                                <input type="password"
                                       name="password"
                                       placeholder="Password (minimum 8 characters)"
                                       class="form-one__control__input @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="login-page__form__input-box form-one__control">
                                <input type="password"
                                       name="password_confirmation"
                                       placeholder="Confirm Password"
                                       class="form-one__control__input"
                                       required>
                            </div>

                            <div class="login-page__form__input-box login-page__form__checkbox-forgot">
                                <div class="login-page__form__checkbox">
                                    <input type="checkbox" id="newsletter" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                                    <label for="newsletter">Subscribe to newsletter</label>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="form-one__control">
                                    <div class="gdpr-consent" style="margin-bottom: 20px;">
                                        <label style="display: flex; align-items: flex-start; font-size: 14px; line-height: 1.4; color: #666;">
                                            <input type="checkbox" name="gdpr_consent" value="1" style="margin-right: 10px; margin-top: 2px;" required {{ old('gdpr_consent') ? 'checked' : '' }}>
                                            <span>I consent to CityLife Church collecting and processing my personal data in accordance with our
                                            <a href="{{ route('privacy-policy') }}" style="color: #007bff; text-decoration: underline;" target="_blank">Privacy Policy</a>.
                                            Your data will only be used for church communication and will not be shared with third parties without your consent.</span>
                                        </label>
                                    </div>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="login-page__form__input-box">
                                <button type="submit" class="citylife-btn">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">Register</span>
                                </button>
                            </div>

                            <div class="login-page__form__or">
                                <hr class="login-page__form__or-line">
                                <span class="login-page__form__or-text">Or</span>
                                <hr class="login-page__form__or-line">
                            </div>

                            <div class="login-page__form__signin">
                                <p class="login-page__form__register-text">
                                    Already have an account?
                                    <a href="{{ route('member.login') }}">Login</a>
                                </p>
                                <p class="login-page__form__register-text">
                                    <a href="{{ route('courses.index') }}">← Back to Courses</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Registration End -->
</x-app-layout>
