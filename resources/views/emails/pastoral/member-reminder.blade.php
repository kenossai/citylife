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
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .message-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 30px 0;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.5;
        }
        .verse-box {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
            font-style: italic;
            text-align: left;
        }
        .verse-text {
            font-size: 16px;
            color: #495057;
            margin-bottom: 10px;
        }
        .verse-reference {
            font-size: 14px;
            color: #6c757d;
            font-weight: 600;
        }
        .celebration-icons {
            font-size: 48px;
            margin: 20px 0;
        }
        .contact-info {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .contact-info h3 {
            margin: 0 0 15px 0;
            color: #1565c0;
            font-size: 18px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
            color: #666;
        }
        .footer img {
            max-width: 100px;
            margin-bottom: 15px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .message-box {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="celebration-icons">
                @if($reminder->reminder_type === 'birthday')
                    🎂🎉🎈
                @elseif($reminder->reminder_type === 'wedding_anniversary')
                    💕💐💍
                @elseif($reminder->reminder_type === 'baptism_anniversary')
                    💧✝️🙏
                @elseif($reminder->reminder_type === 'membership_anniversary')
                    🏠🤝❤️
                @elseif($reminder->reminder_type === 'salvation_anniversary')
                    ✝️🌟🙌
                @else
                    🎊✨🌟
                @endif
            </div>
            <h1>{{ $notification->subject }}</h1>
            <p>From Your Church Family at City Life</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="message-box">
                {{ $notification->message }}
            </div>

            @if($reminder->reminder_type === 'birthday')
                <div class="verse-box">
                    <div class="verse-text">
                        "For I know the plans I have for you," declares the Lord, "plans to prosper you and not to harm you, to give you hope and a future."
                    </div>
                    <div class="verse-reference">- Jeremiah 29:11</div>
                </div>
            @elseif($reminder->reminder_type === 'wedding_anniversary')
                <div class="verse-box">
                    <div class="verse-text">
                        "Above all, love each other deeply, because love covers over a multitude of sins."
                    </div>
                    <div class="verse-reference">- 1 Peter 4:8</div>
                </div>
            @elseif($reminder->reminder_type === 'baptism_anniversary')
                <div class="verse-box">
                    <div class="verse-text">
                        "Therefore, if anyone is in Christ, the new creation has come: The old has gone, the new is here!"
                    </div>
                    <div class="verse-reference">- 2 Corinthians 5:17</div>
                </div>
            @elseif($reminder->reminder_type === 'membership_anniversary')
                <div class="verse-box">
                    <div class="verse-text">
                        "And let us consider how we may spur one another on toward love and good deeds, not giving up meeting together, as some are in the habit of doing, but encouraging one another."
                    </div>
                    <div class="verse-reference">- Hebrews 10:24-25</div>
                </div>
            @endif

            @if($reminder->years_count)
                <p style="font-size: 18px; color: #495057; margin: 25px 0;">
                    <strong>{{ $reminder->years_count }} {{ $reminder->years_count == 1 ? 'Year' : 'Years' }}</strong> of
                    @if($reminder->reminder_type === 'birthday')
                        God's blessings in your life! 🌟
                    @elseif($reminder->reminder_type === 'wedding_anniversary')
                        love, commitment, and God's faithfulness! 💕
                    @elseif($reminder->reminder_type === 'baptism_anniversary')
                        walking with Jesus! ✝️
                    @elseif($reminder->reminder_type === 'membership_anniversary')
                        being part of our church family! 🏠
                    @elseif($reminder->reminder_type === 'salvation_anniversary')
                        life in Christ! 🙌
                    @endif
                </p>
            @endif

            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <p style="margin: 0; font-size: 16px; color: #856404;">
                    <strong>🙏 You are in our prayers</strong><br>
                    Our pastoral team is praying for you and your family. If you need prayer, support, or just want to chat, please don't hesitate to reach out to us.
                </p>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <h3>📞 Get in Touch</h3>
            <p style="margin: 5px 0;"><strong>Church Office:</strong> <a href="tel:+44114234567">0114 234 567</a></p>
            <p style="margin: 5px 0;"><strong>Pastor:</strong> <a href="mailto:pastor@citylifecc.com">pastor@citylifecc.com</a></p>
            <p style="margin: 5px 0;"><strong>Prayer Requests:</strong> <a href="mailto:prayer@citylifecc.com">prayer@citylifecc.com</a></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>City Life Christian Centre</strong><br>
            Your Church Family</p>

            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">YouTube</a>
            </div>

            <p style="font-size: 12px; margin-top: 20px; color: #999;">
                You received this message because you're a valued member of our church family.<br>
                If you'd prefer not to receive these messages, please contact the church office.
            </p>
        </div>
    </div>
</body>
</html>
