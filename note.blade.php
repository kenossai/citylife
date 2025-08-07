In the course lesson and course enrollment what is course id and user id, I need more explanantion of how the two tables work
<section class="donation-details section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-8">
                <div class="donation-details__details">
                    <div class="donation-details__content">
                        <div class="course-details">
                            @if($course->featured_image)
                            <div class="course-card__image wow fadeInUp animated" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">
                                <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                                <div class="course-details__location">
                                    <span class="icon-location"></span>
                                    <span>{{ $course->location ?? 'Main Campus' }}</span>
                                </div><!-- /.course-details__location -->
                                @if($course->start_date)
                                <div class="course-details__date">
                                    <span>{{ $course->start_date->format('d') }}</span>
                                    <span>{{ $course->start_date->format('M') }}</span>
                                </div><!-- /.course-details__date -->
                                @endif
                            </div><!-- /.course-card__image -->
                            @endif
                            <div class="course-card__content">
                                @if($course->start_date && $course->end_date)
                                @php
                                    $now = now();
                                    $start = $course->start_date;
                                    $end = $course->end_date;
                                    $totalDays = $start->diffInDays($end);
                                    $daysPassed = $start->diffInDays($now);
                                    $progress = $totalDays > 0 ? min(100, max(0, ($daysPassed / $totalDays) * 100)) : 0;
                                @endphp
                                <div class="course-card__progress">
                                    <div class="progress-box">
                                        <div class="progress-box__bar">
                                            <div class="progress-box__bar__inner count-bar counted" data-percent="{{ $progress }}%" style="width: {{ $progress }}%;">
                                                <div class="progress-box__number count-text">{{ round($progress) }}%</div>
                                            </div>
                                        </div><!-- /.progress-box__bar -->
                                    </div><!-- /.progress-box -->
                                    <div class="course-card__progress__bottom">
                                        <h5 class="course-card__progress__title">Course Progress <span>{{ round($progress) }}%</span></h5><!-- /.course-card__progress__title -->
                                        <h5 class="course-card__progress__title">Duration <span>{{ $course->duration_weeks ?? 'TBD' }} weeks</span></h5><!-- /.course-card__progress__title -->
                                    </div><!-- /.course-card__progress__bottom -->
                                </div><!-- /.course-card__progress -->
                                @endif

                                <h3 class="course-card__title">{{ $course->title }}</h3><!-- /.course-card__title -->
                                <div class="course-card-three__text wow fadeInUp" data-wow-duration="1500ms">
                                    <p class="course-card-three__text__inner">{{ $course->description }}</p>
                                    @if($course->content)
                                    <div class="course-card-three__text__inner">{!! nl2br(e($course->content)) !!}</div>
                                    @endif
                                </div><!-- /.course-card-three__text -->
                            </div><!-- /.course-card__content -->
                            <div class="course-details__inner">
                                <div class="donation-details__inner__top">
                                    <h3 class="donation-details__inner__title">Course Information</h3><!-- /.donation-details__inner__title -->
                                    <div class="row">
                                        @if($course->instructor)
                                        <div class="col-md-6">
                                            <p><strong>Instructor:</strong> {{ $course->instructor }}</p>
                                        </div>
                                        @endif
                                        @if($course->schedule)
                                        <div class="col-md-6">
                                            <p><strong>Schedule:</strong> {{ $course->schedule }}</p>
                                        </div>
                                        @endif
                                        @if($course->category)
                                        <div class="col-md-6">
                                            <p><strong>Category:</strong> {{ $course->category }}</p>
                                        </div>
                                        @endif
                                        @if($course->current_enrollments)
                                        <div class="col-md-6">
                                            <p><strong>Current Enrollments:</strong> {{ $course->current_enrollments }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div><!-- /.donation-details__inner__top -->

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
                                    <div class="donation-details__donation__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                                        <div class="donation-details__donation__icon">
                                            <span class="icon-group"></span>
                                        </div><!-- /.donation-details__donation__icon -->
                                        <div class="donation-details__donation__content">
                                            <h4 class="donation-details__donation__title">Enrolled</h4>
                                            <p class="donation-details__donation__text">{{ $course->current_enrollments ?? 0 }}</p>
                                        </div><!-- /.donation-details__donation__content -->
                                    </div><!-- /.donation-details__donation__info -->
                                    @if($course->duration_weeks)
                                    <div class="donation-details__donation__info wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
                                        <div class="donation-details__donation__icon">
                                            <span class="icon-clock"></span>
                                        </div><!-- /.donation-details__donation__icon -->
                                        <div class="donation-details__donation__content">
                                            <h4 class="donation-details__donation__title">Duration</h4>
                                            <p class="donation-details__donation__text">{{ $course->duration_weeks }} weeks</p>
                                        </div><!-- /.donation-details__donation__content -->
                                    </div><!-- /.donation-details__donation__info -->
                                    @endif
                                    @if($course->is_registration_open)
                                    <div class="donation-details__donation__button wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="400ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 400ms; animation-name: fadeInUp;">
                                        <a href="#" class="cleenhearts-btn donation-details__donation__btn">
                                            <div class="cleenhearts-btn__icon-box">
                                                <div class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                                            </div>
                                            <span class="cleenhearts-btn__text">Enroll Now</span>
                                        </a>
                                    </div><!-- /.donation-details__donation__button -->
                                    @else
                                    <div class="donation-details__donation__button wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="400ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 400ms; animation-name: fadeInUp;">
                                        <span class="btn btn-secondary">Registration Closed</span>
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
                            </div><!-- /.course-details__inner -->
                        </div><!-- /.course-details -->
                    </div><!-- /.donation-details__content -->

                    <div class="donation-details__donors">
                        <h3 class="donation-details__donors__title">Related Courses</h3><!-- /.donation-details__donors__title -->
                        <div class="row gutter-y-40">
                            @php
                                $relatedCourses = App\Models\Course::where('category', $course->category)
                                    ->where('id', '!=', $course->id)
                                    ->published()
                                    ->limit(4)
                                    ->get();
                            @endphp

                            @forelse($relatedCourses as $related)
                            <div class="col-xl-3 col-sm-6 wow fadeInUp animated" data-wow-delay="{{ $loop->index * 200 }}ms" data-wow-duration="1500ms">
                                <div class="donation-details__donors__single">
                                    <div class="donation-details__donors__content">
                                        <h3 class="donation-details__donors__name">
                                            <a href="{{ route('courses.show', $related->slug) }}">{{ $related->title }}</a>
                                        </h3>
                                        @if($related->instructor)
                                        <p><small>Instructor: {{ $related->instructor }}</small></p>
                                        @endif
                                        @if($related->duration_weeks)
                                        <p><small>Duration: {{ $related->duration_weeks }} weeks</small></p>
                                        @endif
                                        <p>{{ Str::limit($related->description, 80) }}</p>
                                        @if($related->is_registration_open)
                                            <span class="badge badge-success">Open for Registration</span>
                                        @else
                                            <span class="badge badge-secondary">Registration Closed</span>
                                        @endif
                                    </div><!-- /.donation-details__donors__content -->
                                </div><!-- /.donation-details__donors__single -->
                            </div><!-- /.col-xl-3 col-sm-6 -->
                            @empty
                            <div class="col-12">
                                <p class="text-center text-muted">No related courses available at this time.</p>
                            </div>
                            @endforelse
                        </div><!-- /.row -->
                    </div><!-- /.donation-details__donors -->

                    <div class="comments-form">
                        <h3 class="comments-form__title sec-title__title">Leave a comment</h3><!-- /.comments-form__title -->
                        <form class="comments-form__form contact-form-validated form-one" novalidate="novalidate">
                            <div class="row gutter-y-30">
                                <div class="col-md-6 wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                                    <div class="form-one__control">
                                        <input type="text" name="name" placeholder="Your name" class="form-one__control__input">
                                    </div><!-- /.form-one__control -->
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6 wow fadeInUp animated" data-wow-delay="300ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                                    <div class="form-one__control">
                                        <input type="email" name="email" placeholder="Email address" class="form-one__control__input">
                                    </div><!-- /.form-one__control -->
                                </div><!-- /.col-md-6 -->
                                <div class="col-12 wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 100ms; animation-name: fadeInUp;">
                                    <div class="form-one__control">
                                        <textarea name="message" placeholder="write message . . ." class="form-one__control__input form-one__control__message"></textarea>
                                    </div><!-- /.form-one__control -->
                                </div><!-- /.col-12-->
                                <div class="col-12">
                                    <div class="form-one__control">
                                        <button type="submit" class="cleenhearts-btn">
                                            <span class="cleenhearts-btn__icon-box">
                                                <span class="cleenhearts-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                            </span>
                                            <span class="cleenhearts-btn__text">Post Comment</span>
                                        </button>
                                    </div><!-- /.form-one__control -->
                                </div><!-- /.col-12 -->
                            </div><!-- /.form-one__group -->
                        </form>
                        <div class="result"></div>
                    </div><!-- /.comments-form -->
                </div><!-- /.donation-details__details -->
            </div><!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <aside class="sidebar-donation">
                    <div class="sidebar-donation__organizer sidebar-donation__item">
                        <h3 class="sidebar-donation__title">Course Instructor</h3><!-- /.sidebar-donation__title -->
                        <div class="sidebar-donation__organizer__profile">
                            <div class="sidebar-donation__organizer__image">
                                <img src="{{ asset('assets/images/donations/donation-organizer-1.png') }}" alt="{{ $course->instructor ?? 'Instructor' }}">
                            </div><!-- /.sidebar-donation__organizer__image -->
                            <div class="sidebar-donation__organizer__content">
                                <h4 class="sidebar-donation__organizer__name">{{ $course->instructor ?? 'To Be Announced' }}</h4><!-- /.sidebar-donation__organizer__name -->
                                <span class="sidebar-donation__organizer__designation">Course Instructor</span><!-- /.sidebar-donation__organizer__designation -->
                            </div><!-- /.sidebar-donation__organizer__content -->
                        </div><!-- /.sidebar-donation__organizer__profile -->
                        @if($course->location)
                        <div class="sidebar-donation__organizer__address">
                            <div class="sidebar-donation__organizer__address__title">
                                <span class="sidebar-donation__organizer__address__icon icon-location"></span>
                                <span class="sidebar-donation__organizer__address__text">Location:</span>
                            </div><!-- /.sidebar-donation__organizer__address__title -->
                            <address class="sidebar-donation__organizer__address__address">
                                {{ $course->location }}
                            </address><!-- /.sidebar-donation__organizer__address__address -->
                        </div><!-- /.sidebar-donation__organizer__address -->
                        @endif
                    </div><!-- /.sidebar-donation__organizer -->

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
                            @if($course->current_enrollments)
                            <div class="sidebar-donation__campaings__post">
                                <div class="sidebar-donation__campaings__content">
                                    <div class="sidebar-donation__campaings__meta">Enrolled</div>
                                    <h4 class="sidebar-donation__campaings__title">{{ $course->current_enrollments }} students</h4>
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
