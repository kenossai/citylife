<x-app-layout>
    @section('title', $lesson->title . ' - ' . $course->title)

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h3 class="text-white">Lesson {{ $lesson->lesson_number }}</h3>
            <h2 class="page-header__title">{{ $lesson->title }}</h2>
            <p class="section-header__text">{{ $course->title }}</p>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses</a></li>
                <li><i class="icon-graduation-cap"></i> <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                <li><i class="icon-list"></i> <a href="{{ route('courses.lessons', $course->slug) }}">Lessons</a></li>
                <li><span>{{ $lesson->title }}</span></li>
            </ul>
        </div>
    </section>

    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="lesson-content">
                        <!-- Lesson Status -->
                        <div class="lesson-status mb-4">
                            <div class="alert {{ $progress->status === 'completed' ? 'alert-success' : 'alert-info' }}" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="icon-{{ $progress->status === 'completed' ? 'check-circle' : 'clock' }} me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">
                                            @if($progress->status === 'completed')
                                                Lesson Completed!
                                            @elseif($progress->status === 'in_progress')
                                                Lesson In Progress
                                            @else
                                                Ready to Start
                                            @endif
                                        </h6>
                                        <p class="mb-0">
                                            @if($progress->status === 'completed')
                                                You completed this lesson on {{ $progress->completed_at->format('M d, Y \a\t g:i A') }}
                                                @if($progress->quiz_score)
                                                    with a quiz score of {{ round($progress->quiz_score) }}%
                                                @endif
                                            @elseif($progress->status === 'in_progress')
                                                Started on {{ $progress->started_at->format('M d, Y \a\t g:i A') }}
                                            @else
                                                Click "Mark as Complete" when you finish reading the content
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lesson Info -->
                        <div class="lesson-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ $lesson->title }}</h4>
                                    <p class="text-muted">{{ $lesson->description }}</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="lesson-meta">
                                        @if($lesson->duration_minutes)
                                            <span class="badge bg-primary me-2">
                                                <i class="icon-clock"></i> {{ $lesson->formatted_duration }}
                                            </span>
                                        @endif
                                        @if($lesson->quiz_questions)
                                            <span class="badge bg-info me-2">
                                                <i class="icon-question"></i> Quiz Available
                                            </span>
                                        @endif
                                        <span class="badge bg-secondary">
                                            <i class="icon-list"></i> Lesson {{ $lesson->lesson_number }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lesson Content -->
                        <div class="lesson-body">
                            @if($lesson->content)
                                <div class="content-section mb-5">
                                    <h5>Lesson Content</h5>
                                    <div class="content-text">
                                        {!! nl2br(e($lesson->content)) !!}
                                    </div>
                                </div>
                            @endif

                            @if($lesson->homework)
                                <div class="homework-section mb-5">
                                    <h5>Homework Assignment</h5>
                                    <div class="alert alert-warning">
                                        <i class="icon-assignment me-2"></i>
                                        <strong>Assignment:</strong>
                                        <div class="mt-2">
                                            {!! nl2br(e($lesson->homework)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Lesson Actions -->
                        <div class="lesson-actions mt-5">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Lesson Progress</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($progress->status !== 'completed')
                                                <button id="markCompleteBtn" class="btn btn-success me-3">
                                                    <i class="icon-check"></i> Mark as Complete
                                                </button>
                                            @else
                                                <span class="btn btn-outline-success disabled">
                                                    <i class="icon-check"></i> Completed
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($lesson->quiz_questions)
                                                <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}" 
                                                   class="btn {{ $progress->quiz_score ? 'btn-outline-info' : 'btn-info' }}">
                                                    <i class="icon-question"></i> 
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
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="lesson-sidebar">
                        <!-- Navigation -->
                        <div class="lesson-navigation card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Lesson Navigation</h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('courses.lessons', $course->slug) }}" class="btn btn-outline-primary">
                                        <i class="icon-list"></i> All Lessons
                                    </a>
                                    
                                    @php
                                        $prevLesson = $course->lessons()->where('lesson_number', '<', $lesson->lesson_number)->orderBy('lesson_number', 'desc')->first();
                                        $nextLesson = $course->lessons()->where('lesson_number', '>', $lesson->lesson_number)->orderBy('lesson_number')->first();
                                    @endphp
                                    
                                    @if($prevLesson)
                                        <a href="{{ route('courses.lesson.show', [$course->slug, $prevLesson->slug]) }}" class="btn btn-outline-secondary">
                                            <i class="icon-arrow-left"></i> Previous Lesson
                                        </a>
                                    @endif
                                    
                                    @if($nextLesson)
                                        <a href="{{ route('courses.lesson.show', [$course->slug, $nextLesson->slug]) }}" class="btn btn-outline-secondary">
                                            <i class="icon-arrow-right"></i> Next Lesson
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Course Progress -->
                        <div class="course-progress card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Course Progress</h6>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $userEnrollment->progress_percentage }}%" 
                                         aria-valuenow="{{ $userEnrollment->progress_percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ round($userEnrollment->progress_percentage) }}%
                                    </div>
                                </div>
                                <p class="text-muted small">{{ $userEnrollment->completed_lessons }} of {{ $course->lessons->count() }} lessons completed</p>
                            </div>
                        </div>

                        <!-- Help & Support -->
                        <div class="help-support card">
                            <div class="card-body">
                                <h6 class="card-title">Need Help?</h6>
                                <p class="text-muted small">If you have questions about this lesson, feel free to reach out.</p>
                                <a href="mailto:{{ config('mail.from.address') }}?subject=Question about {{ $lesson->title }}" class="btn btn-outline-info btn-sm w-100">
                                    <i class="icon-envelope"></i> Contact Support
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
        .content-text {
            line-height: 1.8;
            font-size: 16px;
        }
        
        .lesson-meta .badge {
            font-size: 12px;
        }
        
        .lesson-actions .card {
            border-left: 4px solid #007bff;
        }
        
        .lesson-navigation .btn {
            font-size: 14px;
        }
        
        .progress {
            height: 15px;
        }
    </style>
</x-app-layout>
