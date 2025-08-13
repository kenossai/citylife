<x-app-layout>
    @section('title', 'My Courses Dashboard')

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h3 class="text-white">Welcome back, {{ $member->first_name }}!</h3>
            <h2 class="page-header__title">My Learning Dashboard</h2>
            <p class="section-header__text">Track your progress and continue your learning journey</p>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses</a></li>
                <li><span>My Dashboard</span></li>
            </ul>
        </div>
    </section>

    <section class="section-space">
        <div class="container">
            <!-- Dashboard Stats -->
            <div class="dashboard-stats mb-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card card text-center">
                            <div class="card-body">
                                <div class="stat-icon mb-3">
                                    <i class="icon-book text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="stat-number">{{ $enrollments->count() }}</h4>
                                <p class="stat-label text-muted">Enrolled Courses</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card card text-center">
                            <div class="card-body">
                                <div class="stat-icon mb-3">
                                    <i class="icon-check-circle text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="stat-number">{{ $enrollments->where('status', 'completed')->count() }}</h4>
                                <p class="stat-label text-muted">Completed Courses</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card card text-center">
                            <div class="card-body">
                                <div class="stat-icon mb-3">
                                    <i class="icon-graduation-cap text-warning" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="stat-number">{{ $enrollments->where('certificate_issued', true)->count() }}</h4>
                                <p class="stat-label text-muted">Certificates Earned</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card card text-center">
                            <div class="card-body">
                                <div class="stat-icon mb-3">
                                    <i class="icon-clock text-info" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="stat-number">{{ $enrollments->where('status', 'active')->count() }}</h4>
                                <p class="stat-label text-muted">In Progress</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Enrollments -->
            <div class="course-enrollments">
                <div class="section-header mb-4">
                    <h3>My Courses</h3>
                    <p class="text-muted">Continue your learning journey or explore new courses</p>
                </div>

                @if($enrollments->count() > 0)
                    <div class="row">
                        @foreach($enrollments as $enrollment)
                            @php
                                $course = $enrollment->course;
                                $progressPercentage = $enrollment->progress_percentage;
                            @endphp
                            
                            <div class="col-lg-6 mb-4">
                                <div class="course-enrollment-card">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <!-- Course Header -->
                                            <div class="course-header mb-3">
                                                <div class="row align-items-start">
                                                    <div class="col-8">
                                                        <h5 class="course-title mb-2">{{ $course->title }}</h5>
                                                        <p class="course-category text-muted small mb-2">{{ $course->category }}</p>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <span class="badge {{ $enrollment->status === 'completed' ? 'bg-success' : ($enrollment->status === 'active' ? 'bg-primary' : 'bg-secondary') }}">
                                                            {{ ucfirst($enrollment->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="course-progress mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">Progress</small>
                                                    <small class="text-muted">{{ round($progressPercentage) }}%</small>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar {{ $progressPercentage >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $progressPercentage }}%"
                                                         aria-valuenow="{{ $progressPercentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>

                            <!-- Course Stats -->
                            <div class="course-stats mb-3">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $enrollment->completed_lessons }}</div>
                                            <div class="stat-label text-muted small">Completed</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $course->lessons->count() }}</div>
                                            <div class="stat-label text-muted small">Total Lessons</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="stat-item">
                                            <div class="stat-value text-info">{{ $course->lessons->whereNotNull('quiz_questions')->count() }}</div>
                                            <div class="stat-label text-muted small">Quizzes</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="stat-item">
                                            @if($enrollment->certificate_issued)
                                                <div class="stat-value text-success">
                                                    <i class="icon-check-circle"></i>
                                                </div>
                                                <div class="stat-label text-success small">Certified</div>
                                            @else
                                                <div class="stat-value text-muted">
                                                    <i class="icon-certificate"></i>
                                                </div>
                                                <div class="stat-label text-muted small">Certificate</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>                                            <!-- Enrollment Info -->
                                            <div class="enrollment-info mb-3">
                                                <small class="text-muted">
                                                    <i class="icon-calendar me-1"></i>
                                                    Enrolled on {{ $enrollment->enrollment_date->format('M d, Y') }}
                                                    @if($enrollment->completion_date)
                                                        • Completed on {{ $enrollment->completion_date->format('M d, Y') }}
                                                    @endif
                                                </small>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="course-actions">
                                                <div class="d-grid gap-2">
                                                    @if($enrollment->status === 'completed')
                                                        <a href="{{ route('courses.lessons', $course->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="icon-eye"></i> Review Course
                                                        </a>
                                                    @else
                                                        <a href="{{ route('courses.lessons', $course->slug) }}" class="btn btn-primary btn-sm">
                                                            <i class="icon-play"></i> Continue Learning
                                                        </a>
                                                    @endif
                                                    
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="icon-info"></i> Details
                                                        </a>
                                                        @if($course->lessons->whereNotNull('quiz_questions')->count() > 0)
                                                            <a href="{{ route('courses.lessons', $course->slug) }}#quizzes" class="btn btn-outline-info btn-sm" title="Take Quizzes">
                                                                <i class="icon-question"></i> Quizzes ({{ $course->lessons->whereNotNull('quiz_questions')->count() }})
                                                            </a>
                                                        @endif
                                                        @if($enrollment->certificate_issued)
                                                            @if($enrollment->certificate_file_path)
                                                                <a href="{{ route('certificate.download', $enrollment->id) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                                                    <i class="icon-download"></i> Download Certificate
                                                                </a>
                                                            @else
                                                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                                                    <i class="icon-certificate"></i> Certificate Pending
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-enrollments text-center py-5">
                        <div class="mb-4">
                            <i class="icon-book text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4>No Course Enrollments Yet</h4>
                        <p class="text-muted mb-4">You haven't enrolled in any courses yet. Explore our available courses and start your learning journey!</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <i class="icon-search"></i> Browse Courses
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('courses.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="icon-search me-2"></i>Browse All Courses
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('contact') }}" class="btn btn-outline-info w-100 mb-2">
                                    <i class="icon-envelope me-2"></i>Contact Support
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-secondary w-100 mb-2" onclick="window.print()">
                                    <i class="icon-print me-2"></i>Print Progress
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }
        
        .course-enrollment-card .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .course-enrollment-card .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .course-title {
            color: #333;
            font-weight: 600;
        }
        
        .progress {
            background-color: #e9ecef;
        }
        
        .stat-item {
            padding: 8px 0;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .course-actions .btn {
            border-radius: 6px;
        }
        
        .no-enrollments {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-icon i {
                font-size: 2rem !important;
            }
        }
    </style>
</x-app-layout>
