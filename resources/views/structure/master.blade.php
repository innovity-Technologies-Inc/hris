@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>{{ isset($generalSettings->name) ? $generalSettings->name : 'HRMS' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRMS Solution">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Daiyan">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script>
        // Set theme ASAP to avoid flash/flicker before CSS loads
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
            } catch (e) {
                // default stays 'light'
            }
        })();
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- App favicon -->
    <link rel="shortcut icon"
        href="{{ isset($generalSettings->favicon) ? asset('storage/' . $generalSettings->favicon) : asset('assets/images/favicon.png') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" id="app-style">

    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('assets/js/head.js') }}"></script>

    {{--    Toastr --}}

    {{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet"> --}}
    <link href="{{ asset('assets/libs/toastr/toastr.css') }}" rel="stylesheet" type="text/css">


    {{--    Filepond Css --}}

    {{-- <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    --}}
    <link href="{{ asset('assets/libs/filepond/filepond.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/filepond/filepond-plugin-image-preview.css') }}" rel="stylesheet" type="text/css">


    {{--    Select 2 Css --}}

    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css">


    {{--    Summernote Css --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    {{--    <link href="{{asset('assets/libs/summernote/summernote-lite.min.css')}}" rel="stylesheet" type="text/css"> --}}


    <style>
        :root {
            /* Default (fallback) values */
            --sidebar-bg-start: #0f172a;
            --sidebar-bg-end: #111827;
            --sidebar-text: #e5e7eb;
            --sidebar-muted: #94a3b8;
            --sidebar-accent: #108dff;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.06);
            --sidebar-active-bg: rgba(16, 141, 255, 0.15);
            --sidebar-active-color: #ffffff;
            --sidebar-divider: rgba(255, 255, 255, 0.06);
        }

        /* Sidebar color modes follow app theme (navbar toggle) */
        html[data-bs-theme='light'] .app-sidebar-menu {
            --sidebar-bg-start: #ffffff;
            --sidebar-bg-end: #f8fafc;
            --sidebar-text: #0f172a;
            --sidebar-muted: #64748b;
            --sidebar-accent: #0d6efd;
            --sidebar-hover-bg: rgba(2, 6, 23, 0.06);
            --sidebar-active-bg: rgba(13, 110, 253, 0.15);
            --sidebar-active-color: #0f172a;
            --sidebar-divider: rgba(2, 6, 23, 0.08);
        }

        html[data-bs-theme='dark'] .app-sidebar-menu {
            --sidebar-bg-start: #0f172a;
            --sidebar-bg-end: #111827;
            --sidebar-text: #e5e7eb;
            --sidebar-muted: #94a3b8;
            --sidebar-accent: #108dff;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.06);
            --sidebar-active-bg: rgba(16, 141, 255, 0.15);
            --sidebar-active-color: #ffffff;
            --sidebar-divider: rgba(255, 255, 255, 0.06);
        }

        /* Sidebar polish */
        .app-sidebar-menu {
            background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            padding-top: 0 !important;
            box-shadow: 4px 0 16px rgba(0, 0, 0, 0.12);
        }

        .app-sidebar-menu .logo-box {
            padding: 8px 16px 10px;
            border-bottom: 1px solid var(--sidebar-divider);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            text-align: center;
        }

        /* Remove any accidental top padding from Simplebar wrappers */
        .app-sidebar-menu .simplebar-content,
        .app-sidebar-menu .simplebar-content-wrapper {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* Remove default margins that can create extra gap */
        .app-sidebar-menu .logo {
            margin: 0;
            line-height: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .app-sidebar-menu .logo-sm,
        .app-sidebar-menu .logo-lg {
            display: inline-flex;
            align-items: center;
            line-height: 0;
        }

        .app-sidebar-menu .logo img {
            display: block;
        }

        .app-sidebar-menu #side-menu {
            padding: 8px 10px 16px;
        }

        .app-sidebar-menu #side-menu .menu-title {
            color: var(--sidebar-muted);
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin: 12px 12px 6px;
        }

        .app-sidebar-menu #side-menu>li>a {
            color: var(--sidebar-text);
            padding: 10px 12px;
            margin: 4px 8px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            position: relative;
        }

        .app-sidebar-menu #side-menu>li>a:hover {
            background: var(--sidebar-hover-bg);
            transform: translateX(2px);
        }

        .app-sidebar-menu #side-menu>li>a.menuitem-active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-color);
            box-shadow: inset 0 0 0 1px rgba(16, 141, 255, 0.25);
        }

        .app-sidebar-menu #side-menu>li>a.menuitem-active::before {
            content: "";
            position: absolute;
            left: -2px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 3px;
            background: var(--sidebar-accent);
        }

        .app-sidebar-menu #side-menu>li>a svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            opacity: 0.9;
        }

        .app-sidebar-menu .menu-arrow {
            margin-left: auto;
            transition: transform 0.2s ease, opacity 0.2s ease;
            opacity: 0.7;
        }

        .app-sidebar-menu a[aria-expanded="true"] .menu-arrow {
            transform: rotate(90deg);
            opacity: 1;
        }

        /* Second level */
        .app-sidebar-menu .nav-second-level {
            padding-left: 10px;
            margin: 4px 0 8px;
        }

        .app-sidebar-menu .nav-second-level>li>a {
            display: block;
            padding: 8px 12px 8px 34px;
            margin: 2px 8px;
            border-radius: 8px;
            color: var(--sidebar-text);
            opacity: 0.9;
            transition: all 0.2s ease;
            position: relative;
        }

        .app-sidebar-menu .nav-second-level>li>a::before {
            content: "";
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--sidebar-muted);
            opacity: 0.6;
        }

        .app-sidebar-menu .nav-second-level>li>a:hover {
            background: var(--sidebar-hover-bg);
            color: var(--sidebar-text);
        }

        .app-sidebar-menu .nav-second-level>li>a.menuitem-active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-color);
        }

        .app-sidebar-menu .nav-second-level>li>a.menuitem-active::before {
            background: var(--sidebar-accent);
            opacity: 1;
        }

        .filepond--credits {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            height: 0;
            width: 0;
        }

        .note-editor.note-airframe .note-editing-area .note-editable,
        .note-editor.note-frame .note-editing-area .note-editable {
            background: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }

        .note-toolbar {
            background: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
        }

        .note-editor.note-frame {
            border-color: var(--bs-border-color);
        }

        .note-btn {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-border-color);
        }

        .note-dropdown-menu {
            background-color: var(--bs-secondary-bg);
            border-color: var(--bs-border-color);
        }

        .note-dropdown-item {
            color: var(--bs-body-color);
        }

        .note-dropdown-item:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
            /* Match Bootstrap form-control height */
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--bs-border-color);
            /* Match Bootstrap border */
            border-radius: 0.375rem;
            /* Match Bootstrap border-radius */
            font-size: 1rem;
            line-height: 1.5;
            background: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }


        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px);
            /* Align the arrow with the selection box */
            top: 50%;
            transform: translateY(-50%);
            right: 0.75rem;
        }


        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            /* Center the selected text vertically */
            color: var(--bs-body-color);
        }


        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 0.375rem;
            /* Match Bootstrap dropdown border-radius */
            border: 1px solid var(--bs-border-color);
            /* Match Bootstrap border */
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }

        .select2-container--bootstrap-5 .select2-results__option {
            color: var(--bs-body-color);
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #108dff;
            /* Bootstrap primary color for highlighting */
            color: white;
        }

        .select2-container--bootstrap-5 .select2-search__field {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-border-color);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--bs-border-color);
            border-radius: 0.375rem;
            font-size: 1rem;
            line-height: 1.5;
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }

        /* Content polish */
        .content-page {
            background: radial-gradient(1200px 400px at 20% -5%, rgba(16, 141, 255, 0.06), transparent 40%),
                radial-gradient(900px 300px at 110% 10%, rgba(99, 102, 241, 0.05), transparent 35%),
                var(--bs-body-bg);
        }

        .content-page .content {
            padding-top: 8px;
        }

        .content-page .content .container-fluid {
            padding-top: 0;
            margin-top: 0;
        }

        /* Cards */
        .card,
        .card-body,
        .card-header,
        .card-footer {
            /* background-color: var(--bs-secondary-bg); */
            border-color: var(--bs-border-color);
        }

        .card {
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            border-bottom: 1px solid var(--bs-border-color);
            font-weight: 600;
        }

        /* Breadcrumb */
        .breadcrumb {
            --bs-breadcrumb-divider-color: var(--sidebar-muted);
            --bs-breadcrumb-item-active-color: var(--bs-body-color);
            color: var(--sidebar-muted);
            margin-bottom: 0.75rem;
        }

        .breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            opacity: 0.6;
        }

        /* Forms */
        .form-control,
        .form-select,
        .select2-container--bootstrap-5 .select2-selection {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-border-color);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus,
        .form-select:focus,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            box-shadow: 0 0 0 .25rem rgba(16, 141, 255, 0.25);
            border-color: rgba(16, 141, 255, 0.6);
        }

        /* Buttons */
        .btn {
            transition: transform 0.05s ease, box-shadow 0.2s ease;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn-primary,
        .btn-outline-primary:hover {
            box-shadow: 0 6px 16px rgba(16, 141, 255, 0.35);
        }

        /* Tables */
        .table> :not(caption)>*>* {
            background-color: transparent;
            color: var(--bs-body-color);
            box-shadow: inset 0 -1px 0 var(--bs-border-color);
        }

        .table thead th {
            background: var(--bs-tertiary-bg);
            border-bottom: 1px solid var(--bs-border-color);
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }

        /* Simplebar scrollbar */
        .simplebar-scrollbar::before {
            background: linear-gradient(180deg, rgba(16, 141, 255, 0.7), rgba(99, 102, 241, 0.7));
            border-radius: 6px;
        }

        .simplebar-track.simplebar-vertical {
            width: 10px;
        }

        .simplebar-track.simplebar-vertical .simplebar-scrollbar::before {
            left: 2px;
            right: 2px;
        }

        /* Swap logo images based on theme */
        .app-sidebar-menu .logo-lg img.logo-img-light,
        .app-sidebar-menu .logo-lg img.logo-img-dark {
            display: none !important;
        }

        html[data-bs-theme='dark'] .app-sidebar-menu .logo-lg img.logo-img-light {
            display: inline-block !important;
        }

        html[data-bs-theme='dark'] .app-sidebar-menu .logo-lg img.logo-img-dark {
            display: none !important;
        }

        html[data-bs-theme='light'] .app-sidebar-menu .logo-lg img.logo-img-light {
            display: none !important;
        }

        html[data-bs-theme='light'] .app-sidebar-menu .logo-lg img.logo-img-dark {
            display: inline-block !important;
        }
    </style>

    @stack('styles')

</head>

<!-- body start -->

<body data-menu-color="light" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        @include('structure.partials.navbar')
        @include('structure.partials.sidebar')



        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    @include('structure.partials.breadcrumb')

                    @yield('content')


                </div> <!-- container-fluid -->
            </div> <!-- content -->

            @include('structure.partials.footer')

        </div>


    </div>

    <!-- Vendor -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    {{-- Select 2 Js --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            if (window.feather) {
                window.feather.replace({
                    width: 18,
                    height: 18
                });
            }
            // Logo visibility is controlled via CSS by html[data-bs-theme]

            // Sidebar color mode now follows global theme (navbar toggle)
            // basic select2
            $('.select2_list').select2({
                width: '100%',
                theme: 'bootstrap-5',
            });


            // can add tags, select the typed word and press enter to add it to the list
            $('.list').select2({
                width: '100%',
                tags: true, // Allow new entries as tags
                tokenSeparators: [','],
                placeholder: "Choose One",
                theme: 'bootstrap-5',
            });
        });
    </script>

    <!-- Apexcharts JS -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Widgets Init Js -->
    <script src="{{ asset('assets/js/pages/crm-dashboard.init.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- Toastr --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> --}}
    <script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.options.timeOut = 3000;
                    toastr.info("{{ Session::get('message') }}");
                    break;

                case 'success':
                    toastr.options.timeOut = 3000;
                    toastr.success("{{ Session::get('message') }}");
                    break;

                case 'warning':
                    toastr.options.timeOut = 3000;
                    toastr.warning("{{ Session::get('message') }}");
                    break;

                case 'error':
                    toastr.options.timeOut = 3000;
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    </script>


    {{-- Sweet Alert --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('assets/libs/sweetalert/sweetalert2@11.js') }}"></script>


    <script>
        $('.confirmDelete').click(function(event) {
            event.preventDefault();
            const form = $(this).closest("form");


            Swal.fire({
                title: 'Are you sure you want to delete?',
                text: 'You won\'t be able to revert!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();


                } else if (result.isDismissed) {
                    console.log('Deletion canceled');
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>

    <script>
        $('.confirmApprove').click(function(event) {
            event.preventDefault();
            const form = $(this).closest("form");


            Swal.fire({
                title: 'Are you sure you want to approve the application?',
                text: 'You won\'t be able to revert!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();


                } else if (result.isDismissed) {
                    console.log('Deletion canceled');
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>


    <script>
        $('.confirmReject').click(function(event) {
            event.preventDefault();
            const form = $(this).closest("form");


            Swal.fire({
                title: 'Are you sure you want to reject the application?',
                text: 'You won\'t be able to revert!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();


                } else if (result.isDismissed) {
                    console.log('Deletion canceled');
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>


    <script>
        $('.removeBtn').click(function(event) {
            event.preventDefault();
            const form = $(this).closest("form");


            Swal.fire({
                title: 'Are you sure you want to remove?',
                text: 'This Plans will be unassigned from this employee!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();


                } else if (result.isDismissed) {
                    console.log('Deletion canceled');
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>


    {{-- Select 2 Js --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            // basic select2
            $('.select2_list').select2({
                width: '100%',
                theme: 'bootstrap-5',
            });

            // can add tags, select the typed word and press enter to add it to the list
            $('.list').select2({
                width: '100%',
                tags: true, // Allow new entries as tags
                tokenSeparators: [','],
                placeholder: "Choose One",
                theme: 'bootstrap-5',
            });
        });
    </script>

    {{-- Filepond Js --}}
    {{-- <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script> --}}
    <script src="{{ asset('assets/libs/filepond/filepond.min.js') }}"></script>


    {{-- <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script> --}}
    <script src="{{ asset('assets/libs/filepond/filepond-plugin-image-preview.min.js') }}"></script>

    <!-- Image Resize -->
    {{-- <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script> --}}
    <script src="{{ asset('assets/libs/filepond/filepond-plugin-image-resize.min.js') }}"></script>

    <!-- Image Transform -->
    {{-- <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script> --}}
    <script src="{{ asset('assets/libs/filepond/filepond-plugin-image-transform.min.js') }}"></script>



    <script>
        // Register needed FilePond plugins globally
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageResize,
            FilePondPluginImageTransform,
        );


        // compression version
        document.querySelectorAll('input.filepond').forEach(input => {
            if (!input.filePondInstance) {
                input.filePondInstance = FilePond.create(input, {
                    storeAsFile: true,
                    instantUpload: false,
                    labelIdle: 'Drag & Drop or <span class="filepond--label-action">Browse</span>',

                    // ✅ Compression settings
                    imageCompress: true,
                    imageCompressQuality: 0.8, // 0–1 (1 = no compression)
                    imageCompressMaxWidth: 1920,
                    imageCompressMaxHeight: 1080,
                    imageCompressMode: 'automatic', // can be 'manual' or 'automatic'
                    imageResizeMode: 'contain', // keep aspect ratio


                    // 👇 Force image format
                    /*imageCompressOutputMimeType: 'image/webp', // can be 'image/png', 'image/jpeg', etc.
                    imageCompressOutputQuality: 0.7, // 0–1
                    imageCompressConvertSize: 0 */
                });
            }
        });


        // Init for original version (NO compression)
        document.querySelectorAll('input.filepond_org').forEach(input => {
            if (!input.filePondInstance) {
                input.filePondInstance = FilePond.create(input, {
                    storeAsFile: true,
                    instantUpload: false,
                    labelIdle: 'Drag & Drop or <span class="filepond--label-action">Browse</span>',
                    allowImageTransform: false // ❌ disables compression for this instance
                });
            }
        });
    </script>

    {{-- Summernote JS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}
    <script src="{{ asset('assets/libs/summernote/summernote-lite.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#editor1').summernote({
                toolbar: [
                    // ['style', ['style']], // optional
                    ['font', ['fontsize', 'bold', 'italic', 'underline', 'clear',
                        'color'
                    ]], // remove 'fontname',
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview']]
                ],
                // Optional: Set your desired height
                height: 200,
            });

            $('#editor2').summernote({
                toolbar: [
                    // ['style', ['style']], // optional
                    ['font', ['fontsize', 'bold', 'italic', 'underline', 'clear',
                        'color'
                    ]], // remove 'fontname',
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview']]
                ],
                // Optional: Set your desired height
                height: 200,
            });

            $('#editor3').summernote({
                toolbar: [
                    // ['style', ['style']], // optional
                    ['font', ['fontsize', 'bold', 'italic', 'underline', 'clear',
                        'color'
                    ]], // remove 'fontname',
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview']]
                ],
                // Optional: Set your desired height
                height: 200,
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
