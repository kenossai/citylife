<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Has Been Approved - CityLife Church</title>
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
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .highlight-box p {
            margin: 0;
            font-size: 15px;
            color: #444444;
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
        .footer .social-links {
            margin: 20px 0 10px 0;
        }
        .footer .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #361a51;
            font-size: 20px;
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
                    We're thrilled to officially welcome you to the CityLife Church family!
                    Your membership account has been reviewed and <strong>approved</strong> by our admin team.
                </p>

                <p class="message">
                    You can now access your member portal, register for courses, view upcoming events,
                    and connect with our vibrant church community.
                </p>

                <!-- CTA Button -->
                <div class="button-container">
                    <a href="{{ $loginUrl }}" class="cta-button">
                        Login to Your Account
                    </a>
                </div>

                <div class="divider"></div>

                <!-- What You Can Do Now -->
                <p class="message" style="font-weight: 600; color: #333333; margin-bottom: 20px;">
                    What you can do now:
                </p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>📚</span>
                        </div>
                        <div class="feature-text">
                            <strong>Access Church Development Course</strong>
                            <p>You've been automatically enrolled! Start your spiritual growth journey today.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>📅</span>
                        </div>
                        <div class="feature-text">
                            <strong>Register for Events & Programs</strong>
                            <p>View upcoming events, register for conferences, and join community programs.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>👥</span>
                        </div>
                        <div class="feature-text">
                            <strong>Connect with the Community</strong>
                            <p>Join small groups, volunteer opportunities, and fellowship activities.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span>🎯</span>
                        </div>
                        <div class="feature-text">
                            <strong>Track Your Progress</strong>
                            <p>Monitor your course completion, attendance, and spiritual milestones.</p>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Next Steps -->
                <div class="highlight-box">
                    <p>
                        <strong>Get Started Today!</strong><br>
                        Log in to your account and complete your member profile. This helps us serve you better
                        and keep you updated on programs that match your interests.
                    </p>
                </div>

                <!-- Support Section -->
                <p class="message" style="font-size: 14px;">
                    <strong>Need Help?</strong><br>
                    If you have any questions or need assistance accessing your account,
                    we're here for you! Contact us at
                    <a href="mailto:info@citylifechurch.org" style="color: #361a51; text-decoration: none;">
                        info@citylifechurch.org
                    </a>
                </p>

                <p class="secondary-text">
                    This is an automated message. Please do not reply directly to this email.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="font-weight: 600; color: #555555; margin-bottom: 15px;">
                    Welcome to the family!<br>
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
                    You're receiving this email because your membership application has been approved.<br>
                    If you did not apply for membership, please contact us immediately.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
