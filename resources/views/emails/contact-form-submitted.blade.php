<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission - {{ $submission->subject }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 300; }
        .content { padding: 30px; }
        .submission-details { background: #f8f9fa; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .detail-row { display: flex; margin-bottom: 10px; }
        .detail-label { font-weight: 600; color: #495057; min-width: 100px; }
        .detail-value { color: #6c757d; }
        .message-section { background: #fff; border: 1px solid #e9ecef; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .message-section h3 { margin-top: 0; color: #495057; }
        .message-content { background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #667eea; font-style: italic; }
        .actions { background: #f8f9fa; padding: 20px; text-align: center; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 500; }
        .btn:hover { background: #5a6fd8; }
        .footer { background: #343a40; color: #adb5bd; padding: 20px; text-align: center; font-size: 14px; }
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-new { background: #dc3545; color: white; }
        .gdpr-info { background: #e8f5e8; border: 1px solid #28a745; border-radius: 4px; padding: 10px; margin: 15px 0; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
            <p>{{ config('app.name') }} - Contact Management System</p>
        </div>

        <div class="content">
            <div class="submission-details">
                <h3>📋 Submission Details</h3>

                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $submission->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">
                        <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
                    </span>
                </div>

                @if($submission->phone)
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">
                        <a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a>
                    </span>
                </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Subject:</span>
                    <span class="detail-value"><strong>{{ $submission->subject }}</strong></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Submitted:</span>
                    <span class="detail-value">{{ $submission->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">IP Address:</span>
                    <span class="detail-value">{{ $submission->ip_address }}</span>
                </div>
            </div>

            <div class="message-section">
                <h3>💬 Message Content</h3>
                <div class="message-content">
                    {{ $submission->message }}
                </div>
            </div>

            @if($submission->gdpr_consent)
            <div class="gdpr-info">
                ✅ <strong>GDPR Consent:</strong> The sender has provided explicit consent for data processing as required by GDPR regulations.
            </div>
            @endif

            <div class="actions">
                <a href="{{ config('app.url') }}/admin/contact-submissions/{{ $submission->id }}/edit" class="btn">
                    📝 View & Respond in Admin Panel
                </a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }} Contact Management System.</p>
            <p>Please do not reply to this email. Use the admin panel to respond to the submission.</p>
        </div>
    </div>
</body>
</html>
