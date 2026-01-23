<x-app-layout>
@section('title', 'My Courses Dashboard')

<style>
    .dashboard-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        padding: 60px 0;
    }

    .dashboard-container {
        background: white;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-sidebar {
        /* background: #f8f9fa; */
        padding: 50px 35px;
        border-right: 1px solid #dee2e6;
        min-height: 700px;
    }

    .dashboard-avatar-box {
        text-align: center;
        margin-bottom: 35px;
    }

    .dashboard-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 36px;
        color: #130435;
    }

    .dashboard-user-name {
        font-size: 22px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 5px;
    }

    .dashboard-user-email {
        font-size: 14px;
        color: #718096;
    }

    .dashboard-stats-box {
        background-color:  #470068c6;
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.8);
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
    }

    .dashboard-section-label {
        font-size: 13px;
        font-weight: 700;
        color: #1a202c;
        margin: 25px 0 18px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .dashboard-nav-list {
        list-style: none;
        padding: 0;
        margin: 0 0 25px 0;
    }

    .dashboard-nav-list li {
        margin-bottom: 8px;
    }

    .dashboard-nav-link {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        color: #4a5568;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.25s ease;
        font-size: 15px;
        font-weight: 500;
    }

    .dashboard-nav-link i {
        margin-right: 14px;
        font-size: 17px;
        width: 22px;
        text-align: center;
    }

    .dashboard-nav-link:hover {
        background: rgba(179, 92, 246, 0.1);
        color: #420061;
    }

    .dashboard-nav-link.active {
        background: #1a202c;
        color: white;
    }

    .dashboard-yellow-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 16px 28px;
        background: #fbbf24;
        color: #1a202c;
        border: none;
        border-radius: 60px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(251, 191, 36, 0.3);
    }

    .dashboard-yellow-btn i {
        margin-right: 10px;
        font-size: 17px;
    }

    .dashboard-yellow-btn:hover {
        background: #f59e0b;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        color: #1a202c;
    }

    .dashboard-yellow-btn.outline {
        background: white;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .dashboard-yellow-btn.outline:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-3px);
    }

    .dashboard-main-content {
        /* background: linear-gradient(135deg, #5a4a7a 0%, #4a3968 100%); */
        padding: 40px 35px;
        min-height: 700px;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }

    .course-card {
        background: rgb(240, 240, 240);
        border-radius: 20px;
        padding: 25px;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .course-card-header {
        margin-bottom: 15px;
    }

    .course-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-primary {
        background: #d7fedb;
        color: #3b3e48;
    }

    .badge-secondary {
        background: #e5e7eb;
        color: #4b5563;
    }

    .course-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 8px;
    }

    .course-category {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .progress-section {
        margin: 15px 0;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .progress-bar-container {
        height: 8px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #5cf676, #8bfa94);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .course-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .course-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .course-btn-primary {
        background: #eba500;
        color: white;
    }

    .course-btn-primary:hover {
        background: #50016a;
        transform: translateY(-2px);
        color: white;
    }

    .course-btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .course-btn-secondary:hover {
        background: #f3ddff;
        transform: translateY(-2px);
        color: #374151;
    }

    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: white;
    }

    .empty-state i {
        font-size: 72px;
        color: rgba(255,255,255,0.3);
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: white;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: rgba(255,255,255,0.7);
        margin-bottom: 25px;
    }

    .section-heading {
        color: rgb(62, 62, 62);
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .section-subheading {
        color: rgba(94, 94, 94, 0.8);
        font-size: 16px;
        margin-bottom: 20px;
    }

    .alert {
        border-radius: 18px;
        margin-bottom: 25px;
    }
</style>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row dashboard-container g-0">
            <!-- Left Sidebar -->
            <div class="col-lg-4">
                <div class="dashboard-sidebar">
                    <div class="dashboard-avatar-box">
                        <div class="dashboard-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="dashboard-user-name">{{ $member->first_name }} {{ $member->last_name }}</h3>
                        <p class="dashboard-user-email">{{ $member->email }}</p>
                    </div>

                    <!-- Stats Box -->
                    <div class="dashboard-stats-box">
                        <div class="stat-row">
                            <span class="stat-label">Enrolled Courses</span>
                            <span class="stat-value">{{ $enrollments->count() }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Completed</span>
                            <span class="stat-value">{{ $enrollments->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">In Progress</span>
                            <span class="stat-value">{{ $enrollments->where('status', 'active')->count() }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Certificates</span>
                            <span class="stat-value">{{ $enrollments->where('certificate_issued', true)->count() }}</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="dashboard-section-label">Quick Actions</h4>
                        <ul class="dashboard-nav-list">
                            <li>
                                <a href="{{ route('courses.index') }}" class="dashboard-nav-link">
                                    <i class="fas fa-search"></i> Browse Courses
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('member.profile') }}" class="dashboard-nav-link">
                                    <i class="fas fa-user-circle"></i> My Profile
                                </a>
                            </li>
                        </ul>

                        <form method="POST" action="{{ route('member.logout') }}">
                            @csrf
                            <button type="submit" class="dashboard-yellow-btn outline">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-lg-8">
                <div class="dashboard-main-content">
                    <h2 class="section-heading">My Courses</h2>
                    <p class="section-subheading">Continue your learning journey</p>

                    @if($enrollments->count() > 0)
                        <div class="courses-grid">
                            @foreach($enrollments as $enrollment)
                                @php
                                    $course = $enrollment->course;
                                    $progressPercentage = $enrollment->progress_percentage;
                                @endphp

                                <div class="course-card">
                                    <div class="course-card-header">
                                        <span class="course-badge {{ $enrollment->status === 'completed' ? 'badge-success' : ($enrollment->status === 'active' ? 'badge-primary' : 'badge-secondary') }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>

                                    <h6 class="course-title">{{ $course->title }}</h6>
                                    <p class="course-category">{{ $course->category }}</p>
                                    <p class="course-category">
                                        Enrolled: {{ $enrollment->enrollment_date->format('M d, Y') }}
                                    </p>

                                    <div class="progress-section">
                                        <div class="progress-info">
                                            <span class="progress-text">{{ round($progressPercentage) }}% Complete</span>
                                            <span class="lessons-count">{{ $enrollment->completed_lessons }}/{{ $course->lessons->count() }}</span>
                                        </div>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill" style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div class="course-actions">
                                        @if($enrollment->status === 'completed' && $enrollment->certificate_issued)
                                            <a href="{{ route('courses.certificate.download', $enrollment->id) }}" class="course-btn course-btn-primary">
                                                <i class="fas fa-download"></i> Certificate
                                            </a>
                                        @else
                                            <a href="{{ route('courses.lessons', $course->slug) }}" class="course-btn course-btn-primary">
                                                <i class="fas fa-play"></i> Continue
                                            </a>
                                        @endif
                                        <a href="{{ route('courses.show', $course->slug) }}" class="course-btn course-btn-secondary">
                                            <i class="fas fa-info-circle"></i> Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>No Courses Yet</h3>
                            <p>You haven't enrolled in any courses yet. Browse our course catalog to get started!</p>
                            <a href="{{ route('courses.index') }}" class="dashboard-yellow-btn" style="max-width: 300px; margin: 0 auto;">
                                <i class="fas fa-search"></i> Browse Courses
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
