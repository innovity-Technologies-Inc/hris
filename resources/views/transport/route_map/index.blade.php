@extends('structure.master')

@section('content')
    <style>
        /* Timeline styles for Route Map modal */
        .route-timeline {
            position: relative;
        }
        .route-timeline::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: #e0e0e0;
        }
        .timeline-item {
            position: relative;
            padding-left: 25px;
        }
        .timeline-dot {
            position: absolute;
            left: 0;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }
        .timeline-dot.bg-success { background-color: #2ecc71 !important; }
        .timeline-dot.bg-warning { background-color: #f1c40f !important; }
        .timeline-dot.bg-danger { background-color: #e74c3c !important; }
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
        <div class="modal-dialog modal-dialog-centered">
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
                        <h5 class="fw-bold text-dark mb-0" id="modalRouteName"></h5>
                    </div>

                    <!-- Stepper Timeline -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3 text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Route Path & Stopovers</h6>
                        
                        <div class="route-timeline position-relative ps-2">
                            <!-- Start Point -->
                            <div class="timeline-item pb-3">
                                <span class="timeline-dot bg-success"></span>
                                <span class="text-muted small d-block">Start Point</span>
                                <strong class="text-dark" id="modalStartPoint"></strong>
                            </div>

                            <!-- Via Points Container -->
                            <div id="modalViaPointsContainer">
                                <!-- Dynamically added via JS -->
                            </div>

                            <!-- End Point -->
                            <div class="timeline-item">
                                <span class="timeline-dot bg-danger"></span>
                                <span class="text-muted small d-block">Destination</span>
                                <strong class="text-dark" id="modalEndPoint"></strong>
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
                $('#modalStartPoint').text(routeMap.start_point);
                $('#modalEndPoint').text(routeMap.end_point);
                
                if (routeMap.route_details) {
                    $('#modalRouteDetails').text(routeMap.route_details);
                    $('#modalDetailsWrapper').show();
                } else {
                    $('#modalDetailsWrapper').hide();
                }

                // Populate via points
                const viaContainer = $('#modalViaPointsContainer');
                viaContainer.empty();

                if (Array.isArray(routeMap.via_points) && routeMap.via_points.length > 0) {
                    routeMap.via_points.forEach(function(point) {
                        viaContainer.append(`
                            <div class="timeline-item pb-3">
                                <span class="timeline-dot bg-warning"></span>
                                <span class="text-muted small d-block">Stopover</span>
                                <strong class="text-dark">${point}</strong>
                            </div>
                        `);
                    });
                }

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
