<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>HRMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRMS Solution">
    <meta name="author" content="Daiyan">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

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
    </style>

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

</body>

</html>
