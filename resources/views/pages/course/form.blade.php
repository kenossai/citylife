<x-app-layout>
    @section('title', 'Registration Form')
    @section('description', 'Register for the course')
    @section('keywords', 'course, registration, form')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <!-- /.page-header__bg -->
        <div class="container">
            <h3 class="text-white">Our Courses</h3>
            <h2 class="page-header__title">{{ $course->title }}</h2>
            <p class="section-header__text">{{ $course->description }}</p>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses List</a></li>
                <li><span>{{ $course->title }}</span></li>
            </ul><!-- /.thm-breadcrumb list-unstyled -->
        </div><!-- /.container -->
    </section>
    <section class="become-volunteer section-space">
            <div class="container">
                <div class="row gutter-y-50">
                    <div class="col-lg-12 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                                                @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('courses.register', $course->slug) }}" method="POST" class="become-volunteer__form form-one">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">

                            <div class="become-volunteer__form__bg" style="background-image: url('{{ asset('assets/images/backgrounds/become-volunteer-bg-1-1.png') }}');"></div><!-- /.become-volunteer__form__bg -->
                            <h3 class="become-volunteer__form__title">Register for {{ $course->title }}</h3><!-- /.become-volunteer__form__title -->

                            <!-- Course Info Banner -->
                            <div class="course-info-banner mb-4 p-3 bg-light rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Instructor:</strong> {{ $course->instructor }}</p>
                                        <p class="mb-1"><strong>Duration:</strong> {{ $course->duration_weeks }} weeks</p>
                                        <p class="mb-0"><strong>Schedule:</strong> {{ $course->schedule }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Start Date:</strong> {{ $course->start_date?->format('M d, Y') }}</p>
                                        <p class="mb-1"><strong>End Date:</strong> {{ $course->end_date?->format('M d, Y') }}</p>
                                        <p class="mb-0"><strong>Location:</strong> {{ $course->location }}</p>
                                    </div>
                                </div>
                                @if($course->requirements)
                                    <div class="mt-2">
                                        <p class="mb-0"><strong>Requirements:</strong> {{ $course->requirements }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="become-volunteer__form__inner">
                                <!-- Personal Information -->
                                <h5 class="mb-3 text-primary">Personal Information</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-one__control mb-3">
                                            <input type="text" name="first_name" id="first_name" placeholder="First Name *"
                                                   class="form-one__control__input @error('first_name') is-invalid @enderror"
                                                   value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div><!-- /.form-one__control -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-one__control mb-3">
                                            <input type="text" name="last_name" id="last_name" placeholder="Last Name *"
                                                   class="form-one__control__input @error('last_name') is-invalid @enderror"
                                                   value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div><!-- /.form-one__control -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-one__control mb-3">
                                            <input type="email" name="email" id="email" placeholder="Email Address *"
                                                   class="form-one__control__input @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div><!-- /.form-one__control -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-one__control mb-3">
                                            <input type="tel" name="phone" id="phone" placeholder="Phone Number *"
                                                   class="form-one__control__input @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div><!-- /.form-one__control -->
                                    </div>
                                </div>

                                <div class="form-one__control mb-3">
                                    <select name="membership_status" id="membership_status"
                                            class="form-one__control__input @error('membership_status') is-invalid @enderror" required>
                                        <option value="">Select your church status *</option>
                                        <option value="visitor" {{ old('membership_status') == 'visitor' ? 'selected' : '' }}>First time visitor</option>
                                        <option value="regular_attendee" {{ old('membership_status') == 'regular_attendee' ? 'selected' : '' }}>Regular attendee</option>
                                        <option value="member" {{ old('membership_status') == 'member' ? 'selected' : '' }}>Church member</option>
                                    </select>
                                    @error('membership_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div><!-- /.form-one__control -->

                                <!-- Emergency Contact -->
                                <h5 class="mb-3 mt-4 text-primary">Emergency Contact</h5>

                                <div class="form-one__control mb-3">
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                                           placeholder="Emergency Contact Name *"
                                           class="form-one__control__input @error('emergency_contact_name') is-invalid @enderror"
                                           value="{{ old('emergency_contact_name') }}" required>
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control mb-3">
                                    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship"
                                           placeholder="Relationship (e.g., spouse, parent, friend)"
                                           class="form-one__control__input @error('emergency_contact_relationship') is-invalid @enderror"
                                           value="{{ old('emergency_contact_relationship') }}">
                                    @error('emergency_contact_relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div><!-- /.form-one__control -->

                                <!-- Terms Agreement -->
                                <div class="form-one__control mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="terms_agreement" id="terms_agreement"
                                               class="form-check-input @error('terms_agreement') is-invalid @enderror"
                                               value="1" {{ old('terms_agreement') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms_agreement">
                                            I agree to the course requirements and understand the commitment involved *
                                        </label>
                                        @error('terms_agreement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div><!-- /.form-one__control -->

                                <div class="become-volunteer__form__bottom form-one__control">
                                    <button type="submit" class="cleenhearts-btn cleenhearts-btn--border-base">
                                        <span class="cleenhearts-btn__icon-box">
                                            <span class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                        </span>
                                        <span class="cleenhearts-btn__text">Register for Course</span>
                                    </button>

                                    <!-- Course Stats -->
                                    <div class="course-stats mt-3">
                                        <p class="text-muted mb-0">
                                            <i class="icon-user"></i> {{ $course->current_enrollments }} students enrolled
                                            @if($course->has_certificate)
                                                | <i class="icon-award"></i> Certificate available
                                            @endif
                                        </p>
                                    </div>
                                </div><!-- /.become-volunteer__form__bottom -->
                            </div><!-- /.become-volunteer__form__inner -->
                        </form>
                    </div><!-- /.col-lg-12 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section>
</x-app-layout>
