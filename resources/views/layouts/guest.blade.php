@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
    $primaryColor = '#974063'; // The brand primary color seen in avatars and CSS
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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ isset($generalSettings->favicon) ? asset('storage/' . $generalSettings->favicon) : asset('assets/images/favicon.png') }}">

        <!-- Bootstrap 5 CSS -->
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" id="app-style">
        
        <!-- Icons -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <script>
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
                --primary-color: {{ $primaryColor }};
                --primary-rgb: 151, 64, 99;
                --glass-bg: rgba(255, 255, 255, 0.85);
                --glass-border: rgba(255, 255, 255, 0.5);
                --bg-gradient: radial-gradient(at 0% 0%, rgba(151, 64, 99, 0.15) 0px, transparent 50%),
                               radial-gradient(at 100% 0%, rgba(151, 64, 99, 0.1) 0px, transparent 50%),
                               radial-gradient(at 100% 100%, rgba(151, 64, 99, 0.05) 0px, transparent 50%),
                               radial-gradient(at 0% 100%, rgba(151, 64, 99, 0.1) 0px, transparent 50%);
            }

            [data-bs-theme=dark] {
                --glass-bg: rgba(15, 23, 42, 0.8);
                --glass-border: rgba(255, 255, 255, 0.08);
                --bg-gradient: radial-gradient(at 0% 0%, rgba(151, 64, 99, 0.25) 0px, transparent 50%),
                               radial-gradient(at 100% 100%, rgba(151, 64, 99, 0.1) 0px, transparent 50%);
            }

            body {
                background: var(--bg-gradient), var(--bs-body-bg);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Plus Jakarta Sans', sans-serif;
                overflow: hidden;
                position: relative;
            }

            /* Decorative Blobs */
            .blob {
                position: absolute;
                width: 500px;
                height: 500px;
                background: var(--primary-color);
                filter: blur(80px);
                opacity: 0.15;
                border-radius: 50%;
                z-index: -1;
                animation: float 20s infinite alternate;
            }

            .blob-1 { top: -100px; left: -100px; }
            .blob-2 { bottom: -100px; right: -100px; animation-delay: -5s; }

            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                100% { transform: translate(100px, 50px) scale(1.1); }
            }

            .auth-card {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 28px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2),
                            0 0 40px rgba(var(--primary-rgb), 0.15); /* Subtle Glow */
                width: 100%;
                max-width: 440px;
                padding: 3rem 2.5rem;
                position: relative;
                z-index: 10;
                transition: all 0.4s ease;
            }

            .auth-card:hover {
                box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25),
                            0 0 60px rgba(var(--primary-rgb), 0.25); /* Enhanced Glow on Hover */
                transform: translateY(-5px);
            }

            /* Illustration */
            .bg-illustration {
                position: absolute;
                right: 5%;
                bottom: 5%;
                width: 400px;
                opacity: 0.25;
                z-index: 1;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            @media (max-width: 992px) {
                .bg-illustration {
                    display: none;
                }
            }

            .auth-card::before {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: 28px;
                padding: 2px;
                background: linear-gradient(135deg, var(--glass-border), transparent, var(--glass-border));
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                pointer-events: none;
            }

            .auth-logo-wrapper {
                width: 90px;
                height: 90px;
                margin: 0 auto 1.5rem;
                background: white;
                border-radius: 22px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 20px rgba(var(--primary-rgb), 0.15);
                border: 1px solid rgba(var(--primary-rgb), 0.1);
            }

            [data-bs-theme=dark] .auth-logo-wrapper {
                background: rgba(var(--primary-rgb), 0.2);
                border-color: rgba(var(--primary-rgb), 0.3);
            }

            .auth-logo {
                max-width: 60px;
                max-height: 60px;
                object-fit: contain;
            }

            .brand-name {
                font-weight: 800;
                font-size: 1.75rem;
                color: var(--bs-body-color);
                letter-spacing: -0.5px;
                margin-bottom: 0.25rem;
            }

            .form-label {
                color: var(--bs-secondary-color);
                font-weight: 600;
                margin-left: 4px;
            }

            .input-group {
                background: var(--bs-secondary-bg);
                border-radius: 14px;
                border: 1px solid var(--bs-border-color);
                transition: all 0.3s ease;
                overflow: hidden;
            }

            .input-group:focus-within {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.15);
                background: var(--bs-body-bg);
            }

            .input-group-text {
                border: none;
                padding-left: 1.25rem;
                color: var(--bs-secondary-color);
            }

            .form-control {
                border: none;
                padding: 0.85rem 1rem;
                background: transparent;
                font-weight: 500;
            }

            .form-control:focus {
                box-shadow: none;
                background: transparent;
            }

            .btn-primary {
                background: var(--primary-color);
                border: none;
                border-radius: 14px;
                padding: 0.85rem;
                font-weight: 700;
                font-size: 1rem;
                box-shadow: 0 10px 20px -5px rgba(var(--primary-rgb), 0.4);
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: #813453;
                transform: translateY(-2px);
                box-shadow: 0 15px 25px -5px rgba(var(--primary-rgb), 0.5);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .forgot-link {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 600;
                transition: opacity 0.2s;
            }

            .forgot-link:hover {
                opacity: 0.8;
                color: var(--primary-color);
            }
        </style>
    </head>
    <body>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <img src="{{ asset('assets/images/svg/work-vector.svg') }}" alt="Background Illustration" class="bg-illustration">

        <div class="auth-card text-center">
            <div class="auth-logo-wrapper">
                <a href="/">
                    @if(isset($generalSettings->logo))
                        <img src="{{ asset('storage/' . $generalSettings->logo) }}" alt="Logo" class="auth-logo">
                    @else
                        <i class="fas fa-fingerprint fa-2x" style="color: var(--primary-color)"></i>
                    @endif
                </a>
            </div>
            
            <h1 class="brand-name">{{ $generalSettings->name ?? 'HRMS' }}</h1>
            <p class="text-muted mb-4 px-4">Secure access to your human resource portal</p>

            <div class="text-start">
                {{ $slot }}
            </div>
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
