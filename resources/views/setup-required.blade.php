<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Required - CityLife Church</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 28px;
        }
        p {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .status {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .actions {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-right: 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #718096;
        }
        .btn-secondary:hover {
            background: #4a5568;
        }
        code {
            background: #f7fafc;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 14px;
            color: #e53e3e;
        }
        .steps {
            background: #f7fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .steps ol {
            margin-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏗️ Setup Required</h1>

        <div class="status">
            <strong>Status:</strong> CityLife Church is almost ready!
        </div>

        <p>
            Your Laravel application has been successfully deployed to Laravel Cloud, but the database tables need to be created.
        </p>

        <div class="steps">
            <strong>Next Steps:</strong>
            <ol>
                <li>Access the admin panel at <code>/admin</code></li>
                <li>Complete the initial setup and create your admin account</li>
                <li>Start adding content through the Filament admin interface</li>
            </ol>
        </div>

        <p>
            If you're seeing this page, it means:
        </p>
        <ul style="margin-left: 20px; color: #4a5568; line-height: 2;">
            <li>✅ Your app is deployed successfully</li>
            <li>✅ Database connection is working</li>
            <li>⏳ Database tables need to be created</li>
        </ul>

        <div class="actions">
            <a href="/admin" class="btn">Go to Admin Panel</a>
            <a href="/health" class="btn btn-secondary">Check Health Status</a>
        </div>

        @if(isset($error))
        <p style="margin-top: 20px; font-size: 12px; color: #718096;">
            Technical details: {{ $error }}
        </p>
        @endif
    </div>
</body>
</html>
