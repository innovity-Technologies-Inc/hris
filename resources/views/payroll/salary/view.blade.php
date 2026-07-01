@extends('structure.master')
@section('content')
    <div class="row">
        {{-- List Section --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-check-alt me-2"></i>
                            <h5 class="mb-0">Eligible Employees for Salary</h5>
                        </div>
                        <a href="{{route('salary.index')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Salary Month: {{ \Carbon\Carbon::parse($salary_month)->format('M, Y') }}</h6>
                    </div>

                    {{-- Approval Engine Workflow History --}}
                    @include('approval_engine.workflow_history', ['model' => $process])

                    <div class="table-responsive" id="search-result">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Total Salary</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($salaryes);
                            @endphp
                                @foreach($salaryes as $item)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $sl++ }}</th>
                                        <td>
                                            {!! \App\HelperClass::generateAvatar(
                                                $item->getEmployee->photo_path,
                                                $item->getEmployee->full_name,
                                                32,
                                                '#974063',
                                                '',
                                                $item->getEmployee->id,
                                            ) !!}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $item->getEmployee->applicant_id }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.profile.general_informations', $item->employee_id) }}">
                                                {{ $item->getEmployee->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->getEmployee->officeInfo->getCurrentDepartment->department_name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            ৳ {{ number_format($item->total_salary, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('salary.payroll.show', $item->id) }}" class="btn btn-info btn-sm" title="View Details">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $salaryes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection

