@extends('structure.master')

@section('content')
    <style>
        /* Horizontal timeline styles for Route Map modal */
        .horizontal-route-container {
            min-height: 120px;
        }
        .route-line {
            position: absolute;
            left: 12.5%;
            right: 12.5%;
            top: 29px;
            height: 4px;
            background: var(--bs-border-color, #e9ecef);
            z-index: 1;
            transition: all 0.3s ease;
        }
        .route-step {
            text-align: center;
            position: relative;
        }
        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            border: 3px solid var(--bs-modal-bg, #fff);
            box-shadow: 0 0 0 2px var(--bs-border-color, #e9ecef);
            font-size: 14px;
            color: white;
            font-weight: bold;
            z-index: 3;
            position: relative;
            transition: all 0.3s ease;
        }
        .step-icon.bg-success {
            box-shadow: 0 0 0 2px #2ecc71;
            background-color: #2ecc71 !important;
        }
        .step-icon.bg-warning {
            box-shadow: 0 0 0 2px #f1c40f;
            background-color: #f1c40f !important;
            color: #333;
        }
        .step-icon.bg-danger {
            box-shadow: 0 0 0 2px #e74c3c;
            background-color: #e74c3c !important;
        }
        .step-label {
            font-size: 0.72rem;
            color: #888;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .step-name {
            font-size: 0.82rem;
            color: var(--bs-body-color, #212529);
            font-weight: 700;
            display: block;
            padding: 0 5px;
            word-break: break-word;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @if(auth()->user()->can('employee-transport.create'))
                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('transport.route_maps.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                    @endif
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search route maps by keyword" aria-label="Keyword Search" value="{{ request('keyword') }}">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive" id="search-result">
                        @include('transport.route_map.search_results')
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    <!-- Route Map Details Modal -->
    <div class="modal fade" id="routeMapModal" tabindex="-1" aria-labelledby="routeMapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title text-white" id="routeMapModalLabel">
                        <i class="mdi mdi-map-search-outline me-2"></i> Route Map Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Route Name -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-1 text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Route Name</h6>
                        <h5 class="fw-bold text-body mb-0" id="modalRouteName"></h5>
                    </div>

                    <!-- Horizontal Stepper Timeline -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-4 text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Route Map</h6>
                        
                        <div class="horizontal-route-container position-relative py-3">
                            <!-- Connecting Line -->
                            <div class="route-line"></div>
                            
                            <!-- Steps Container -->
                            <div class="d-flex justify-content-between align-items-start position-relative" id="modalHorizontalSteps" style="z-index: 2;">
                                <!-- Populated via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Route Details -->
                    <div class="bg-light p-3 rounded-3" id="modalDetailsWrapper" style="display: none;">
                        <h6 class="text-muted mb-1 text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Details & Directions</h6>
                        <p class="text-secondary mb-0 small" id="modalRouteDetails"></p>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData(url = "{{ route('transport.route_maps.index') }}") {
                const queryString = $('#filterForm').serialize();
                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">Loading Data...</div>');
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Global modal view function
            window.showRouteMapModal = function(routeMap) {
                $('#modalRouteName').text(routeMap.route_name);
                
                if (routeMap.route_details) {
                    $('#modalRouteDetails').text(routeMap.route_details);
                    $('#modalDetailsWrapper').show();
                } else {
                    $('#modalDetailsWrapper').hide();
                }

                // Build horizontal steps
                const stepsContainer = $('#modalHorizontalSteps');
                stepsContainer.empty();

                const steps = [];

                // 1. Start Point
                steps.push({
                    label: 'Start',
                    name: routeMap.start_point,
                    class: 'bg-success',
                    icon: '<i class="mdi mdi-play" style="font-size: 12px; margin-left: 2px;"></i>'
                });

                // 2. Via Points
                const vias = Array.isArray(routeMap.via_points) ? routeMap.via_points : [];
                if (vias.length === 1) {
                    steps.push({
                        label: 'Stopover',
                        name: vias[0],
                        class: 'bg-warning',
                        icon: '1'
                    });
                } else if (vias.length === 2) {
                    steps.push({
                        label: 'Stopover 1',
                        name: vias[0],
                        class: 'bg-warning',
                        icon: '1'
                    });
                    steps.push({
                        label: 'Stopover 2',
                        name: vias[1],
                        class: 'bg-warning',
                        icon: '2'
                    });
                } else if (vias.length > 2) {
                    steps.push({
                        label: 'Stopover 1',
                        name: vias[0],
                        class: 'bg-warning',
                        icon: '1'
                    });
                    
                    // Show remaining stopovers summary
                    const remaining = vias.slice(1);
                    const tooltipText = remaining.join(', ');
                    steps.push({
                        label: `Stopovers (+${remaining.length})`,
                        name: `<span class="text-primary cursor-pointer" title="${tooltipText}" data-bs-toggle="tooltip" data-bs-placement="top">${remaining[0]} & others</span>`,
                        class: 'bg-warning',
                        icon: '+'
                    });
                }

                // 3. End Point
                steps.push({
                    label: 'Destination',
                    name: routeMap.end_point,
                    class: 'bg-danger',
                    icon: '<i class="mdi mdi-flag-variant" style="font-size: 12px;"></i>'
                });

                // Adjust connecting route line width and offsets based on step counts
                const stepCount = steps.length;
                const routeLine = $('.route-line');
                if (stepCount === 2) {
                    routeLine.css({ left: '25%', right: '25%', top: '29px' });
                } else if (stepCount === 3) {
                    routeLine.css({ left: '16.6%', right: '16.6%', top: '29px' });
                } else {
                    routeLine.css({ left: '12.5%', right: '12.5%', top: '29px' });
                }

                // Render each step horizontally
                steps.forEach(function(step) {
                    const widthPercent = 100 / stepCount;
                    stepsContainer.append(`
                        <div class="route-step" style="width: ${widthPercent}%;">
                            <div class="step-icon ${step.class}">
                                ${step.icon}
                            </div>
                            <span class="step-label">${step.label}</span>
                            <span class="step-name">${step.name}</span>
                        </div>
                    `);
                });

                // Initialize Bootstrap Tooltips if any
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                const myModal = new bootstrap.Modal(document.getElementById('routeMapModal'));
                myModal.show();
            };

            // Global delete function
            window.deleteRouteMap = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/transport/route-maps/${id}`)
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire('Deleted!', response.data.message, 'success');
                                    fetchData();
                                } else {
                                    Swal.fire('Failed!', response.data.message, 'error');
                                }
                            })
                            .catch(error => {
                                const msg = error.response && error.response.data && error.response.data.message
                                    ? error.response.data.message
                                    : 'Something went wrong';
                                Swal.fire('Error', msg, 'error');
                            });
                    }
                });
            };
        });
    </script>
@endsection
