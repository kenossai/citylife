<x-app-layout>
    @section('title', 'My Courses Dashboard')

    <!-- Modern Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-md-6">
                    <div class="welcome-section">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                <i class="icon-user"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-white">Welcome back {{ $member->first_name }} {{ $member->last_name }}!</h4>
                                <p class="text-white mb-0">Here's what's happening with your learning today.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="header-stats d-flex justify-content-end align-items-center">
                        <div class="header-stat-item me-4">
                            <div class="stat-value text-primary">{{ number_format($enrollments->count() * 150, 0) }}</div>
                            <div class="stat-label">Learning Value</div>
                        </div>
                        <div class="header-stat-item me-4">
                            <div class="stat-value text-success">{{ number_format($enrollments->where('status', 'completed')->count() * 200, 0) }}</div>
                            <div class="stat-label">Achievements</div>
                        </div>
                        @auth('member')
                        <div class="header-stat-item">
                            <form method="POST" action="{{ route('member.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="icon-logout me-1"></i>Logout
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="dashboard-content">
        <div class="container">
            <!-- Main Dashboard Grid -->
            <div class="row">

                <!-- Right Column - Course Cards and Quick Stats -->
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="quick-stats-grid mb-4">
                                <div class="quick-stat-card">
                                    <div class="icon-container">
                                        <i class="icon-trending-up text-success" style="font-size: 24px;">{{ $enrollments->count() }}</i>
                                    </div>
                                    <div class="stat-info">
                                        <p>Enrolled Courses</p>
                                        {{-- <small class="text-success">+12%</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-stats-grid mb-4">
                                <div class="quick-stat-card">
                                    <div class="icon-container">
                                        <i class="icon-trending-up text-success" style="font-size: 24px;">{{ $enrollments->where('status', 'completed')->count() }}</i>
                                    </div>
                                    <div class="stat-info">
                                        <p>Completed Courses</p>
                                        {{-- <small class="text-success">+12%</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-stats-grid mb-4">
                                <div class="quick-stat-card">
                                    <div class="icon-container">
                                        <i class="icon-trending-up text-success" style="font-size: 24px;">{{ $enrollments->where('certificate_issued', true)->count() }}</i>
                                    </div>
                                    <div class="stat-info">
                                        <p>Certificates Earned</p>
                                        {{-- <small class="text-success">+12%</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quick-stats-grid mb-4">
                                <div class="quick-stat-card">
                                    <div class="icon-container">
                                        <i class="icon-trending-up text-success" style="font-size: 24px;">{{ $enrollments->where('status', 'active')->count() }}</i>
                                    </div>
                                    <div class="stat-info">
                                        <p>In Progress Courses</p>
                                        {{-- <small class="text-success">+12%</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Enrollments Section -->
            <div class="course-enrollments">
                <div class="section-header mb-4">
                    <h3>My Courses</h3>
                    <p class="text-muted">Continue your learning journey</p>
                </div>

                @if($enrollments->count() > 0)
                    <div class="row">
                        @foreach($enrollments->take(6) as $enrollment)
                            @php
                                $course = $enrollment->course;
                                $progressPercentage = $enrollment->progress_percentage;
                            @endphp

                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="modern-course-card">
                                    <div class="course-card-header">
                                        <div class="course-badge">
                                            <span class="badge {{ $enrollment->status === 'completed' ? 'badge-success' : ($enrollment->status === 'active' ? 'badge-primary' : 'badge-secondary') }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="course-card-body">
                                        <h6 class="course-title">{{ Str::limit($course->title, 40) }}</h6>
                                        <p class="course-category">{{ $course->category }}</p>
                                        <p class="course-category">
                                            Enrolled on
                                            {{ $enrollment->enrollment_date->format('M d, Y') }}
                                            @if($enrollment->completion_date)
                                                • Completed on {{ $enrollment->completion_date->format('M d, Y') }}
                                            @endif
                                        </p>

                                        <div class="progress-section">
                                            <div class="progress-info">
                                                <span class="progress-text">{{ round($progressPercentage) }}% Complete</span>
                                                <span class="lessons-count">{{ $enrollment->completed_lessons }}/{{ $course->lessons->count() }}</span>
                                            </div>
                                            <div class="progress-bar-modern">
                                                <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-evenly">
                                           <div class="col">
                                                <div class="course-actions">
                                                    @if($enrollment->status === 'completed')
                                                        <a href="{{ route('courses.lessons', $course->slug) }}" class="btn-modern btn-outline">
                                                            Review Course
                                                        </a>
                                                    @else
                                                        <a href="{{ route('courses.lessons', $course->slug) }}" class="btn-modern btn-primary">
                                                            Continue
                                                        </a>
                                                    @endif
                                                </div>
                                           </div>
                                           <div class="col text-end">
                                                <div class="course-actions">
                                                    @if($course->lessons->whereNotNull('quiz_questions')->count() > 0)
                                                        <a href="{{ route('courses.lessons', $course->slug) }}#quizzes" class="btn-modern btn-primary" title="Take Quizzes">
                                                            <i class="icon-question"></i> Quizzes ({{ $course->lessons->whereNotNull('quiz_questions')->count() }})
                                                        </a>
                                                    @endif
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="icon-book"></i>
                        </div>
                        <h4>No Course Enrollments Yet</h4>
                        <p>Start your learning journey by exploring our available courses</p>
                        <a href="{{ route('courses.index') }}" class="btn-modern btn-primary">
                            Browse Courses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <style>
        /* Modern Dashboard Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #513170 0%, #351c42 100%);
            color: white;
            margin-bottom: 2rem;
        }

        .user-avatar img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .header-stats .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .header-stats .stat-label {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 0;
        }

        .dashboard-content {
            padding: 0 2rem 3rem;
        }

        /* Modern Stat Cards */
        .modern-stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .modern-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modern-stat-card.expense-card {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .modern-stat-card.sales-card {
            background: linear-gradient(135deg, #4ecdc4 0%, #26d0ce 100%);
            color: white;
        }

        .modern-stat-card .card-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modern-stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(79, 172, 254, 0.1);
            color: #4facfe;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .modern-stat-card .stat-icon.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .modern-stat-card .stat-icon.warning {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
        }

        .modern-stat-card .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .modern-stat-card .stat-details h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: inherit;
        }

        .modern-stat-card .stat-details p {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .trend-up {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Chart Cards */
        .chart-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            height: 350px;
        }

        .chart-card .card-header {
            padding: 1.5rem 1.5rem 0;
            background: transparent;
            border: none;
        }

        .chart-card .card-header h5 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .progress-chart {
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chart-bars {
            display: flex;
            align-items: end;
            justify-content: space-between;
            height: 180px;
            padding: 0 1rem;
        }

        .bar-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            max-width: 60px;
        }

        .bar-container {
            display: flex;
            align-items: end;
            height: 140px;
            width: 100%;
            justify-content: center;
            gap: 3px;
        }

        .bar {
            width: 12px;
            border-radius: 6px 6px 0 0;
            transition: all 0.3s ease;
        }

        .bar.primary {
            background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%);
        }

        .bar.secondary {
            background: linear-gradient(180deg, #43e97b 0%, #38f9d7 100%);
        }

        .bar-label {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .legend-color.primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .legend-color.secondary {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Circular Progress */
        .circular-progress {
            position: relative;
            margin: 2rem auto;
        }

        .progress-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
        }

        .progress-circle::before {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
        }

        .progress-value {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .progress-value h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #1f2937;
        }

        .progress-value p {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }

        .progress-stat h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #1f2937;
        }

        .progress-stat p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        /* Quick Stats Grid */
        .quick-stats-grid {
            display: grid;
            gap: 1rem;
        }

        .quick-stat-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quick-stat-card .icon-container {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(79, 172, 254, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .quick-stat-card .stat-info h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #1f2937;
        }

        .quick-stat-card .stat-info p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .quick-stat-card .stat-info small {
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Earnings Card */
        .earnings-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .earnings-card .card-header {
            padding: 1.5rem 1.5rem 0;
            background: transparent;
            border: none;
        }

        .earnings-amount {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .earnings-amount h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .earnings-change {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .mini-chart {
            width: 100%;
            height: 60px;
        }

        /* Modern Course Cards */
        .modern-course-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .modern-course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .course-card-header {
            padding: 1rem 1rem 0;
            display: flex;
            justify-content: flex-end;
        }

        .badge-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .course-card-body {
            padding: 0 1rem 1.5rem;
        }

        .course-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .course-category {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .progress-section {
            margin-bottom: 1rem;
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
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 3px;
            transition: width 0.3s ease;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .empty-state h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0 1rem 2rem;
            }

            .dashboard-header .container-fluid {
                padding: 1rem;
            }

            .header-stats {
                margin-top: 1rem;
            }

            .chart-card {
                height: auto;
                min-height: 300px;
            }

            .progress-chart {
                height: 200px;
            }

            .chart-bars {
                height: 120px;
            }

            .bar-container {
                height: 100px;
            }

            .progress-circle {
                width: 120px;
                height: 120px;
            }

            .progress-circle::before {
                width: 95px;
                height: 95px;
            }

            .progress-value h3 {
                font-size: 1.25rem;
            }
        }

        /* Fix for the avatar placeholder */
        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .user-avatar i {
            font-size: 1.5rem;
            color: white;
        }
    </style>
</x-app-layout>
