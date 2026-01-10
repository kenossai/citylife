<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Registration - CityLife Church</title>
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
            /* border-left: 4px solid #667eea; */
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
            background-color: #361a51 ;
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .expiry-notice {
            background-color: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 6px;
            padding: 15px;
            margin: 25px 0;
            text-align: center;
        }
        .expiry-notice p {
            margin: 0;
            color: #f57c00;
            font-size: 14px;
            font-weight: 500;
        }
        .expiry-notice .icon {
            font-size: 20px;
            margin-right: 5px;
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
            color: #667eea;
            text-decoration: none;
        }
        .footer .social-links {
            margin: 20px 0 10px 0;
        }
        .footer .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
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
                    <strong style="font-size: 20px; color: #ff6b35;">CityLife Church</strong>
                @endif
                <p class="text-secondary">Building Faith, Changing Lives</p>
            </div>

            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Welcome to the Family!</p>

                <p class="message">
                    We're absolutely delighted that you've expressed interest in joining CityLife Church.
                    Your registration request has been <strong>approved</strong>, and we can't wait to have
                    you as part of our community!
                </p>

                <p class="message">
                    You're just one step away from becoming an official member. Click the button below
                    to complete your registration and unlock access to all our resources and programs.
                </p>

                <!-- CTA Button -->
                <div class="button-container">
                    <a href="{{ $registrationUrl }}" class="cta-button">
                        Complete Your Registration
                    </a>
                </div>

                <!-- Expiry Notice -->
                <div class="expiry-notice">
                    <p>
                        <strong>Important:</strong> This invitation link will expire in 7 days.
                        Please complete your registration soon!
                    </p>
                </div>

                <div class="divider"></div>

                <!-- What's Next Section -->
                <p class="message" style="font-weight: 600; color: #333333; margin-bottom: 20px;">
                    What happens after registration?
                </p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-text">
                            <strong>Church Development Course (CDC)</strong>
                            <p>Automatically enrolled! Start your spiritual growth journey with our comprehensive course.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-text">
                            <strong>Member Dashboard</strong>
                            <p>Access your personalized dashboard with courses, events, and community resources.</p>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Support Section -->
                <div class="highlight-box">
                    <p>
                        <strong>Need Help?</strong><br>
                        If you have any questions or need assistance with your registration,
                        we're here for you! Contact us at
                        <a href="mailto:info@citylifechurch.org" style="color: #667eea; text-decoration: none;">
                            info@citylifechurch.org
                        </a>
                    </p>
                </div>

                <p class="secondary-text">
                    This is an automated message. Please do not reply directly to this email.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="font-weight: 600; color: #555555; margin-bottom: 15px;">
                    Blessings,<br>
                    The CityLife Church Team
                </p>

                {{-- <div class="social-links">
                    <a href="#" title="Facebook">📘</a>
                    <a href="#" title="Instagram">📷</a>
                    <a href="#" title="Twitter">🐦</a>
                    <a href="#" title="YouTube">▶️</a>
                </div> --}}

                <div class="divider" style="margin: 20px 0;"></div>

                <p style="font-size: 12px; color: #999999;">
                    CityLife Church<br>
                    Building Faith, Changing Lives<br>
                    <a href="mailto:info@citylifechurch.org">info@citylifechurch.org</a>
                </p>

                <p style="font-size: 11px; color: #aaaaaa; margin-top: 15px;">
                    You're receiving this email because you requested to join CityLife Church.<br>
                    This invitation is personal and cannot be transferred.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
