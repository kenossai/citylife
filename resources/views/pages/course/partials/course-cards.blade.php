@foreach ($courses as $course)
    <div class="col-md-6 course-card-item wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms">
        <div class="donation-card course-card">
            <div class="donation-card__bg" style="background-image: url('assets/images/backgrounds/donation-bg-1-1.png');">
            </div>
            <a href="{{ route('courses.show', $course->id) }}" class="donation-card__image">
                @if($course->featured_image)
                    <img src="{{ asset('media/' . $course->featured_image) }}" alt="{{ $course->title }}">
                @else
                    <img src="{{ $course->featured_image ? asset('media/' . $course->featured_image) : 'assets/images/courses/course-default.jpg' }}" alt="{{ $course->title }}">
                @endif
                <div class="donation-card__category">{{ $course->category }}</div>
            </a>
            <div class="donation-card__content">
                <div class="course-card__meta">
                    <div class="course-meta">
                        <div class="course-meta__item">
                            <i class="icon-clock"></i>
                            <span>{{ $course->duration_weeks }} weeks</span>
                        </div>
                        <div class="course-meta__item">
                            <i class="icon-calendar"></i>
                            <span>{{ $course->start_date ? $course->start_date->format('M d, Y') : 'TBA' }}</span>
                        </div>
                        @if($course->current_enrollments > 0)
                        <div class="course-meta__item">
                            <i class="icon-user"></i>
                            <span>{{ $course->current_enrollments }} enrolled</span>
                        </div>
                        @endif
                    </div>
                </div>

                <h3 class="donation-card__title">
                    <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                </h3>

                <p class="course-card__description">
                    {{ Str::limit($course->description, 120) }}
                </p>

                <div class="course-card__details">
                    <div class="course-instructor">
                        <i class="icon-user-1"></i>
                        <span>{{ $course->instructor }}</span>
                    </div>
                    @if($course->schedule)
                    <div class="course-schedule">
                        <i class="icon-clock"></i>
                        <span>{{ $course->schedule }}</span>
                    </div>
                    @endif
                    @if($course->location)
                    <div class="course-location">
                        <i class="icon-location"></i>
                        <span>{{ $course->location }}</span>
                    </div>
                    @endif
                </div>

                <div class="course-card__actions">
                    <a href="{{ route('courses.show', $course->slug) }}" class="donation-card__btn citylife-btn citylife-btn--border-base">
                        <div class="citylife-btn__icon-box">
                            <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                        </div>
                        <span class="citylife-btn__text">View Course</span>
                    </a>

                    @if($course->is_registration_open)
                        <span class="course-status course-status--open">
                            <i class="icon-check"></i> Registration Open
                        </span>
                    @else
                        <span class="course-status course-status--closed">
                            <i class="icon-close"></i> Registration Closed
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
