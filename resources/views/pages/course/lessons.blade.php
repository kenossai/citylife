<x-app-layout>
    @section('title', 'Lessons - ' . $course->title)

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h3 class="text-white">Course Lessons</h3>
            <h2 class="page-header__title">{{ $course->title }}</h2>
            <p class="section-header__text">Track your progress through the course</p>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses</a></li>
                <li><i class="icon-graduation-cap"></i> <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                <li><span>Lessons</span></li>
            </ul>
        </div>
    </section>

    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="course-lessons">
                        <div class="course-lessons__header mb-5">
                            <h3>Course Progress</h3>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $userEnrollment->progress_percentage }}%"
                                     aria-valuenow="{{ $userEnrollment->progress_percentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ round($userEnrollment->progress_percentage) }}%
                                </div>
                            </div>
                            <p class="text-muted">{{ $userEnrollment->completed_lessons }} of {{ $lessons->count() }} lessons completed</p>
                        </div>

                        <div class="lessons-list">
                            <!-- Quick Quiz Access Section -->
                            <div id="quizzes" class="quiz-section mb-5">
                                <h4 class="mb-3">
                                    <i class="icon-question text-info me-2"></i>Available Quizzes
                                </h4>
                                <div class="row">
                                    @foreach($lessons as $lesson)
                                        @if($lesson->quiz_questions)
                                            @php
                                                $lessonProgress = $progress[$lesson->id] ?? null;
                                                $quizScore = $lessonProgress ? $lessonProgress->quiz_score : null;
                                            @endphp
                                            <div class="col-md-6 mb-3">
                                                <div class="quiz-card">
                                                    <div class="card border-info">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="card-title mb-0">{{ $lesson->title }}</h6>
                                                                @if($quizScore !== null)
                                                                    <span class="badge {{ $quizScore >= 70 ? 'bg-success' : 'bg-warning' }}">
                                                                        {{ round($quizScore) }}%
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <p class="card-text small text-muted mb-3">{{ $lesson->description }}</p>
                                                            <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}"
                                                               class="btn btn-info btn-sm w-100">
                                                                <i class="icon-question"></i>
                                                                @if($quizScore !== null)
                                                                    Retake Quiz
                                                                @else
                                                                    Take Quiz
                                                                @endif
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @if($lessons->whereNotNull('quiz_questions')->count() === 0)
                                    <div class="alert alert-info">
                                        <i class="icon-info-circle me-2"></i>
                                        No quizzes are available for this course yet.
                                    </div>
                                @endif
                            </div>

                            <!-- All Lessons Section -->
                            <h4 class="mb-3">
                                <i class="icon-list text-primary me-2"></i>All Lessons
                            </h4>
                            @foreach($lessons as $lesson)
                                @php
                                    $lessonProgress = $progress[$lesson->id] ?? null;
                                    $isCompleted = $lessonProgress && $lessonProgress->status === 'completed';
                                    $isInProgress = $lessonProgress && $lessonProgress->status === 'in_progress';
                                    $quizScore = $lessonProgress ? $lessonProgress->quiz_score : null;
                                @endphp

                                <div class="lesson-card mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-1">
                                                    <div class="lesson-number">
                                                        <span class="badge {{ $isCompleted ? 'bg-success' : ($isInProgress ? 'bg-warning' : 'bg-secondary') }} rounded-circle p-3">
                                                            @if($isCompleted)
                                                                <i class="icon-check"></i>
                                                            @elseif($isInProgress)
                                                                <i class="icon-play"></i>
                                                            @else
                                                                {{ $lesson->lesson_number }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <h5 class="lesson-title mb-2">{{ $lesson->title }}</h5>
                                                    <p class="lesson-description text-muted mb-2">{{ $lesson->description }}</p>
                                                    <div class="lesson-meta">
                                                        @if($lesson->duration_minutes)
                                                            <span class="badge bg-light text-dark me-2">
                                                                <i class="icon-clock"></i> {{ $lesson->formatted_duration }}
                                                            </span>
                                                        @endif
                                                        @if($lesson->quiz_questions)
                                                            <span class="badge bg-info text-white me-2">
                                                                <i class="icon-question"></i> Quiz Available
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    @if($quizScore !== null)
                                                        <div class="quiz-score text-center">
                                                            <div class="score-badge mb-2">
                                                                <span class="badge {{ $quizScore >= 70 ? 'bg-success' : 'bg-warning' }} p-2">
                                                                    {{ round($quizScore) }}%
                                                                </span>
                                                            </div>
                                                            <small class="text-muted">Quiz Score</small>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="lesson-actions">
                                                        <a href="{{ route('courses.lesson.show', [$course->slug, $lesson->slug]) }}"
                                                           class="btn {{ $isCompleted ? 'btn-outline-success' : 'btn-primary' }} btn-sm mb-2 w-100">
                                                            @if($isCompleted)
                                                                <i class="icon-check"></i> Review
                                                            @elseif($isInProgress)
                                                                <i class="icon-play"></i> Continue
                                                            @else
                                                                <i class="icon-play"></i> Start
                                                            @endif
                                                        </a>

                                                        @if($lesson->quiz_questions)
                                                            <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}"
                                                               class="btn btn-outline-info btn-sm w-100">
                                                                <i class="icon-question"></i>
                                                                @if($quizScore !== null)
                                                                    Retake Quiz ({{ round($quizScore) }}%)
                                                                @else
                                                                    Take Quiz
                                                                @endif
                                                            </a>
                                                        @else
                                                            <span class="btn btn-outline-secondary btn-sm w-100 disabled">
                                                                <i class="icon-info"></i> No Quiz
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="course-sidebar">
                        <div class="course-info card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Course Information</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Instructor:</strong> {{ $course->instructor ?? 'TBA' }}
                                    </li>
                                    <li class="mb-2">
                                        <strong>Duration:</strong> {{ $course->duration_weeks }} weeks
                                    </li>
                                    <li class="mb-2">
                                        <strong>Schedule:</strong> {{ $course->schedule ?? 'Self-paced' }}
                                    </li>
                                    <li class="mb-2">
                                        <strong>Location:</strong> {{ $course->location ?? 'Online' }}
                                    </li>
                                    <li class="mb-2">
                                        <strong>Enrolled:</strong> {{ $userEnrollment->enrollment_date->format('M d, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="course-actions card">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline-primary">
                                        <i class="icon-info"></i> Course Details
                                    </a>
                                    <a href="{{ route('courses.dashboard') }}?email={{ urlencode(session('user_email')) }}" class="btn btn-outline-secondary">
                                        <i class="icon-dashboard"></i> My Courses
                                    </a>
                                    <a href="mailto:{{ config('mail.from.address') }}?subject=Question about {{ $course->title }}" class="btn btn-outline-info">
                                        <i class="icon-envelope"></i> Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .lesson-card {
            transition: all 0.3s ease;
        }

        .lesson-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .lesson-number {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .score-badge .badge {
            font-size: 14px;
            min-width: 50px;
        }

        .progress {
            height: 20px;
        }

        .lesson-actions .btn {
            font-size: 12px;
        }

        .quiz-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
            border-radius: 10px;
            padding: 20px;
            border: 2px dashed #17a2b8;
            margin-bottom: 2rem;
        }

        .quiz-card .card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .quiz-card .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.15);
        }

        .quiz-section h4 {
            color: #17a2b8;
            font-weight: 600;
        }
    </style>
</x-app-layout>
