@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
    $primaryColor = '#974063';
    $primaryRGB = '151, 64, 99';
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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ isset($generalSettings->favicon) ? asset('storage/' . $generalSettings->favicon) : asset('assets/images/favicon.png') }}">

        <!-- Bootstrap 5 CSS -->
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" id="app-style">
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
                --primary-rgb: {{ $primaryRGB }};
                --secondary-color: #6366f1;
                --bg-canvas: #e2e8f0; /* Darker Slate for better card pop in light mode */
            }

            [data-bs-theme=dark] {
                --bg-canvas: #000000; /* Pure black for maximum contrast in dark mode */
            }

            body {
                background-color: var(--bg-canvas);
                font-family: 'Plus Jakarta Sans', sans-serif;
                min-height: 100vh;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow-x: hidden;
            }

            /* --- Mesh Background --- */
            .mesh-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -2;
                background-color: var(--bg-canvas);
                background-image: 
                    radial-gradient(at 0% 0%, rgba(var(--primary-rgb), 0.3) 0px, transparent 65%),
                    radial-gradient(at 100% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 60%),
                    radial-gradient(at 100% 100%, rgba(var(--primary-rgb), 0.25) 0px, transparent 65%),
                    radial-gradient(at 0% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 60%);
            }

            /* --- Noise Texture --- */
            .noise-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                opacity: 0.04;
                pointer-events: none;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3True%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            }

            /* --- Split Layout Container --- */
            .auth-wrapper {
                display: flex;
                width: 100%;
                max-width: 1100px;
                min-height: 650px;
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                border-radius: 40px;
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
                overflow: hidden;
                margin: 20px;
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            }

            [data-bs-theme=dark] .auth-wrapper {
                background: rgba(30, 41, 59, 0.6);
                border-color: rgba(255, 255, 255, 0.05);
                box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4);
            }

            /* --- Left Side: Branding & Illustration --- */
            .auth-side-brand {
                flex: 1;
                background: linear-gradient(135deg, var(--primary-color), #7a3050);
                padding: 4rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                position: relative;
                overflow: hidden;
            }

            @media (max-width: 991px) {
                .auth-side-brand { display: none; }
                .auth-wrapper { max-width: 500px; }
            }

            .brand-illustration {
                width: 100%;
                max-width: 320px;
                z-index: 2;
                filter: drop-shadow(0 20px 30px rgba(0,0,0,0.2));
                animation: float 6s infinite ease-in-out;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }

            .brand-circle {
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                z-index: 1;
            }

            .circle-1 { width: 300px; height: 300px; top: -100px; left: -100px; }
            .circle-2 { width: 200px; height: 200px; bottom: -50px; right: -50px; }

            /* --- Right Side: Form --- */
            .auth-side-form {
                flex: 1;
                padding: 4rem 3.5rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .auth-header {
                margin-bottom: 2.5rem;
            }

            .auth-logo-box {
                width: 64px;
                height: 64px;
                background: white;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 20px rgba(var(--primary-rgb), 0.1);
                margin-bottom: 1.5rem;
            }

            [data-bs-theme=dark] .auth-logo-box {
                background: #1e293b;
            }

            .auth-title {
                font-family: 'Outfit', sans-serif;
                font-weight: 800;
                font-size: 2.25rem;
                letter-spacing: -1px;
                color: var(--bs-body-color);
                margin-bottom: 0.5rem;
            }

            /* --- Form Styling --- */
            .form-label {
                font-weight: 700;
                font-size: 0.85rem;
                color: var(--bs-secondary-color);
                margin-bottom: 0.6rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .input-group-custom {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .input-group-custom i {
                position: absolute;
                left: 1.25rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--bs-secondary-color);
                transition: all 0.3s;
                z-index: 5;
            }

            .form-control-custom {
                width: 100%;
                padding: 1rem 1.25rem 1rem 3.25rem;
                background: var(--bs-secondary-bg);
                border: 2px solid transparent;
                border-radius: 16px;
                font-weight: 600;
                color: var(--bs-body-color);
                transition: all 0.3s;
            }

            .form-control-custom:focus {
                background: var(--bs-body-bg);
                border-color: var(--primary-color);
                box-shadow: 0 0 0 5px rgba(var(--primary-rgb), 0.1);
                outline: none;
            }

            .form-control-custom:focus + i {
                color: var(--primary-color);
            }

            /* --- Premium Button --- */
            .btn-premium {
                background: linear-gradient(135deg, var(--primary-color), #7a3050);
                color: white;
                border: none;
                border-radius: 16px;
                padding: 1.1rem;
                font-weight: 800;
                font-size: 1.1rem;
                letter-spacing: 0.5px;
                width: 100%;
                position: relative;
                overflow: hidden;
                box-shadow: 0 15px 35px -10px rgba(var(--primary-rgb), 0.5);
                transition: all 0.3s;
            }

            .btn-premium:hover {
                transform: translateY(-3px);
                box-shadow: 0 20px 40px -10px rgba(var(--primary-rgb), 0.6);
                color: white;
            }

            .btn-premium::after {
                content: "";
                position: absolute;
                top: -50%;
                left: -60%;
                width: 20%;
                height: 200%;
                background: rgba(255, 255, 255, 0.2);
                transform: rotate(30deg);
                transition: all 0.6s;
            }

            .btn-premium:hover::after {
                left: 130%;
            }

            /* --- Custom Checkbox --- */
            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .link-premium {
                color: var(--primary-color);
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s;
            }

            .link-premium:hover {
                opacity: 0.7;
                color: var(--primary-color);
            }

        </style>
    </head>
    <body>
        <div class="mesh-bg"></div>
        <div class="noise-overlay"></div>

        <div class="auth-wrapper">
            <!-- Left Side -->
            <div class="auth-side-brand">
                <div class="brand-circle circle-1"></div>
                <div class="brand-circle circle-2"></div>
                
                <img src="{{ asset('assets/images/svg/work-vector.svg') }}" alt="Illustration" class="brand-illustration">
                
                <div class="text-center mt-5 z-2">
                    <h2 class="fw-bold h1 Outfit">Streamline Your HR</h2>
                    <p class="opacity-75 fs-5 px-5">Experience the next generation of human resource management with our powerful cloud portal.</p>
                </div>
            </div>

            <!-- Right Side -->
            <div class="auth-side-form">
                <div class="auth-header text-center text-lg-start">
                    <div class="auth-logo-box mx-auto mx-lg-0">
                        @if(isset($generalSettings->favicon))
                            <img src="{{ asset('storage/' . $generalSettings->favicon) }}" alt="Favicon" style="max-width: 45px; max-height: 45px; object-fit: contain;">
                        @else
                            <i class="fas fa-fingerprint fa-2x" style="color: var(--primary-color)"></i>
                        @endif
                    </div>
                    <h1 class="auth-title">{{ $generalSettings->name ?? 'HRMS' }}</h1>
                    <p class="text-muted fw-semibold">Sign in to continue to your dashboard</p>
                </div>

                {{ $slot }}

                <div class="mt-auto pt-4 text-center">
                    <p class="small text-muted mb-0">&copy; {{ date('Y') }} {{ $generalSettings->name ?? 'HRMS' }}. All rights reserved.</p>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
