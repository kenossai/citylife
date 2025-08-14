<x-app-layout>
    @section('title', $lesson->title . ' - ' . $course->title)

    <!-- Modern Lesson Header -->
    <div class="lesson-header">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-md-8">
                    <div class="lesson-info">
                        <div class="breadcrumb-modern mb-2">
                            <a href="{{ route('home') }}" class="breadcrumb-link">
                                <i class="icon-home"></i> Home
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.index') }}" class="breadcrumb-link">
                                <i class="icon-book"></i> Courses
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.show', $course->slug) }}" class="breadcrumb-link">
                                <i class="icon-graduation-cap"></i> {{ $course->title }}
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.lessons', $course->slug) }}" class="breadcrumb-link">
                                <i class="icon-list"></i> Lessons
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <span class="breadcrumb-current">{{ $lesson->title }}</span>
                        </div>
                        <h3 class="lesson-number-title mb-1">Lesson {{ $lesson->lesson_number }}</h3>
                        <h2 class="lesson-main-title mb-2">{{ $lesson->title }}</h2>
                        <p class="lesson-subtitle">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lesson-stats">
                        <div class="stat-item">
                            <div class="stat-value {{ $progress->status === 'completed' ? 'text-success' : ($progress->status === 'in_progress' ? 'text-warning' : 'text-secondary') }}">
                                @if($progress->status === 'completed')
                                    <i class="icon-check-circle"></i>
                                @elseif($progress->status === 'in_progress')
                                    <i class="icon-play-circle"></i>
                                @else
                                    <i class="icon-circle"></i>
                                @endif
                            </div>
                            <div class="stat-label">
                                {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                            </div>
                        </div>
                        @if($lesson->duration_minutes)
                            <div class="stat-item">
                                <div class="stat-value">{{ $lesson->formatted_duration }}</div>
                                <div class="stat-label">Duration</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="lesson-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Lesson Status Card -->
                    <div class="status-card mb-4">
                        <div class="card-header">
                            <h5>
                                <i class="icon-{{ $progress->status === 'completed' ? 'check-circle text-success' : ($progress->status === 'in_progress' ? 'play-circle text-warning' : 'clock text-secondary') }} me-2"></i>
                                @if($progress->status === 'completed')
                                    Lesson Completed!
                                @elseif($progress->status === 'in_progress')
                                    Lesson In Progress
                                @else
                                    Ready to Start
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="status-info">
                                @if($progress->status === 'completed')
                                    <p class="status-text">
                                        <i class="icon-calendar me-2"></i>
                                        Completed on {{ $progress->completed_at->format('M d, Y \a\t g:i A') }}
                                        @if($progress->quiz_score)
                                            <span class="quiz-score-badge ms-3">
                                                <i class="icon-award me-1"></i>
                                                Quiz Score: {{ round($progress->quiz_score) }}%
                                            </span>
                                        @endif
                                    </p>
                                @elseif($progress->status === 'in_progress')
                                    <p class="status-text">
                                        <i class="icon-clock me-2"></i>
                                        Started on {{ $progress->started_at->format('M d, Y \a\t g:i A') }}
                                    </p>
                                @else
                                    <p class="status-text">
                                        <i class="icon-info-circle me-2"></i>
                                        Click "Mark as Complete" when you finish reading the content
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Meta Info -->
                    <div class="lesson-meta-card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="lesson-title">{{ $lesson->title }}</h4>
                                    <p class="lesson-description">{!! $lesson->description !!}</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="lesson-badges">
                                        @if($lesson->duration_minutes)
                                            <span class="modern-badge duration-badge">
                                                <i class="icon-clock"></i> {{ $lesson->formatted_duration }}
                                            </span>
                                        @endif
                                        @if($lesson->quiz_questions)
                                            <span class="modern-badge quiz-badge">
                                                <i class="icon-question"></i> Quiz Available
                                            </span>
                                        @endif
                                        <span class="modern-badge lesson-badge">
                                            <i class="icon-list"></i> Lesson {{ $lesson->lesson_number }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Content -->
                    <div class="lesson-body-card">
                        @if($lesson->content)
                            <div class="content-section">
                                <div class="content-header">
                                    <h5><i class="icon-book-open me-2"></i>Lesson Content</h5>
                                </div>
                                <div class="content-body">
                                    <div class="content-text">
                                        {!! $lesson->content !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($lesson->homework)
                            <div class="homework-section">
                                <div class="homework-header">
                                    <h5><i class="icon-assignment me-2"></i>Homework Assignment</h5>
                                </div>
                                <div class="homework-body">
                                    <div class="homework-content">
                                        <div class="homework-icon">
                                            <i class="icon-assignment"></i>
                                        </div>
                                        <div class="homework-text">
                                            <strong>Assignment Instructions:</strong>
                                            <div class="homework-details mt-2">
                                                {!! nl2br(e($lesson->homework)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="lesson-actions-card">
                        <div class="card-header">
                            <h5><i class="icon-settings me-2"></i>Lesson Progress</h5>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                <div class="primary-actions">
                                    @if($progress->status !== 'completed')
                                        <button id="markCompleteBtn" class="btn-modern btn-primary">
                                            <i class="icon-check me-2"></i>Mark as Complete
                                        </button>
                                    @else
                                        <div class="completed-badge">
                                            <i class="icon-check-circle me-2"></i>Completed
                                        </div>
                                    @endif
                                </div>
                                <div class="secondary-actions">
                                    @if($lesson->quiz_questions)
                                        <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}"
                                           class="btn-modern {{ $progress->quiz_score ? 'btn-outline' : 'btn-quiz' }}">
                                            <i class="icon-question me-2"></i>
                                            @if($progress->quiz_score)
                                                Retake Quiz ({{ round($progress->quiz_score) }}%)
                                            @else
                                                Take Quiz
                                            @endif
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="lesson-sidebar">
                        <!-- Navigation Card -->
                        <div class="modern-sidebar-card mb-4">
                            <div class="card-header">
                                <h5><i class="icon-navigation me-2"></i>Lesson Navigation</h5>
                            </div>
                            <div class="card-body">
                                <div class="navigation-buttons">
                                    <a href="{{ route('courses.lessons', $course->slug) }}" class="btn-modern btn-outline w-100 mb-3">
                                        <i class="icon-list me-2"></i>All Lessons
                                    </a>

                                    @php
                                        $prevLesson = $course->lessons()->where('lesson_number', '<', $lesson->lesson_number)->orderBy('lesson_number', 'desc')->first();
                                        $nextLesson = $course->lessons()->where('lesson_number', '>', $lesson->lesson_number)->orderBy('lesson_number')->first();
                                    @endphp

                                    @if($prevLesson)
                                        <a href="{{ route('courses.lesson.show', [$course->slug, $prevLesson->slug]) }}"
                                           class="btn-modern btn-outline w-100 mb-3">
                                            <i class="icon-arrow-left me-2"></i>Previous: {{ $prevLesson->title }}
                                        </a>
                                    @endif

                                    @if($nextLesson)
                                        <a href="{{ route('courses.lesson.show', [$course->slug, $nextLesson->slug]) }}"
                                           class="btn-modern btn-primary w-100">
                                            <i class="icon-arrow-right me-2"></i>Next: {{ $nextLesson->title }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Course Progress Card -->
                        <div class="modern-sidebar-card mb-4">
                            <div class="card-header">
                                <h5><i class="icon-chart me-2"></i>Course Progress</h5>
                            </div>
                            <div class="card-body">
                                <div class="progress-info mb-3">
                                    <div class="progress-text">{{ round($userEnrollment->progress_percentage) }}% Complete</div>
                                    <div class="lessons-count">{{ $userEnrollment->completed_lessons }}/{{ $course->lessons->count() }} lessons</div>
                                </div>
                                <div class="progress-bar-modern">
                                    <div class="progress-fill" style="width: {{ $userEnrollment->progress_percentage }}%"></div>
                                </div>
                                <div class="progress-details mt-3">
                                    <div class="detail-item">
                                        <span class="detail-label">Lessons Completed:</span>
                                        <span class="detail-value">{{ $userEnrollment->completed_lessons }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Total Lessons:</span>
                                        <span class="detail-value">{{ $course->lessons->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help & Support Card -->
                        <div class="modern-sidebar-card">
                            <div class="card-header">
                                <h5><i class="icon-help me-2"></i>Need Help?</h5>
                            </div>
                            <div class="card-body">
                                <p class="help-text">If you have questions about this lesson, feel free to reach out for support.</p>
                                <a href="mailto:{{ config('mail.from.address') }}?subject=Question about {{ $lesson->title }}"
                                   class="btn-modern btn-outline w-100">
                                    <i class="icon-envelope me-2"></i>Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const markCompleteBtn = document.getElementById('markCompleteBtn');

            if (markCompleteBtn) {
                markCompleteBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to mark this lesson as complete?')) {
                        // You can add an AJAX call here to mark the lesson as complete
                        // For now, we'll redirect to the quiz or next lesson
                        @if($lesson->quiz_questions)
                            window.location.href = '{{ route("courses.lesson.quiz", [$course->slug, $lesson->slug]) }}';
                        @else
                            // Mark as complete via AJAX call would go here
                            alert('Lesson marked as complete!');
                            window.location.reload();
                        @endif
                    }
                });
            }
        });
    </script>
    @endpush

    <style>
        /* Modern Lesson Page Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Lesson Header */
        .lesson-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .lesson-number-title {
            font-size: 1.125rem;
            font-weight: 500;
            opacity: 0.9;
            margin: 0;
        }

        .lesson-main-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .lesson-subtitle {
            opacity: 0.8;
            margin: 0;
        }

        .lesson-stats {
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
        .status-card, .lesson-meta-card, .lesson-body-card, .lesson-actions-card {
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

        .card-header h5 {
            font-weight: 600;
            margin: 0;
            color: #1f2937;
            display: flex;
            align-items: center;
        }

        .card-body {
            padding: 1.5rem;
        }

        .status-info {
            display: flex;
            align-items: center;
        }

        .status-text {
            color: #6b7280;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .quiz-score-badge {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Lesson Meta */
        .lesson-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .lesson-description {
            color: #6b7280;
            margin-bottom: 0;
        }

        .lesson-badges {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .modern-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .duration-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .quiz-badge {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .lesson-badge {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        /* Content Section */
        .lesson-body-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .content-section, .homework-section {
            padding: 2rem;
        }

        .content-header, .homework-header {
            margin-bottom: 1.5rem;
        }

        .content-header h5, .homework-header h5 {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .content-text {
            line-height: 1.8;
            font-size: 1rem;
            color: #374151;
        }

        .homework-section {
            background: #fef3c7;
            border-top: 1px solid #e5e7eb;
        }

        .homework-content {
            display: flex;
            gap: 1rem;
        }

        .homework-icon {
            background: #f59e0b;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .homework-text {
            flex: 1;
        }

        .homework-details {
            color: #92400e;
            line-height: 1.6;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .completed-badge {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        /* Modern Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
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

        .btn-modern.btn-quiz {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-modern.btn-quiz:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
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

        .navigation-buttons {
            display: flex;
            flex-direction: column;
        }

        /* Progress Bar */
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

        .progress-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
        }

        .detail-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .help-text {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .lesson-stats {
                justify-content: center;
                margin-top: 1rem;
            }

            .lesson-badges {
                align-items: flex-start;
                margin-top: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .navigation-buttons .btn-modern {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</x-app-layout>
