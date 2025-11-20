@extends('structure.master')
@section('content')

    <!-- Profile Header -->

    @include('employees.partials.profile_view.profile_header')

    @include('employees.partials.creation_button')


    @if(Route::currentRouteNamed('employees.profile.general_informations'))
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
    @endif



@endsection

@push('scripts')
    <script>
        // Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endpush
