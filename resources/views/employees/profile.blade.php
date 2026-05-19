@extends('structure.master')
@section('content')
    <!-- Profile Header -->

    @include('employees.partials.profile_view.profile_header')

    @include('employees.partials.creation_button')


    @if (Route::currentRouteNamed('employees.profile.general_informations'))
        @include('employees.partials.profile_view.general_info')
    @elseif(Route::currentRouteNamed('employees.profile.office_informations'))
        @include('employees.partials.profile_view.office_info')
    @elseif(Route::currentRouteNamed('employees.profile.eligible_plans'))
        @include('employees.partials.profile_view.eligible_plans_info')
    @elseif(Route::currentRouteNamed('employees.profile.education_information'))
        @include('employees.partials.profile_view.education_info')
    @elseif(Route::currentRouteNamed('employees.profile.nominee_information'))
        @include('employees.partials.profile_view.nominee_information')
    @elseif(Route::currentRouteNamed('employees.profile.salary_breakdown'))
        @include('employees.partials.profile_view.salary_breakdown')
    @elseif(Route::currentRouteNamed('employees.profile.bank_accounts'))
        @include('employees.partials.profile_view.bank_accounts')
    @elseif(Route::currentRouteNamed('employees.profile.plans'))
        @include('employees.partials.profile_view.plans')
    @elseif(Route::currentRouteNamed('employees.profile.leave_info'))
        @include('employees.partials.profile_view.leave_info')
    @endif
@endsection

@push('scripts')
    <script>
        // Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Employee Status Toggle with SweetAlert
        $(document).ready(function() {
            // Setup AJAX to include CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if(auth()->user()->user_type !== 'Employee')
            $('#employeeStatusToggle').on('change', function(e) {
                e.preventDefault();

                const checkbox = $(this);
                // Get the NEW status based on checkbox state AFTER the change
                const newStatus = checkbox.is(':checked') ? 'active' : 'inactive';

                // Revert checkbox state temporarily until confirmed
                checkbox.prop('checked', !checkbox.is(':checked'));

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to change employee status to ${newStatus}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User confirmed, proceed with the toggle
                        $.ajax({
                            url: '{{ route('employees.toggle_status', $employee->id) }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                status: newStatus
                            },
                            success: function(response) {
                                console.log('Response:', response);
                                if (response.success) {
                                    // Update checkbox state based on response status
                                    checkbox.prop('checked', response.status ===
                                        'active');

                                    // Update label text and color
                                    const statusLabel = $('#statusLabel');
                                    statusLabel.text(response.status.charAt(0)
                                        .toUpperCase() + response.status.slice(1));
                                    statusLabel.css('color', response.status ===
                                        'active' ? '#28a745' : '#dc3545');

                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message ||
                                            'Failed to update status',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', xhr, status, error);
                                console.error('Response Text:', xhr.responseText);

                                let errorMessage =
                                    'An error occurred while updating the status';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.status === 404) {
                                    errorMessage =
                                        'Route not found. Please check the route configuration.';
                                } else if (xhr.status === 422) {
                                    errorMessage =
                                        'Validation error. Please check the data being sent.';
                                } else if (xhr.status === 500) {
                                    errorMessage =
                                        'Server error. Please check the logs.';
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                    // If user cancelled, checkbox state is already reverted
                });
            });
            @endif
        });
    </script>
@endpush
