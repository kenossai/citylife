<x-app-layout>
    @section('title', 'Contact ' . $ministry->name . ' - City Life Church')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Contact {{ $ministry->name }}</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('ministries.index') }}">Ministries</a></li>
                <li><a href="{{ route('ministries.show', $ministry->slug) }}">{{ $ministry->name }}</a></li>
                <li><span>Contact</span></li>
            </ul>
        </div>
    </section>

    <!-- Contact Form Start -->
    <section class="contact-page section-space">
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-lg-8">
                    <div class="contact-page__form">
                        <div class="section-title">
                            <h6 class="section-title__tagline">Get Involved</h6>
                            <h2 class="section-title__title">Join {{ $ministry->name }}</h2>
                            <p class="section-title__text">
                                Interested in joining our {{ $ministry->name }}? Fill out the form below and we'll get back to you soon.
                            </p>
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

                        <form class="contact-page__form__box form-one" method="POST" action="{{ route('ministries.contact.submit', $ministry->slug) }}">
                            @csrf
                            <div class="row gutter-y-20">
                                <div class="col-md-6">
                                    <div class="form-one__control">
                                        <input type="text" 
                                               name="name" 
                                               placeholder="Your Name"
                                               class="form-one__control__input @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-one__control">
                                        <input type="email" 
                                               name="email" 
                                               placeholder="Email Address"
                                               class="form-one__control__input @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-one__control">
                                        <input type="tel" 
                                               name="phone" 
                                               placeholder="Phone Number (Optional)"
                                               class="form-one__control__input @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-one__control">
                                        <textarea name="message" 
                                                  placeholder="Tell us about your interest in this ministry..."
                                                  class="form-one__control__input @error('message') is-invalid @enderror"
                                                  rows="6"
                                                  required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-one__control">
                                        <button type="submit" class="citylife-btn">
                                            <div class="citylife-btn__icon-box">
                                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                            </div>
                                            <span class="citylife-btn__text">Send Message</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Ministry Info -->
                        <div class="sidebar__single sidebar__info">
                            <h4 class="sidebar__title">{{ $ministry->name }}</h4>
                            @if($ministry->featured_image)
                                <img src="{{ Storage::url($ministry->featured_image) }}" alt="{{ $ministry->name }}" class="img-fluid mb-3">
                            @endif
                            <p>{{ $ministry->description }}</p>
                            
                            <ul class="sidebar__info-list">
                                @if($ministry->leader)
                                <li>
                                    <i class="icon-user"></i>
                                    <span class="sidebar__info-label">Leader:</span>
                                    <span class="sidebar__info-text">{{ $ministry->leader }}</span>
                                </li>
                                @endif
                                
                                @if($ministry->meeting_time)
                                <li>
                                    <i class="icon-clock"></i>
                                    <span class="sidebar__info-label">Meeting Time:</span>
                                    <span class="sidebar__info-text">{{ $ministry->meeting_time }}</span>
                                </li>
                                @endif
                                
                                @if($ministry->meeting_location)
                                <li>
                                    <i class="icon-location"></i>
                                    <span class="sidebar__info-label">Location:</span>
                                    <span class="sidebar__info-text">{{ $ministry->meeting_location }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Contact Info -->
                        @if($ministry->contact_email)
                        <div class="sidebar__single sidebar__contact">
                            <h4 class="sidebar__title">Direct Contact</h4>
                            <div class="sidebar__contact-info">
                                <a href="mailto:{{ $ministry->contact_email }}">
                                    <i class="icon-email"></i>{{ $ministry->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Back to Ministry -->
                        <div class="sidebar__single">
                            <a href="{{ route('ministries.show', $ministry->slug) }}" class="citylife-btn">
                                <div class="citylife-btn__icon-box">
                                    <div class="citylife-btn__icon-box__inner"><span class="icon-left-arrow"></span></div>
                                </div>
                                <span class="citylife-btn__text">Back to Ministry</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Form End -->
</x-app-layout>
