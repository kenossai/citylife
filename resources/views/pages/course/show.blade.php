<x-app-layout>
    @section('title', 'Courses - ' . $course->title)
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
<section class="donation-details section-space">
    <div class="container">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="icon-check-circle me-3" style="font-size: 1.5rem; color: #28a745;"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Registration Successful!</h5>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="icon-times-circle me-3" style="font-size: 1.5rem; color: #dc3545;"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Registration Error</h5>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- End Flash Messages --}}

        <div class="row gutter-y-50">
            <div class="col-lg-8">
                <div class="donation-details__details">
                    <div class="donation-details__content">
                        <div class="donation-card-three donation-card">
                            @if ($course->featured_image)
                                <div class="donation-card__image wow fadeInUp animated" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">
                                    <img src="{{ asset('storage/' . $course->featured_image) }}" alt="donation details">
                                    <div class="donation-details__hall">
                                        <span>{{ $course->location }}</span>
                                    </div><!-- /.donation-details__hall -->
                                    @if($course->start_date)
                                        <div class="donation-details__date">
                                            <span>{{ $course->start_date->format('d') }}</span>
                                            <span>{{ $course->start_date->format('M') }}</span>
                                        </div><!-- /.donation-details__date -->
                                    @endif
                                </div><!-- /.donation-card__image -->
                            @endif
                            <div class="donation-card__content">
                                @if ($course->start_date && $course->end_date)
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $start = $course->start_date;
                                        $end = $course->end_date;
                                        $totalDays = $start->diffInDays($end);
                                        $daysPassed = $start->diffInDays($now);
                                        $progress = $totalDays > 0 ? min(100, max(0, ($daysPassed / $totalDays) * 100)) : 0;
                                    @endphp
                                    <div class="donation-card__progress">
                                        <div class="progress-box">
                                            <div class="progress-box__bar">
                                                <div class="progress-box__bar__inner count-bar counted" data-percent="{{ $progress }}%" style="width: {{ $progress }}%;">
                                                    <div class="progress-box__number count-text">{{ round($progress) }}%</div>
                                                </div>
                                            </div><!-- /.progress-box__bar -->
                                        </div><!-- /.progress-box -->
                                        <div class="donation-card__progress__bottom">
                                            <h5 class="donation-card__progress__title">Course Progress <span>{{ round($progress) }}%</span></h5><!-- /.donation-card__progress__title -->
                                            <h5 class="donation-card__progress__title">Duration <span>{{ $course->duration_weeks ?? 'TBD' }} weeks</span></h5><!-- /.donation-card__progress__title -->
                                        </div><!-- /.donation-card__progress__bottom -->
                                    </div><!-- /.donation-card__progress -->
                                @endif
                                <h3 class="donation-card__title">{{ $course->title }}</h3><!-- /.donation-card__title -->
                                <div class="donation-card-three__textwow fadeInUp" data-wow-duration="1500ms">
                                    <p class="donation-card-three__text__inner">{{ $course->description }}</p>
                                    <p class="donation-card-three__text__inner">{!! nl2br(e($course->content)) !!}</p>
                                </div><!-- /.donation-card-three__text -->
                            </div><!-- /.donation-card__content -->
                            <div class="donation-details__inner">
                                @if($course->what_you_learn)
                                <div class="course-details__inner__content wow fadeInUp" data-wow-duration="1500ms">
                                    <h4 class="course-details__section-title">What You'll Learn</h4>
                                    <div class="course-details__inner__text">{!! nl2br(e($course->what_you_learn)) !!}</div>
                                </div><!-- /.course-details__inner__content -->
                                @endif

                                @if($course->course_objectives)
                                <div class="course-details__inner__content wow fadeInUp" data-wow-duration="1500ms">
                                    <h4 class="course-details__section-title">Course Objectives</h4>
                                    <div class="course-details__inner__text">{!! nl2br(e($course->course_objectives)) !!}</div>
                                </div><!-- /.course-details__inner__content -->
                                @endif

                                @if($course->requirements)
                                <div class="course-details__inner__content wow fadeInUp" data-wow-duration="1500ms">
                                    <h4 class="course-details__section-title">Requirements</h4>
                                    <div class="course-details__inner__text">{!! nl2br(e($course->requirements)) !!}</div>
                                </div><!-- /.course-details__inner__content -->

                                <div class="donation-details__donation">
                                    @if($isEnrolled)
                                    {{-- User is enrolled - show enrollment status --}}
                                    <div class="donation-details__donation__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                        <div class="donation-details__donation__icon">
                                            <span class="icon-check-circle" style="color: #28a745;"></span>
                                        </div><!-- /.donation-details__donation__icon -->
                                        <div class="donation-details__donation__content">
                                            <h4 class="donation-details__donation__title">Your Status</h4>
                                            <p class="donation-details__donation__text" style="color: #28a745; font-weight: bold;">✓ Enrolled</p>
                                        </div><!-- /.donation-details__donation__content -->
                                    </div><!-- /.donation-details__donation__info -->
                                    @else
                                    {{-- User is not enrolled - show actual enrollment count --}}
                                    <div class="donation-details__donation__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                        <div class="donation-details__donation__icon">
                                            <span class="icon-group"></span>
                                        </div><!-- /.donation-details__donation__icon -->
                                        <div class="donation-details__donation__content">
                                            <h4 class="donation-details__donation__title">Total Enrolled</h4>
                                            <p class="donation-details__donation__text">{{ $actualEnrollmentCount }} {{ $actualEnrollmentCount == 1 ? 'student' : 'students' }}</p>
                                        </div><!-- /.donation-details__donation__content -->
                                    </div><!-- /.donation-details__donation__info -->
                                    @endif
                                    @if($course->duration_weeks)
                                    <div class="donation-details__donation__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
                                        <div class="donation-details__donation__icon">
                                            <span class="icon-clock"></span>
                                        </div><!-- /.donation-details__donation__icon -->
                                        <div class="donation-details__donation__content">
                                            <h4 class="donation-details__donation__title">Duration</h4>
                                            <p class="donation-details__donation__text">{{ $course->duration_weeks }} Weeks</p>
                                        </div><!-- /.donation-details__donation__content -->
                                    </div><!-- /.donation-details__donation__info -->
                                    @endif
                                    @if($isEnrolled)
                                    {{-- User is already enrolled --}}
                                    <div class="donation-details__donation__button wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="400ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 400ms; animation-name: fadeInUp;">
                                        <div class="alert alert-success mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="icon-check-circle me-2" style="font-size: 1.2rem;"></i>
                                                <span><strong>You're enrolled!</strong> Access your lessons and track your progress.</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Course Access Buttons --}}
                                        <div class="enrolled-user-actions">
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('courses.lessons', $course->slug) }}" class="cleenhearts-btn cleenhearts-btn--primary">
                                                    <div class="cleenhearts-btn__icon-box">
                                                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-play"></span></div>
                                                    </div>
                                                    <span class="cleenhearts-btn__text">Access Lessons</span>
                                                </a>
                                                
                                                <a href="{{ route('courses.dashboard') }}?email={{ urlencode(session('user_email')) }}" class="btn btn-outline-primary">
                                                    <i class="icon-dashboard me-2"></i>My Dashboard
                                                </a>
                                            </div>
                                            
                                            @if($userEnrollment)
                                                <div class="enrollment-stats mt-3 p-3 bg-light rounded">
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <div class="stat-item">
                                                                <strong class="d-block">{{ round($userEnrollment->progress_percentage) }}%</strong>
                                                                <small class="text-muted">Progress</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="stat-item">
                                                                <strong class="d-block">{{ $userEnrollment->completed_lessons }}/{{ $course->lessons->count() }}</strong>
                                                                <small class="text-muted">Lessons</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div><!-- /.donation-details__donation__button -->
                                    @elseif($course->is_registration_open)
                                    {{-- Registration is open and user not enrolled --}}
                                    <div class="donation-details__donation__button wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="400ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 400ms; animation-name: fadeInUp;">
                                        <a href="{{ route('courses.register.form', $course->slug) }}" class="cleenhearts-btn donation-details__donation__btn">
                                            <div class="cleenhearts-btn__icon-box">
                                                <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                            </div>
                                            <span class="cleenhearts-btn__text">Register</span>
                                        </a>
                                    </div><!-- /.donation-details__donation__button -->
                                    @else
                                    {{-- Registration is closed --}}
                                    <div class="donation-details__donation__button wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="400ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 400ms; animation-name: fadeInUp;">
                                        <span class="btn btn-secondary">Closed</span>
                                    </div><!-- /.donation-details__donation__button -->
                                    @endif
                                </div><!-- /.donation-details__donation -->
                                @endif
                               @if($course->has_certificate)
                                <div class="donation-details__inner__bottom wow fadeInUp animated" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">
                                    <div class="alert alert-success">
                                        <h4>
                                            <span class="icon-award"></span>
                                            Certificate Available
                                        </h4>
                                        <p>Complete this course and receive a certificate of completion. Minimum attendance required: {{ $course->min_attendance_for_certificate ?? 5 }} sessions.</p>
                                    </div>
                                </div><!-- /.donation-details__inner__bottom -->
                                @endif
                            </div><!-- /.donation-details__inner -->
                        </div><!-- /.donation-card-three donation-card -->
                    </div><!-- /.donation-details__content -->

                    <div class="donation-details__donors">
                        <h3 class="donation-details__donors__title">Recent donors</h3><!-- /.donation-details__donors__title -->
                        <div class="row gutter-y-40">
                            <div class="col-xl-3 col-sm-6 wow fadeInUp animated" data-wow-delay="00ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                <div class="donation-details__donors__single">
                                    <div class="donation-details__donors__image">
                                        <img src="assets/images/donations/donor-d-1.png" alt="donor details">
                                    </div><!-- /.donation-details__donors__image -->
                                    <div class="donation-details__donors__content">
                                        <h3 class="donation-details__donors__name"><a href="volunteer-details.html">Diego C. Rapoza</a></h3><!-- /.donation-details__donors__name -->
                                        <h4 class="donation-details__donors__amount">$69</h4><!-- /.donation-details__donors__amount -->
                                    </div><!-- /.donation-details__donors__content -->
                                </div><!-- /.donation-details__donors__single -->
                            </div><!-- /.col-xl-3 col-sm-6 -->
                        </div><!-- /.row -->
                    </div><!-- /.donation-details__recent -->
                </div><!-- /.donation-details__details -->
            </div><!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <aside class="sidebar-donation">
                    <div class="sidebar-donation__organizer sidebar-donation__item">
                        <h3 class="sidebar-donation__title">Course Teachers</h3><!-- /.sidebar-donation__title -->
                        <div class="sidebar-donation__organizer__profile">
                            <div class="sidebar-donation__organizer__image">
                                <img src="assets/images/donations/donation-organizer-1.png" alt="">
                            </div><!-- /.sidebar-donation__organizer__image -->
                            <div class="sidebar-donation__organizer__content">
                                <h4 class="sidebar-donation__organizer__name"><a href="volunteer-details.html">{{ $course->instructor }}</a></h4><!-- /.sidebar-donation__organizer__name -->
                                <span class="sidebar-donation__organizer__designation">Walkers Ridge</span><!-- /.sidebar-donation__organizer__designation -->
                            </div><!-- /.sidebar-donation__organizer__content -->
                        </div><!-- /.sidebar-donation__organizer__profile -->
                        @if($course->location)
                            <div class="sidebar-donation__organizer__address">
                                <div class="sidebar-donation__organizer__address__title">
                                    <span class="sidebar-donation__organizer__address__icon icon-location"></span>
                                    <span class="sidebar-donation__organizer__address__text">Address:</span>
                                </div><!-- /.sidebar-donation__organizer__address__title -->
                                <address class="sidebar-donation__organizer__address__address">
                                    {{ $course->location }}
                                </address><!-- /.sidebar-donation__organizer__address__address -->
                            </div><!-- /.sidebar-donation__organizer__address -->
                        @endif
                    </div><!-- /.sidebar-donation__organizer -->

                    <div class="sidebar-donation__campaings sidebar-donation__item">
                        <h3 class="sidebar-donation__title">Related Courses</h3><!-- /.sidebar-donation__title -->
                        <div class="sidebar-donation__campaings__inner">
                            @php
                                $relatedCourses = App\Models\Course::where('category', $course->category)
                                    ->where('slug', '!=', $course->slug)
                                    ->published()
                                    ->limit(4)
                                    ->get();
                            @endphp
                            @foreach($relatedCourses as $relatedCourse)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__image">
                                    <img src="{{ $relatedCourse->featured_image }}" alt="Campaings Post">
                                </div><!-- /.sidebar-donation__campaings__image -->
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">{{ $relatedCourse->start_date->format('M d, Y') }}</div><!-- /.sidebar-donation__campaings__meta -->
                                    <h4 class="sidebar-donation__campaings__title"><a href="{{ route('courses.show', $relatedCourse->slug) }}">{{ $relatedCourse->title }}</a></h4><!-- /.sidebar-donation__campaings__title -->
                                </div><!-- /.sidebar-donation__campaings__content -->
                            </div><!-- /.sidebar-donation__campaings__post -->
                            @endforeach

                        </div><!-- /.sidebar-donation__campaings__inner -->
                    </div><!-- /.sidebar-donation__campaings -->
                    <div class="sidebar-donation__campaings sidebar-donation__item">
                        <h3 class="sidebar-donation__title">Course Details</h3><!-- /.sidebar-donation__title -->
                        <div class="sidebar-donation__campaings__inner">
                            @if($course->start_date)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">Start Date</div>
                                    <h4 class="sidebar-donation__campaings__title">{{ $course->start_date->format('M d, Y') }}</h4>
                                </div><!-- /.sidebar-donation__campaings__content -->
                            </div><!-- /.sidebar-donation__campaings__post -->
                            @endif
                            @if($course->end_date)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">End Date</div>
                                    <h4 class="sidebar-donation__campaings__title">{{ $course->end_date->format('M d, Y') }}</h4>
                                </div><!-- /.sidebar-donation__campaings__content -->
                            </div><!-- /.sidebar-donation__campaings__post -->
                            @endif
                            @if($actualEnrollmentCount > 0)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">Total Enrolled</div>
                                    <h4 class="sidebar-donation__campaings__title">{{ $actualEnrollmentCount }} {{ $actualEnrollmentCount == 1 ? 'student' : 'students' }}</h4>
                                </div><!-- /.sidebar-donation__campaings__content -->
                            </div><!-- /.sidebar-donation__campaings__post -->
                            @endif
                            @if($isEnrolled && $userEnrollment)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">Your Enrollment</div>
                                    <h4 class="sidebar-donation__campaings__title" style="color: #28a745;">
                                        <i class="icon-check-circle"></i> Registered on {{ $userEnrollment->enrollment_date->format('M d, Y') }}
                                    </h4>
                                </div><!-- /.sidebar-donation__campaings__content -->
                            </div><!-- /.sidebar-donation__campaings__post -->
                            @endif
                        </div><!-- /.sidebar-donation__campaings__inner -->
                    </div><!-- /.sidebar-donation__campaings -->
                    <div class="sidebar__categories-wrapper sidebar__single sidebar-donation__item">
                        <h4 class="sidebar__title">Course Categories</h4><!-- /.sidebar__title -->
                        <ul class="sidebar__categories list-unstyled">
                            @php
                                $categories = App\Models\Course::published()
                                    ->select('category')
                                    ->groupBy('category')
                                    ->get()
                                    ->pluck('category');
                            @endphp
                            @foreach($categories as $category)
                            @php
                                $categoryCount = App\Models\Course::published()->where('category', $category)->count();
                            @endphp
                            <li>
                                <a href="{{ route('courses.index', ['category' => $category]) }}">
                                    <span>{{ $category }}</span>
                                    <span>({{ $categoryCount }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul><!-- /.sidebar__categories list-unstyled -->
                    </div><!-- /.sidebar__categories-wrapper sidebar__single -->
                </aside><!-- /.sidebar-donation -->
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
</x-app-layout>
