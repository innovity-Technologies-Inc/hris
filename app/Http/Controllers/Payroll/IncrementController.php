<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncrementController extends Controller
{
    public function index()
    {
        // Object-style dummy data following Laravel patterns
        $employees = collect([
            (object)[
                'id' => 1,
                'full_name' => 'Ahmed Rahman',
                'applicant_id' => 'EMP-2024-001',
                'system_id' => 'SYS-001',
            ],
            (object)[
                'id' => 2,
                'full_name' => 'Fatima Khatun',
                'applicant_id' => 'EMP-2024-002',
                'system_id' => 'SYS-002',
            ],
            (object)[
                'id' => 3,
                'full_name' => 'Mohammad Karim',
                'applicant_id' => 'EMP-2024-003',
                'system_id' => 'SYS-003',
            ],
        ]);

        // Create increment objects with proper relationships
        $incrementData = collect([
            (object)[
                'id' => 1,
                'employee_id' => 1,
                'increment_base' => 'basic_salary',
                'increment_method' => 'percentage',
                'increment_amount' => 10.00,
                'effective_from' => now()->subMonths(3),
                'effective_to' => null,
                'status' => 'approved',
                'getEmployee' => (object)[
                    'id' => 1,
                    'full_name' => 'Ahmed Rahman',
                    'applicant_id' => 'EMP-2024-001',
                    'system_id' => 'SYS-001',
                    'officeInfo' => (object)[
                        'current_designation' => 'Software Engineer',
                        'grade' => 'Grade 5',
                    ],
                ],
                'status_badge_class' => 'bg-success',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 2,
                'increment_base' => 'gross_salary',
                'increment_method' => 'fixed',
                'increment_amount' => 5000.00,
                'effective_from' => now()->addWeeks(2),
                'effective_to' => null,
                'status' => 'pending',
                'getEmployee' => (object)[
                    'id' => 2,
                    'full_name' => 'Fatima Khatun',
                    'applicant_id' => 'EMP-2024-002',
                    'system_id' => 'SYS-002',
                    'officeInfo' => (object)[
                        'current_designation' => 'Junior Software Engineer',
                        'grade' => 'Grade 4',
                    ],
                ],
                'status_badge_class' => 'bg-warning',
            ],
            (object)[
                'id' => 3,
                'employee_id' => 3,
                'increment_base' => 'basic_salary',
                'increment_method' => 'percentage',
                'increment_amount' => 12.00,
                'effective_from' => now()->subMonth(),
                'effective_to' => null,
                'status' => 'approved',
                'getEmployee' => (object)[
                    'id' => 3,
                    'full_name' => 'Mohammad Karim',
                    'applicant_id' => 'EMP-2024-003',
                    'system_id' => 'SYS-003',
                    'officeInfo' => (object)[
                        'current_designation' => 'Senior Software Engineer',
                        'grade' => 'Grade 6',
                    ],
                ],
                'status_badge_class' => 'bg-success',
            ],
        ]);

        // Create paginator for dummy data
        $increments = new \Illuminate\Pagination\LengthAwarePaginator(
            $incrementData,
            $incrementData->count(),
            15,
            1
        );

        return view('payroll.increment.index', compact('increments', 'employees'));
    }

    public function create()
    {
        // Object-style employee dummy data with office info and salary breakdown
        $employees = collect([
            (object)[
                'id' => 1,
                'full_name' => 'Ahmed Rahman',
                'applicant_id' => 'EMP-2024-001',
                'system_id' => 'SYS-001',
                'officeInfo' => (object)[
                    'current_designation_id' => 2,
                    'current_designation' => 'Software Engineer',
                    'grade' => 'Grade 5',
                    'getCurrentDepartment' => (object)['name' => 'IT Department'],
                ],
                'salaryBreakdown' => (object)[
                    'basic_salary' => 35000.00,
                    'house_allowance' => 14000.00,
                    'transport_allowance' => 4000.00,
                    'food_allowance' => 2500.00,
                    'medical_allowance' => 3500.00,
                    'other_earnings' => 0.00,
                    'gross_salary' => 59000.00,
                ],
            ],
            (object)[
                'id' => 2,
                'full_name' => 'Fatima Khatun',
                'applicant_id' => 'EMP-2024-002',
                'system_id' => 'SYS-002',
                'officeInfo' => (object)[
                    'current_designation_id' => 1,
                    'current_designation' => 'Junior Software Engineer',
                    'grade' => 'Grade 4',
                    'getCurrentDepartment' => (object)['name' => 'IT Department'],
                ],
                'salaryBreakdown' => (object)[
                    'basic_salary' => 28000.00,
                    'house_allowance' => 11200.00,
                    'transport_allowance' => 3000.00,
                    'food_allowance' => 2000.00,
                    'medical_allowance' => 2800.00,
                    'other_earnings' => 0.00,
                    'gross_salary' => 47000.00,
                ],
            ],
            (object)[
                'id' => 3,
                'full_name' => 'Mohammad Karim',
                'applicant_id' => 'EMP-2024-003',
                'system_id' => 'SYS-003',
                'officeInfo' => (object)[
                    'current_designation_id' => 3,
                    'current_designation' => 'Senior Software Engineer',
                    'grade' => 'Grade 6',
                    'getCurrentDepartment' => (object)['name' => 'IT Department'],
                ],
                'salaryBreakdown' => (object)[
                    'basic_salary' => 45000.00,
                    'house_allowance' => 18000.00,
                    'transport_allowance' => 5000.00,
                    'food_allowance' => 3000.00,
                    'medical_allowance' => 4500.00,
                    'other_earnings' => 0.00,
                    'gross_salary' => 75500.00,
                ],
            ],
        ]);

        return view('payroll.increment.form', compact('employees'));
    }

    public function store(Request $request)
    {
        return redirect()->route('increment.index')->with('success', 'Increment created successfully (Dummy Implementation)');
    }

    public function show($id)
    {
        // Object-style increment dummy data
        $increment = (object)[
            'id' => $id,
            'employee_id' => 1,
            'increment_base' => 'basic_salary',
            'increment_method' => 'percentage',
            'increment_amount' => 10.00,
            'effective_from' => \Carbon\Carbon::parse('2024-01-01'),
            'effective_to' => null,
            'status' => 'approved',
            'created_at' => \Carbon\Carbon::parse('2023-12-20 10:15:00'),
            'getEmployee' => (object)[
                'id' => 1,
                'full_name' => 'Ahmed Rahman',
                'applicant_id' => 'EMP-2024-001',
                'system_id' => 'SYS-001',
                'officeInfo' => (object)[
                    'current_designation' => 'Software Engineer',
                    'grade' => 'Grade 5',
                    'getCurrentDepartment' => (object)['name' => 'IT Department'],
                ],
            ],
            'status_badge_class' => 'bg-success',
            'increment_summary' => '10% (Percentage on Basic Salary)',
        ];

        return view('payroll.increment.view', compact('increment'));
    }

    public function edit($id)
    {
        // Combine create data with existing increment data
        $employees = collect([
            (object)[
                'id' => 1,
                'full_name' => 'Ahmed Rahman',
                'applicant_id' => 'EMP-2024-001',
                'system_id' => 'SYS-001',
                'officeInfo' => (object)[
                    'current_designation_id' => 2,
                    'current_designation' => 'Software Engineer',
                    'grade' => 'Grade 5',
                ],
                'salaryBreakdown' => (object)[
                    'basic_salary' => 35000.00,
                    'house_allowance' => 14000.00,
                    'transport_allowance' => 4000.00,
                    'food_allowance' => 2500.00,
                    'medical_allowance' => 3500.00,
                    'other_earnings' => 0.00,
                    'gross_salary' => 59000.00,
                ],
            ],
        ]);

        $increment = (object)[
            'id' => $id,
            'employee_id' => 1,
            'increment_base' => 'basic_salary',
            'increment_method' => 'percentage',
            'increment_amount' => 10.00,
            'effective_from' => '2024-01-01',
            'effective_to' => null,
            'status' => 'pending',
        ];

        return view('payroll.increment.form', compact('employees', 'increment'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('increment.show', $id)->with('success', 'Increment updated successfully (Dummy Implementation)');
    }

    public function approve($id)
    {
        return redirect()->route('increment.show', $id)->with('success', 'Increment approved successfully (Dummy Implementation)');
    }

    public function reject($id)
    {
        return redirect()->route('increment.show', $id)->with('error', 'Increment rejected (Dummy Implementation)');
    }
}
