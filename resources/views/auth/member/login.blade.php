<x-app-layout>
    @section('title', 'Member Login - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Member Login</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Login</span></li>
            </ul>
        </div>
    </section>

    <!-- Login Start -->
    <section class="login-page section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="login-page__image">
                        <img src="{{ asset('assets/images/login/login-1-1.jpg') }}" alt="login">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="login-page__form">
                        <span class="login-page__form__top-title">Member Login</span>
                        <h2 class="login-page__form__title">Welcome back to our community</h2>

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

                        <div class="alert alert-info">
                            <strong>Existing Members:</strong> If you're an existing member, your default password is <code>password123</code>. Please change it after logging in.
                        </div>

                        <form class="login-page__form__box form-one" method="POST" action="{{ route('member.login.submit') }}">
                            @csrf
                            <div class="login-page__form__input-box form-one__control">
                                <input type="email"
                                       name="email"
                                       placeholder="Email address"
                                       class="form-one__control__input @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="login-page__form__input-box form-one__control">
                                <input type="password"
                                       name="password"
                                       placeholder="Password"
                                       class="form-one__control__input @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="login-page__form__input-box login-page__form__checkbox-forgot">
                                <div class="login-page__form__checkbox">
                                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">Remember me</label>
                                </div>
                                <a href="#" class="login-page__form__forgot">Forgot your password?</a>
                            </div>

                            <div class="login-page__form__input-box">
                                <button type="submit" class="citylife-btn">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">Log In</span>
                                </button>
                            </div>

                            <div class="login-page__form__or">
                                <hr class="login-page__form__or-line">
                                <span class="login-page__form__or-text">Or</span>
                                <hr class="login-page__form__or-line">
                            </div>

                            <div class="login-page__form__signin">
                                <p class="login-page__form__register-text">
                                    Don't have an account?
                                    <a type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-registration-modal'))">Register as Member</a>
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
    <!-- Login End -->
</x-app-layout>
