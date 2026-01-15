<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration Confirmation - CityLife Church</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f4;
            padding: 20px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
            color: #636363
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #333333;
            margin: 0 0 20px 0;
        }
        .message {
            font-size: 16px;
            color: #555555;
            margin: 0 0 20px 0;
        }
        .highlight-box {
            background-color: #f8f9ff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .highlight-box p {
            margin: 0;
            font-size: 15px;
            color: #444444;
        }
        .course-info-box {
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .course-info-box h3 {
            margin: 0 0 15px 0;
            color: #474747;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #333333;
            min-width: 120px;
        }
        .info-value {
            color: #555555;
            flex: 1;
        }
        .cta-button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #361a51;
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(54, 26, 81, 0.4);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(54, 26, 81, 0.5);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .features {
            display: table;
            width: 100%;
            margin: 30px 0;
        }
        .feature-item {
            display: table-row;
        }
        .feature-icon {
            display: table-cell;
            width: 40px;
            padding: 10px 10px 10px 0;
            vertical-align: top;
        }
        .feature-icon span {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #361a51 0%, #5a3a7a 100%);
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            color: #ffffff;
            font-size: 16px;
        }
        .feature-text {
            display: table-cell;
            padding: 10px 0;
            vertical-align: top;
        }
        .feature-text strong {
            color: #333333;
            font-size: 16px;
        }
        .feature-text p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666666;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
        .success-badge {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 30px;
            text-align: center;
            color: #777777;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #361a51;
            text-decoration: none;
        }
        .secondary-text {
            font-size: 14px;
            color: #888888;
            margin: 15px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0 10px;
            }
            .header h1 {
                font-size: 24px;
            }
            .content {
                padding: 30px 20px;
            }
            .cta-button {
                display: block;
                padding: 14px 30px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                @php
                    $logoPath = public_path('assets/images/logo_small_black.png');
                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                @endphp
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="CityLife Church" class="logo">
                @else
                    <strong style="font-size: 20px; color: #361a51;">CityLife Church</strong>
                @endif
                <p class="text-secondary">Building Faith, Changing Lives</p>
            </div>

            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Hello {{ $member->first_name }}! 👋</p>

                <p class="message">
                    Thank you for registering for <strong>{{ $course->title }}</strong>! We're excited to have you
                    join this course and grow in your faith journey with us.
                </p>

                <!-- Course Details -->
                <div class="course-info-box">
                    <h3>Course Details</h3>
                    <div class="info-row">
                        <div class="info-label">Course Name:</div>
                        <div class="info-value">{{ $course->title }}</div>
                    </div>
                    @if($course->start_date)
                    <div class="info-row">
                        <div class="info-label">Start Date:</div>
                        <div class="info-value">{{ $course->start_date->format('F j, Y') }}</div>
                    </div>
                    @endif
                    @if($course->end_date)
                    <div class="info-row">
                        <div class="info-label">End Date:</div>
                        <div class="info-value">{{ $course->end_date->format('F j, Y') }}</div>
                    </div>
                    @endif
                    @if($course->location)
                    <div class="info-row">
                        <div class="info-label">Location:</div>
                        <div class="info-value">{{ $course->location }}</div>
                    </div>
                    @endif
                    @if($course->instructor)
                    <div class="info-row">
                        <div class="info-label">Instructor:</div>
                        <div class="info-value">{{ $course->instructor }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Enrollment Date:</div>
                        <div class="info-value">{{ $enrollment->enrollment_date->format('F j, Y') }}</div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="button-container">
                    <a href="{{ $courseUrl }}" class="cta-button">
                        View Course Details
                    </a>
                </div>

                <div class="divider"></div>

                <!-- What's Next Section -->
                <p class="message" style="font-weight: 600; color: #333333; margin-bottom: 20px;">
                    What happens next?
                </p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>📧</span>
                        </div>
                        <div class="feature-text">
                            <strong>We'll Contact You Soon</strong>
                            <p>You'll receive additional details about the course schedule and materials.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>📱</span>
                        </div>
                        <div class="feature-text">
                            <strong>Access Your Dashboard</strong>
                            <p>Track your progress and access course materials through your member portal.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>🎓</span>
                        </div>
                        <div class="feature-text">
                            <strong>Prepare to Learn</strong>
                            <p>Get ready for an enriching learning experience that will strengthen your faith.</p>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Support Section -->
                <div class="highlight-box">
                    <p>
                        <strong>Need Help?</strong><br>
                        If you have any questions about the course or need assistance,
                        we're here for you! Contact us at
                        <a href="mailto:info@citylifechurch.org" style="color: #361a51; text-decoration: none;">
                            info@citylifechurch.org
                        </a>
                    </p>
                </div>

                <p class="secondary-text">
                    This is an automated confirmation email. Please do not reply directly to this email.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="font-weight: 600; color: #555555; margin-bottom: 15px;">
                    See you in class!<br>
                    Blessings,<br>
                    The CityLife Church Team
                </p>

                <div class="divider" style="margin: 20px 0;"></div>

                <p style="font-size: 12px; color: #999999;">
                    CityLife Church<br>
                    Building Faith, Changing Lives<br>
                    <a href="mailto:info@citylifechurch.org">info@citylifechurch.org</a>
                </p>

                <p style="font-size: 11px; color: #aaaaaa; margin-top: 15px;">
                    You're receiving this email because you registered for a course at CityLife Church.<br>
                    To manage your course enrollments, visit your member dashboard.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
