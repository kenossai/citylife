<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Membership Interest</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #410d6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #5d04a7;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
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
            <h1>New Membership Interest</h1>
        </div>

        <div class="content">
            <p>Hello {{ $admin->name }},</p>

            <p>A new user has expressed interest in joining CityLife Church and is waiting for approval.</p>

            <div class="info-box">
                <div class="info-item">
                    <span class="info-label">Email Address:</span>
                    <span class="info-value">{{ $interest->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Submitted On:</span>
                    <span class="info-value">{{ $interest->created_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ ucfirst($interest->status) }}</span>
                </div>
            </div>

            <div class="alert">
                <strong>Action Required</strong><br>
                Please review this membership interest and approve it to send the registration link to the user.
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/registration-interests') }}" class="button">
                    Review in Admin Panel
                </a>
            </div>

            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>Next Steps:</strong><br>
                1. Click the button above to access the admin panel<br>
                2. Review the membership interest<br>
                3. Approve or reject the request<br>
                4. Once approved, the user will automatically receive a registration link via email
            </p>
        </div>

        <div class="footer">
            <p>This is an automated notification from CityLife Church Management System</p>
            <p>© {{ date('Y') }} CityLife Church. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
