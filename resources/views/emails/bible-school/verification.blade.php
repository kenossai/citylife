<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bible School International Access Code</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            -webkit-font-smoothing: antialiased;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1a3560 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .header p {
            margin: 0;
            font-size: 15px;
            color: rgba(255,255,255,0.85);
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 18px;
            font-size: 16px;
        }
        .otp-box {
            background: #f0f5ff;
            border: 2px dashed #2c5aa0;
            border-radius: 10px;
            padding: 28px 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c5aa0;
            margin-bottom: 12px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 6px;
            color: #1a3560;
            font-family: 'Courier New', Courier, monospace;
        }
        .otp-expiry {
            font-size: 13px;
            color: #888;
            margin-top: 12px;
        }
        .speaker-name {
            font-weight: 600;
            color: #2c5aa0;
        }
        .note {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 14px 18px;
            border-radius: 0 6px 6px 0;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }
        .footer {
            background: #f9f9f9;
            border-top: 1px solid #eeeeee;
            padding: 24px 30px;
            text-align: center;
            font-size: 13px;
            color: #999999;
        }
        .footer a { color: #2c5aa0; text-decoration: none; }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        <div class="header">
            <h1>Bible School International</h1>
            <p>Your one-time access code</p>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>
                You requested access to the teaching sessions from
                <span class="speaker-name">{{ $speakerName }}</span>.
                Use the code below to unlock all resources.
            </p>

            <div class="otp-box">
                <div class="otp-label">Your Access Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">This code expires in 10 minutes</div>
            </div>

            <p>Simply enter this code on the speaker page to gain immediate access to all videos, audio sessions and materials.</p>

            <div class="note">
                If you did not request this code, you can safely ignore this email.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CityLife Church &mdash; Bible School International<br>
            <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
        </div>

    </div>
</div>
</body>
</html>
