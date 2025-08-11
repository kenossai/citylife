<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply from {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 300; }
        .content { padding: 30px; }
        .greeting { background: #f8f9fa; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .reply-content { background: #fff; border: 1px solid #e9ecef; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .original-message { background: #f8f9fa; border-left: 4px solid #6c757d; padding: 15px; margin: 20px 0; }
        .original-message h4 { margin-top: 0; color: #495057; }
        .contact-info { background: #e8f5e8; border: 1px solid #28a745; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .contact-info h3 { margin-top: 0; color: #155724; }
        .footer { background: #343a40; color: #adb5bd; padding: 20px; text-align: center; font-size: 14px; }
        .signature { border-top: 1px solid #e9ecef; padding-top: 20px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✉️ {{ config('app.name') }}</h1>
            <p>Thank you for reaching out to us</p>
        </div>

        <div class="content">
            <div class="greeting">
                <p>Dear {{ $submission->name }},</p>
                <p>Thank you for contacting {{ config('app.name') }}. We have received your message and are pleased to respond.</p>
            </div>

            <div class="reply-content">
                <h3>Our Response:</h3>
                {!! nl2br(e($replyMessage)) !!}
            </div>

            <div class="original-message">
                <h4>Your Original Message ({{ $submission->created_at->format('M d, Y \a\t g:i A') }}):</h4>
                <p><strong>Subject:</strong> {{ $submission->subject }}</p>
                <p><strong>Message:</strong></p>
                <p>{{ $submission->message }}</p>
            </div>

            <div class="contact-info">
                <h3>📞 Get in Touch</h3>
                <p>If you have any further questions or would like to continue this conversation, please feel free to contact us:</p>
                <ul>
                    <li><strong>Email:</strong> <a href="mailto:{{ $churchContact->email ?? 'info@citylifechurch.org' }}">{{ $churchContact->email ?? 'info@citylifechurch.org' }}</a></li>
                    <li><strong>Phone:</strong> <a href="tel:{{ $churchContact->phone ?? '(555) 123-4567' }}">{{ $churchContact->phone ?? '(555) 123-4567' }}</a></li>
                    @if($churchContact && $churchContact->address)
                    <li><strong>Address:</strong> {{ $churchContact->full_address }}</li>
                    @endif
                    @if($churchContact && $churchContact->office_hours)
                    <li><strong>Office Hours:</strong> {{ $churchContact->office_hours }}</li>
                    @endif
                </ul>
            </div>

            <div class="signature">
                <p>Blessings,<br>
                <strong>{{ $respondedBy->name ?? 'CityLife Church Team' }}</strong><br>
                {{ config('app.name') }}</p>

                @if($churchContact && $churchContact->website_url)
                <p><a href="{{ $churchContact->website_url }}">{{ $churchContact->website_url }}</a></p>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>This email was sent from {{ config('app.name') }} in response to your contact form submission.</p>
            <p>Please do not reply to this email address. Use the contact information provided above for further communication.</p>
        </div>
    </div>
</body>
</html>
