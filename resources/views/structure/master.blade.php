@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d6efd">
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

    <!-- Custom Styles -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <style>
        /* Global Select2 height fix to match Bootstrap 5 form-control exactly */
        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px !important; /* Standard BS5 height */
            display: flex;
            align-items: center;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            padding-left: 0.75rem !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>

    @stack('styles')

</head>



<!-- body start -->

<body data-menu-color="light" data-sidebar="default">

{{--loading screen--}}
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
    <div class="spinner"></div>
    <p style="margin-top: 10px; font-weight: bold;">Loading, please wait...</p>
</div>

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
                allowClear: true,
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

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Set default CSRF token for Axios
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    </script>


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
                    imageCompressOutputMimeType: 'image/webp', // can be 'image/png', 'image/jpeg', etc.
                    imageCompressOutputQuality: 0.7, // 0–1
                    imageCompressConvertSize: 0
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



<script>
    const loader = document.getElementById('loading-overlay');

    // Function to hide loader
    const hideLoader = () => {
        if (loader) loader.style.display = 'none';
    };

    // Function to show loader
    const showLoader = () => {
        if (loader) loader.style.display = 'flex';
    };

    // 1. Initial Load
    window.addEventListener('load', hideLoader);

    // 2. The "BfCache" Fix (Back/Forward Button)
    window.addEventListener('pageshow', (event) => {
        hideLoader();
    });

    // 3. Show on Unload (Links/Redirects)
    window.addEventListener('beforeunload', () => {
        showLoader();
    });

    // 4. THE FALLBACK: In case the user cancels the navigation
    // or the back button is hit, this "heartbeat" ensures the loader
    // doesn't stay forever.
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === 'visible') {
            hideLoader();
        }
    });

    // 5. Form Submissions
    document.addEventListener('submit', (e) => {
        // Only show loader if the form is actually submitting (not blocked by validation)
        if (!e.defaultPrevented) {
            showLoader();
        }
    });

    // 6. Handle the "Stop" button in the browser
    window.addEventListener('pagehide', hideLoader);

    $(document).on('click', '.password-toggle', function() {
        // Find the input within the same input-group or parent container
        let input = $(this).siblings('input');
        if (input.length === 0) {
            input = $(this).closest('.input-group').find('input');
        }
        
        const icon = $(this);
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>

<script>
    window.profileFieldConfigs = {!! json_encode(\App\Models\Setting\ProfileFieldConfig::all()->map(fn($c) => [
        'section' => $c->section,
        'field_name' => $c->field_name,
        'is_required' => (bool)$c->is_required
    ])) !!};

    let profileFieldConfigObserver = null;

    function applyFieldRequirements() {
        if (!window.profileFieldConfigs) return;

        // Temporarily disconnect observer to avoid infinite recursion loops
        if (profileFieldConfigObserver) {
            profileFieldConfigObserver.disconnect();
        }
        
        window.profileFieldConfigs.forEach(config => {
            const field = config.field_name;
            const isRequired = config.is_required;
            
            const querySelector = `[name="${field}"], [name="${field}[]"], [name^="${field}["]`;
            const inputs = document.querySelectorAll(querySelector);
            
            inputs.forEach(input => {
                if (input.type === 'hidden') return;
                
                // Only update attribute if value has changed to optimize performance
                const currentlyRequired = input.required || input.hasAttribute('required');
                if (isRequired !== currentlyRequired) {
                    if (isRequired) {
                        input.required = true;
                        input.setAttribute('required', 'required');
                    } else {
                        input.required = false;
                        input.removeAttribute('required');
                    }
                }
                
                let label = null;
                if (input.id) {
                    label = document.querySelector(`label[for="${input.id}"]`);
                }
                if (!label) {
                    label = input.closest('.mb-3, .form-group, .col-lg-4, .col-md-6, .col-lg-3, .col-12')?.querySelector('label');
                }
                
                if (label) {
                    const asterisk = label.querySelector('.text-danger');
                    if (isRequired) {
                        if (!asterisk) {
                            const span = document.createElement('span');
                            span.className = 'text-danger';
                            span.textContent = ' *';
                            label.appendChild(span);
                        }
                    } else {
                        if (asterisk) {
                            asterisk.remove();
                        }
                    }
                }
            });
        });

        // Reconnect observer
        if (profileFieldConfigObserver) {
            profileFieldConfigObserver.observe(document.body, { childList: true, subtree: true });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyFieldRequirements();
        
        profileFieldConfigObserver = new MutationObserver(function() {
            applyFieldRequirements();
        });
        profileFieldConfigObserver.observe(document.body, { childList: true, subtree: true });
    });
</script>

</body>

</html>

