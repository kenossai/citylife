<x-app-layout>
    @section('title', 'Lessons - ' . $course->title)

    <!-- Modern Course Header -->
    <div class="course-header">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-md-8">
                    <div class="course-info">
                        <div class="breadcrumb-modern mb-2">
                            <a href="{{ route('home') }}" class="breadcrumb-link">
                                <i class="icon-home"></i> Home
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.index') }}" class="breadcrumb-link">
                                <i class="icon-book"></i> Courses
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <span class="breadcrumb-current">{{ $course->title }}</span>
                        </div>
                        <h2 class="course-title mb-2">{{ $course->title }}</h2>
                        <p class="course-subtitle">Track your progress through the course</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ round($userEnrollment->progress_percentage) }}%</div>
                            <div class="stat-label">Progress</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $userEnrollment->completed_lessons }}/{{ $lessons->count() }}</div>
                            <div class="stat-label">Lessons</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="lessons-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Progress Overview Card -->
                    <div class="progress-card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">Course Progress</h4>
                        </div>
                        <div class="card-body">
                            <div class="progress-info mb-3">
                                <div class="progress-text">{{ round($userEnrollment->progress_percentage) }}% Complete</div>
                                <div class="lessons-count">{{ $userEnrollment->completed_lessons }}/{{ $lessons->count() }} lessons</div>
                            </div>
                            <div class="progress-bar-modern">
                                <div class="progress-fill" style="width: {{ $userEnrollment->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Quizzes Section -->
                    <div id="quizzes" class="quiz-section mb-5">
                        <div class="section-header">
                            <h4><i class="icon-question text-primary me-2"></i>Available Quizzes</h4>
                        </div>
                        <div class="row">
                            @foreach($lessons as $lesson)
                                @if($lesson->quiz_questions)
                                    @php
                                        $lessonProgress = $progress[$lesson->id] ?? null;
                                        $quizScore = $lessonProgress ? $lessonProgress->quiz_score : null;
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="modern-quiz-card">
                                            <div class="quiz-header">
                                                @if($quizScore !== null)
                                                    <span class="quiz-badge {{ $quizScore >= 70 ? 'badge-success' : 'badge-warning' }}">
                                                        {{ round($quizScore) }}%
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="quiz-body">
                                                <h6 class="quiz-title">{{ $lesson->title }}</h6>
                                                <p class="quiz-description">{{ Str::limit($lesson->description, 80) }}</p>
                                                <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}"
                                                   class="btn-modern btn-primary w-100">
                                                    <i class="icon-question me-2"></i>
                                                    @if($quizScore !== null)
                                                        Retake Quiz
                                                    @else
                                                        Take Quiz
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if($lessons->whereNotNull('quiz_questions')->count() === 0)
                            <div class="empty-quiz-state">
                                <i class="icon-info-circle"></i>
                                <p>No quizzes are available for this course yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- All Lessons Section -->
                    <div class="lessons-section">
                        <div class="section-header">
                            <h4><i class="icon-list text-primary me-2"></i>All Lessons</h4>
                        </div>
                        <div class="lessons-grid">
                            @foreach($lessons as $lesson)
                                @php
                                    $lessonProgress = $progress[$lesson->id] ?? null;
                                    $isCompleted = $lessonProgress && $lessonProgress->status === 'completed';
                                    $isInProgress = $lessonProgress && $lessonProgress->status === 'in_progress';
                                    $quizScore = $lessonProgress ? $lessonProgress->quiz_score : null;
                                @endphp

                                <div class="modern-lesson-card">
                                    <div class="lesson-status">
                                        <div class="lesson-number {{ $isCompleted ? 'completed' : ($isInProgress ? 'in-progress' : 'not-started') }}">
                                            @if($isCompleted)
                                                <i class="icon-check"></i>
                                            @elseif($isInProgress)
                                                <i class="icon-play"></i>
                                            @else
                                                {{ $lesson->lesson_number }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="lesson-content">
                                        <h5 class="lesson-title">{{ $lesson->title }}</h5>
                                        <p class="lesson-description">{{ $lesson->description }}</p>
                                        <div class="lesson-meta">
                                            @if($lesson->duration_minutes)
                                                <span class="meta-badge">
                                                    <i class="icon-clock"></i> {{ $lesson->formatted_duration }}
                                                </span>
                                            @endif
                                            @if($lesson->quiz_questions)
                                                <span class="meta-badge quiz-badge">
                                                    <i class="icon-question"></i> Quiz Available
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="lesson-actions">
                                        <a href="{{ route('courses.lesson.show', [$course->slug, $lesson->slug]) }}"
                                           class="btn-modern btn-primary">
                                            @if($isCompleted)
                                                <i class="icon-refresh me-2"></i>Review
                                            @elseif($isInProgress)
                                                <i class="icon-play me-2"></i>Continue
                                            @else
                                                <i class="icon-play me-2"></i>Start
                                            @endif
                                        </a>
                                        @if($lesson->quiz_questions)
                                            <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}"
                                               class="btn-modern btn-outline">
                                                <i class="icon-question"></i>
                                                @if($quizScore)
                                                    {{ round($quizScore) }}%
                                                @else
                                                    Quiz
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="course-sidebar">
                        <!-- Course Info Card -->
                        <div class="modern-sidebar-card mb-4">
                            <div class="card-header">
                                <h5>Course Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="course-detail">
                                    <i class="icon-book-open text-primary"></i>
                                    <div>
                                        <span class="detail-label">Total Lessons</span>
                                        <span class="detail-value">{{ $lessons->count() }}</span>
                                    </div>
                                </div>
                                <div class="course-detail">
                                    <i class="icon-clock text-success"></i>
                                    <div>
                                        <span class="detail-label">Duration</span>
                                        <span class="detail-value">{{ $course->formatted_duration ?? 'Self-paced' }}</span>
                                    </div>
                                </div>
                                <div class="course-detail">
                                    <i class="icon-award text-warning"></i>
                                    <div>
                                        <span class="detail-label">Certificate</span>
                                        <span class="detail-value">{{ $userEnrollment->certificate_issued ? 'Earned' : 'Available' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="modern-sidebar-card">
                            <div class="card-header">
                                <h5>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('courses.show', $course->slug) }}"
                                   class="btn-modern btn-outline w-100 mb-3">
                                    <i class="icon-info me-2"></i>Course Details
                                </a>
                                @if($userEnrollment->progress_percentage == 100 && !$userEnrollment->certificate_issued)
                                    <button class="btn-modern btn-primary w-100">
                                        <i class="icon-download me-2"></i>Download Certificate
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Modern Lessons Page Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Course Header */
        .course-header {
            background: #351c42;
            color: white;
            margin-bottom: 2rem;
        }

        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-link:hover {
            color: white;
        }

        .breadcrumb-separator {
            color: rgba(255, 255, 255, 0.6);
        }

        .breadcrumb-current {
            color: white;
            font-weight: 500;
        }

        .course-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .course-subtitle {
            opacity: 0.9;
            margin: 0;
            color: white;
        }

        .course-stats {
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        /* Content Cards */
        .progress-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem 1.5rem 0;
            background: transparent;
            border: none;
        }

        .card-header h4 {
            font-weight: 600;
            margin: 0;
            color: #1f2937;
        }

        .card-body {
            padding: 1.5rem;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .progress-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }

        .lessons-count {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .progress-bar-modern {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Section Headers */
        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-header h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        /* Quiz Cards */
        .modern-quiz-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .modern-quiz-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .quiz-header {
            padding: 1rem 1rem 0;
            display: flex;
            justify-content: flex-end;
        }

        .quiz-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .quiz-body {
            padding: 0 1rem 1.5rem;
        }

        .quiz-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .quiz-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        /* Lesson Cards */
        .lessons-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .modern-lesson-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .modern-lesson-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .lesson-status {
            flex-shrink: 0;
        }

        .lesson-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .lesson-number.completed {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .lesson-number.in-progress {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .lesson-number.not-started {
            background: #e5e7eb;
            color: #6b7280;
        }

        .lesson-content {
            flex: 1;
        }

        .lesson-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .lesson-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.75rem;
        }

        .lesson-meta {
            display: flex;
            gap: 0.5rem;
        }

        .meta-badge {
            background: #f3f4f6;
            color: #6b7280;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .meta-badge.quiz-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .lesson-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        /* Modern Buttons */
        .btn-modern {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-modern.btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-modern.btn-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            transform: translateY(-1px);
        }

        .btn-modern.btn-outline {
            background: transparent;
            border: 2px solid #e5e7eb;
            color: #6b7280;
        }

        .btn-modern.btn-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
        }

        /* Sidebar */
        .modern-sidebar-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modern-sidebar-card .card-header {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .modern-sidebar-card .card-header h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .course-detail {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .course-detail:last-child {
            margin-bottom: 0;
        }

        .course-detail i {
            font-size: 1.25rem;
            width: 20px;
            text-align: center;
        }

        .detail-label {
            display: block;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .detail-value {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Empty States */
        .empty-quiz-state {
            text-align: center;
            padding: 2rem;
            background: #f9fafb;
            border-radius: 12px;
            color: #6b7280;
        }

        .empty-quiz-state i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .course-stats {
                justify-content: center;
                margin-top: 1rem;
            }

            .modern-lesson-card {
                flex-direction: column;
                text-align: center;
            }

            .lesson-actions {
                width: 100%;
                justify-content: center;
            }

            .lesson-meta {
                justify-content: center;
            }
        }
    </style>
</x-app-layout>
