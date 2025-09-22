<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $notification->subject }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .member-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .member-info h3 {
            margin: 0 0 10px 0;
            color: #1565c0;
            font-size: 18px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #5a6fd8;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px 15px;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 Pastoral Care Reminder</h1>
            <p>City Life Christian Centre</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="message-box">
                <p style="font-size: 18px; margin: 0;">{{ $notification->message }}</p>
            </div>

            <div class="member-info">
                <h3>📋 Member Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $member->first_name }} {{ $member->last_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $member->email ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $member->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Membership Status:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $member->membership_status)) }}</span>
                    </div>
                    @if($reminder->years_count)
                    <div class="info-item">
                        <span class="info-label">Years:</span>
                        <span class="info-value">{{ $reminder->years_count }} years</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ $reminder->reminder_date->format('F j, Y') }}</span>
                    </div>
                </div>
            </div>

            @if($reminder->description)
            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>📝 Additional Notes:</strong><br>
                {{ $reminder->description }}
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/admin/pastoral-reminders/{{ $reminder->id }}/edit" class="button">
                    View in Admin Panel
                </a>
            </div>

            <div style="background-color: #e8f5e8; border: 1px solid #c3e6c3; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>💡 Suggested Actions:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    @if($reminder->reminder_type === 'birthday')
                        <li>Send a personal birthday message or card</li>
                        <li>Consider a phone call or visit</li>
                        <li>Pray for their upcoming year</li>
                    @elseif(str_contains($reminder->reminder_type, 'anniversary'))
                        <li>Acknowledge their milestone publicly (if appropriate)</li>
                        <li>Send congratulations from the pastoral team</li>
                        <li>Consider a special recognition or gift</li>
                    @else
                        <li>Reach out with a personal message</li>
                        <li>Schedule a follow-up if needed</li>
                        <li>Keep them in your prayers</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>City Life Christian Centre</strong><br>
            Pastoral Care Team<br>
            <a href="mailto:pastor@citylifecc.com">pastor@citylifecc.com</a></p>
            <p style="font-size: 12px; margin-top: 15px;">
                This is an automated reminder from your church management system.
            </p>
        </div>
    </div>
</body>
</html>
