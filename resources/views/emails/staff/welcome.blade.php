<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CityLife Church</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ff6b35;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        h1 {
            color: #ff6b35;
            margin: 0;
            font-size: 24px;
        }
        .welcome-message {
            font-size: 16px;
            margin-bottom: 25px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #ff6b35;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .credential-item {
            margin: 10px 0;
        }
        .credential-label {
            font-weight: 600;
            color: #666;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin-left: 10px;
        }
        .cta-button {
            display: inline-block;
            background-color: #ff6b35;
            color: #ffffff;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 25px 0;
            font-weight: 600;
            text-align: center;
        }
        .info-section {
            background-color: #fff3e0;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-section h4 {
            margin-top: 0;
            color: #e65100;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="CityLife Church" class="logo">
            <h1>Welcome to the Team!</h1>
        </div>

        <div class="welcome-message">
            <p>Dear {{ $user->first_name ?? $user->name }},</p>

            <p>We're excited to welcome you to the CityLife Church staff team! Your account has been created and you now have access to our staff portal.</p>
        </div>

        <div class="credentials-box">
            <h3>Your Login Credentials</h3>
            <div class="credential-item">
                <span class="credential-label">Email:</span>
                <span class="credential-value">{{ $user->email }}</span>
            </div>
            @if($temporaryPassword)
            <div class="credential-item">
                <span class="credential-label">Temporary Password:</span>
                <span class="credential-value">{{ $temporaryPassword }}</span>
            </div>
            @endif
        </div>

        @if($temporaryPassword)
        <div class="info-section">
            <h4>⚠️ Important Security Notice</h4>
            <p>For security reasons, you will be required to change your password upon first login. Please keep this temporary password secure and do not share it with anyone.</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ $loginUrl ?? route('filament.admin.auth.login') }}" class="cta-button">Access Staff Portal</a>
        </div>

        @if($loginUrl)
        <div class="info-section">
            <h4>🔒 Secure Login Link</h4>
            <p>This secure login link is valid for 24 hours and will take you directly to the admin portal. For security reasons, this link can only be used once.</p>
        </div>
        @endif

        <div class="welcome-message">
            <h3>Getting Started</h3>
            <p>Here's what you can do with your staff account:</p>
            <ul>
                <li>Access the administrative dashboard</li>
                <li>Manage church resources and content</li>
                <li>View and update member information</li>
                <li>Coordinate events and activities</li>
                <li>Communicate with the team</li>
            </ul>

            <p>If you have any questions or need assistance, please don't hesitate to reach out to your supervisor or the IT team.</p>

            <p><strong>Your Role:</strong> {{ $user->job_title ?? 'Staff Member' }}</p>
            @if($user->department)
            <p><strong>Department:</strong> {{ $user->department }}</p>
            @endif
        </div>

        <div class="footer">
            <p>This is an automated message from CityLife Church.<br>
            If you received this email in error, please contact us immediately.</p>
            <p style="margin-top: 15px;">
                <strong>CityLife Church</strong><br>
                1 South Parade, Sheffield S3 8SS<br>
                <a href="mailto:info@citylifechurch.org">info@citylifechurch.org</a>
            </p>
        </div>
    </div>
</body>
</html>
