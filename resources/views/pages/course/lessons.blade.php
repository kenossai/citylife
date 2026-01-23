<x-app-layout>
@section('title', 'Lessons - ' . $course->title)

<style>
    .lessons-hero {
        background-color:  #3d0047;
        color: white;
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .lessons-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
        opacity: 0.3;
    }

    .lessons-hero .container {
        position: relative;
        z-index: 1;
    }

    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .breadcrumb-custom a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb-custom a:hover {
        color: white;
    }

    .breadcrumb-custom span {
        color: rgba(255,255,255,0.5);
    }

    .hero-title {
        font-size: 42px;
        color: white;
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 18px;
        color: rgba(255,255,255,0.9);
        margin-bottom: 30px;
    }

    .progress-overview {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .progress-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 25px;
        margin-bottom: 20px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
        color: #fbbf24;
    }

    .stat-label {
        font-size: 14px;
        color: rgba(255,255,255,0.8);
    }

    .progress-bar-wrapper {
        margin-top: 20px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .progress-bar-beautiful {
        height: 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill-beautiful {
        height: 100%;
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
        border-radius: 10px;
        transition: width 0.6s ease;
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
    }

    .lessons-section {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 10px;
    }

    .section-subtitle {
        font-size: 16px;
        color: #718096;
    }

    .quizzes-section {
        padding: 60px 0;
        background: white;
    }

    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .quiz-card-beautiful {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s ease;
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }

    .quiz-card-beautiful::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #8b5cf6, #a78bfa);
    }

    .quiz-card-beautiful:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
    }

    .quiz-badge-beautiful {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .quiz-badge-beautiful.passed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .quiz-badge-beautiful.failed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .quiz-badge-beautiful.not-taken {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .quiz-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 10px;
    }

    .quiz-card-description {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .lessons-list {
        display: grid;
        gap: 25px;
    }

    .lesson-card-beautiful {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 25px;
        border: 1px solid #e5e7eb;
    }

    .lesson-card-beautiful:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #360153;
    }

    .lesson-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        flex-shrink: 0;
        position: relative;
    }

    .lesson-icon.completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .lesson-icon.in-progress {
        background-color: #10b981;
        color: white;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
    }

    .lesson-icon.not-started {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
    }

    .lesson-content {
        flex: 1;
    }

    .lesson-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 8px;
    }

    .lesson-card-description {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .lesson-tags {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .lesson-tag {
        background: #f3f4f6;
        color: #6b7280;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .lesson-tag.quiz-tag {
        background: rgba(139, 92, 246, 0.1);
        color: #160d2b;
    }

    .lesson-tag.completed-tag {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .lesson-buttons {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .btn-beautiful {
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-beautiful.btn-primary {
        background-color:  #ffb700;
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .btn-beautiful.btn-primary:hover {
        background-color:  #58006e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
        color: white;
    }

    .btn-beautiful.btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-beautiful.btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
    }

    .btn-beautiful.btn-quiz {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
    }

    .btn-beautiful.btn-quiz:hover {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 80px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 16px;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 32px;
        }

        .progress-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .lesson-card-beautiful {
            flex-direction: column;
            text-align: center;
        }

        .lesson-buttons {
            width: 100%;
            flex-direction: column;
        }

        .btn-beautiful {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<div class="lessons-hero">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
            <span>›</span>
            <a href="{{ route('courses.index') }}"><i class="fas fa-book"></i> Courses</a>
            <span>›</span>
            <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
            <span>›</span>
            <span style="color: white;">Lessons</span>
        </div>

        <h1 class="hero-title">{{ $course->title }}</h1>
        <p class="hero-subtitle">Complete all lessons to earn your certificate</p>

        <div class="progress-overview">
            <div class="progress-stats">
                <div class="stat-item">
                    <span class="stat-value">{{ $userEnrollment->completed_lessons }}</span>
                    <span class="stat-label">Lessons Completed</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $lessons->count() }}</span>
                    <span class="stat-label">Total Lessons</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $lessons->whereNotNull('quiz_questions')->count() }}</span>
                    <span class="stat-label">Quizzes Available</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ round($userEnrollment->progress_percentage) }}%</span>
                    <span class="stat-label">Overall Progress</span>
                </div>
            </div>

            <div class="progress-bar-wrapper">
                <div class="progress-label">
                    <span>Course Progress</span>
                    <span>{{ $userEnrollment->completed_lessons }}/{{ $lessons->count() }} Lessons</span>
                </div>
                <div class="progress-bar-beautiful">
                    <div class="progress-fill-beautiful" style="width: {{ $userEnrollment->progress_percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Lessons Section -->
<section class="lessons-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-list"></i> Course Lessons</h2>
            <p class="section-subtitle">Follow the lessons in order for the best learning experience</p>
        </div>

        <div class="lessons-list">
            @foreach($lessons as $lesson)
                @php
                    $lessonProgress = $progress[$lesson->id] ?? null;
                    $isCompleted = $lessonProgress && $lessonProgress->status === 'completed';
                    $isInProgress = $lessonProgress && $lessonProgress->status === 'in_progress';
                    $quizScore = $lessonProgress ? $lessonProgress->quiz_score : null;
                @endphp

                <div class="lesson-card-beautiful">
                    <div class="lesson-icon {{ $isCompleted ? 'completed' : ($isInProgress ? 'in-progress' : 'not-started') }}">
                        @if($isCompleted)
                            <i class="fas fa-check"></i>
                        @elseif($isInProgress)
                            <i class="fas fa-play"></i>
                        @else
                            {{ $lesson->lesson_number }}
                        @endif
                    </div>

                    <div class="lesson-content">
                        <h4 class="lesson-card-title">{{ $lesson->title }}</h4>
                        <p class="lesson-card-description">{{ $lesson->description }}</p>

                        <div class="lesson-tags">
                            @if($lesson->duration_minutes)
                                <span class="lesson-tag">
                                    <i class="fas fa-clock"></i> {{ $lesson->formatted_duration }}
                                </span>
                            @endif
                            @if($lesson->quiz_questions)
                                <span class="lesson-tag quiz-tag">
                                    <i class="fas fa-clipboard-question"></i> Quiz Available
                                </span>
                            @endif
                            @if($isCompleted)
                                <span class="lesson-tag completed-tag">
                                    <i class="fas fa-check-circle"></i> Completed
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="lesson-buttons">
                        <a href="{{ route('courses.lesson.show', [$course->slug, $lesson->slug]) }}" class="btn-beautiful btn-primary">
                            @if($isCompleted)
                                <i class="fas fa-redo"></i> Review
                            @elseif($isInProgress)
                                <i class="fas fa-play"></i> Continue
                            @else
                                <i class="fas fa-play"></i> Start Lesson
                            @endif
                        </a>

                        @if($lesson->quiz_questions)
                            <a href="{{ route('courses.lesson.quiz', [$course->slug, $lesson->slug]) }}" class="btn-beautiful btn-secondary">
                                <i class="fas fa-clipboard-question"></i>
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
</section>

</x-app-layout>
