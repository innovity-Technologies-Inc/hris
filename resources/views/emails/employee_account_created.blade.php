<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #4a4a4a;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            background-color: {{ $primaryColor }};
            padding: 40px;
            text-align: center;
        }
        .content {
            padding: 40px;
            line-height: 1.6;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background-color: {{ $primaryColor }};
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 25px;
            box-shadow: 0 4px 6px rgba(151, 64, 99, 0.2);
        }
        .logo {
            max-width: 120px;
            height: auto;
        }
        .brand-name {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        h1 {
            font-size: 22px;
            color: #333;
            margin-top: 0;
        }
        p {
            margin-bottom: 20px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .credential-item {
            margin-bottom: 10px;
        }
        .credential-label {
            font-weight: bold;
            color: #666;
            width: 100px;
            display: inline-block;
        }
        .divider {
            border-top: 1px solid #eeeeee;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" align="center">
            <tr>
                <td class="header">
                    @if(isset($generalSettings->logo))
                        <img src="{{ asset('storage/' . $generalSettings->logo) }}" alt="{{ $appName }}" class="logo">
                    @elseif(isset($generalSettings->favicon))
                        <img src="{{ asset('storage/' . $generalSettings->favicon) }}" alt="{{ $appName }}" class="logo" style="max-width: 60px;">
                    @else
                        <div class="brand-name">{{ $appName }}</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>Welcome, {{ $name }}!</h1>
                    <p>Your employee account has been created successfully. You can now log in to the <strong>{{ $appName }}</strong> HRMS system using the following credentials:</p>
                    
                    <div class="credentials-box">
                        <div class="credential-item">
                            <span class="credential-label">Login URL:</span>
                            <a href="{{ $loginUrl }}" style="color: {{ $primaryColor }};">{{ $loginUrl }}</a>
                        </div>
                        <div class="credential-item">
                            <span class="credential-label">Email:</span>
                            <strong>{{ $email }}</strong>
                        </div>
                        <div class="credential-item">
                            <span class="credential-label">Password:</span>
                            <strong>{{ $password }}</strong>
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <a href="{{ $loginUrl }}" class="button">Login to Dashboard</a>
                    </div>

                    <p style="margin-top: 30px; font-size: 14px; color: #dc3545; font-weight: bold;">
                        Important: Please change your password after your first login for security purposes.
                    </p>
                    
                    <div class="divider"></div>
                    
                    <p style="font-size: 14px; color: #666;">
                        If you have any issues logging in, please contact your system administrator.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
