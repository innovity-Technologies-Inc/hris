<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Activated</title>
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
        .divider {
            border-top: 1px solid #eeeeee;
            margin: 30px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            background-color: #28a745;
            color: #ffffff;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
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
                    <h1>Congratulations, {{ $employee->full_name }}!</h1>
                    <p>Your employee profile has been reviewed and successfully <strong>Activated</strong>.</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <span class="status-badge">Profile Active</span>
                    </div>

                    <p>You can now access all the features of the <strong>{{ $appName }}</strong> HRMS system. Your account is fully operational.</p>
                    
                    <div style="text-align: center;">
                        <a href="{{ url('/') }}" class="button">Go to Dashboard</a>
                    </div>

                    <p style="margin-top: 30px;">If you have any questions, please contact the HR department.</p>
                    
                    <div class="divider"></div>
                    
                    <p style="font-size: 14px; color: #666;">
                        Thank you for being part of our team!
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

