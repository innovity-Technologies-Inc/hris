@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $generalSettings->name ?? config('app.name', 'HRMS') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ isset($generalSettings->favicon) ? asset('storage/' . $generalSettings->favicon) : asset('assets/images/favicon.png') }}">

        <!-- Bootstrap 5 CSS -->
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" id="app-style">
        
        <!-- Icons -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <script>
            // Set theme ASAP to avoid flash/flicker
            (function() {
                try {
                    var saved = localStorage.getItem("__CONFIG__");
                    var theme = 'light';
                    if (saved) {
                        var cfg = JSON.parse(saved);
                        if (cfg && (cfg.theme === 'dark' || cfg.theme === 'light')) {
                            theme = cfg.theme;
                        }
                    }
                    document.documentElement.setAttribute('data-bs-theme', theme);
                } catch (e) {}
            })();
        </script>

        <style>
            :root {
                --primary-color: #108dff;
                --glass-bg: rgba(255, 255, 255, 0.7);
                --glass-border: rgba(255, 255, 255, 0.4);
            }

            [data-bs-theme=dark] {
                --glass-bg: rgba(30, 41, 59, 0.7);
                --glass-border: rgba(255, 255, 255, 0.1);
            }

            body {
                background: radial-gradient(circle at 0% 0%, rgba(16, 141, 255, 0.1) 0%, transparent 50%),
                            radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                            var(--bs-body-bg);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Inter', sans-serif;
            }

            .auth-card {
                background: var(--glass-bg);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid var(--glass-border);
                border-radius: 20px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
                width: 100%;
                max-width: 420px;
                padding: 2.5rem;
                transition: transform 0.3s ease;
            }

            .auth-logo {
                width: 80px;
                height: 80px;
                object-fit: contain;
                margin-bottom: 1.5rem;
            }

            .form-control {
                border-radius: 12px;
                padding: 0.75rem 1rem;
                background: var(--bs-secondary-bg);
                border: 1px solid var(--bs-border-color);
            }

            .form-control:focus {
                box-shadow: 0 0 0 4px rgba(16, 141, 255, 0.15);
                border-color: var(--primary-color);
            }

            .btn-primary {
                border-radius: 12px;
                padding: 0.75rem;
                font-weight: 600;
                background: var(--primary-color);
                border: none;
                box-shadow: 0 10px 15px -3px rgba(16, 141, 255, 0.3);
            }

            .btn-primary:hover {
                background: #007ae6;
                transform: translateY(-1px);
                box-shadow: 0 10px 15px -3px rgba(16, 141, 255, 0.4);
            }

            .brand-name {
                font-weight: 800;
                font-size: 1.5rem;
                color: var(--bs-body-color);
                margin-bottom: 0.5rem;
            }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <div class="text-center">
                <a href="/">
                    @if(isset($generalSettings->logo))
                        <img src="{{ asset('storage/' . $generalSettings->logo) }}" alt="Logo" class="auth-logo">
                    @else
                        <div class="auth-logo d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    @endif
                </a>
                <h1 class="brand-name">{{ $generalSettings->name ?? 'HRMS' }}</h1>
                <p class="text-muted small mb-4">Welcome back! Please login to your account.</p>
            </div>

            {{ $slot }}
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
