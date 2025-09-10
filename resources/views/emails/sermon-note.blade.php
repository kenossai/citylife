<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermon Notes: {{ $series->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: left;
            margin-bottom: 20px;
        }
        .greeting {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin: 20px 0 10px 0;
            text-decoration: underline;
        }
        .section-content {
            margin-bottom: 20px;
        }
        .signature {
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .church-info {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .church-logo {
            margin: 20px 0;
        }
        .contact-info {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        .contact-info a {
            color: #0066cc;
            text-decoration: none;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="greeting">Hello {{ $recipientName }},</div>

        <div class="section-title">{{ $series->title }} - Sermon Notes</div>
        <div class="section-content">
            @if($series->summary)
            <p>{{ $series->summary }}</p>
            @endif

            @if($series->pastor)
            <p><strong>Speaker:</strong> {{ $series->pastor }}</p>
            @endif

            @if($series->series_date)
            <p><strong>Date:</strong> {{ $series->series_date->format('F j, Y') }}</p>
            @endif

            @if($series->scripture_references)
            <p><strong>Scripture References:</strong> {{ $series->scripture_references }}</p>
            @endif

            <p>Please find the complete sermon notes attached to this email as a PDF document. These notes include key points, scripture references, and practical applications from the message.</p>

            @if($series->video_url)
            <p>You can also watch the full message online at: <a href="{{ $series->video_url }}">{{ $series->video_url }}</a></p>
            @endif

            @if($series->audio_url)
            <p>Or listen to the audio version at: <a href="{{ $series->audio_url }}">{{ $series->audio_url }}</a></p>
            @endif
        </div>

        <div class="signature">
            <p>Thank you very much,</p>
            <p><strong>The CityLife Team</strong></p>
        </div>

        <div class="church-info">
            <div class="church-logo">
                <strong>CityLife Church</strong>
            </div>

            <div class="contact-info">
                <p><strong>City Life International Church</strong></p>
                <p>1 South Parade, Spaldesmoor, Sheffield S3 8ZZ</p>
                <p><strong>Tel:</strong> 0114 272 8243 (ext 1) | <strong>Mon-Sat:</strong> 10:00 am – 3:00 pm</p>
                <p><a href="mailto:office@citylifecc.com">office@citylifecc.com</a></p>
                <p><a href="https://en-gb.facebook.com/drjimmaster/">https://en-gb.facebook.com/drjimmaster/</a></p>
                <p><a href="mailto:bibleschool@citylifecc.com">bibleschool@citylifecc.com</a></p>
                <p><a href="https://www.youtube.com/channel/UCTP2_DfFmZg5oFu6alMvA">YouTube Channel</a></p>
            </div>
        </div>
    </div>
</body>
</html>
