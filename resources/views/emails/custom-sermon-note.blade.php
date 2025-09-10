<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $series->title }}</title>
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
        .message-content {
            margin-bottom: 30px;
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
        /* Rich editor content styling */
        .message-content h1, .message-content h2, .message-content h3 {
            margin: 20px 0 10px 0;
        }
        .message-content ul, .message-content ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .message-content strong {
            font-weight: bold;
        }
        .message-content em {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-content">
            {!! $customMessage !!}
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
