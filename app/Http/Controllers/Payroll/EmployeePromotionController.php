<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeePromotionController extends Controller
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

        $designations = collect([
            (object)['id' => 1, 'company_designation' => 'Junior Software Engineer'],
            (object)['id' => 2, 'company_designation' => 'Software Engineer'],
            (object)['id' => 3, 'company_designation' => 'Senior Software Engineer'],
            (object)['id' => 4, 'company_designation' => 'Lead Software Engineer'],
        ]);

        // Create promotion objects with proper relationships
        $promotionData = collect([
            (object)[
                'id' => 1,
                'employee_id' => 1,
                'previous_designation' => 2,
                'new_designation' => 3,
                'new_basic_salary' => 50000.00,
                'effective_from' => now()->subMonths(2),
                'effective_to' => null,
                'status' => 'approved',
                'getEmployee' => (object)[
                    'id' => 1,
                    'full_name' => 'Ahmed Rahman',
                    'applicant_id' => 'EMP-2024-001',
                    'system_id' => 'SYS-001',
                ],
                'getPreviousDesignation' => (object)[
                    'id' => 2,
                    'company_designation' => 'Software Engineer',
                ],
                'getNewDesignation' => (object)[
                    'id' => 3,
                    'company_designation' => 'Senior Software Engineer',
                ],
                'status_badge_class' => 'bg-success',
            ],
            (object)[
                'id' => 2,
                'employee_id' => 2,
                'previous_designation' => 1,
                'new_designation' => 2,
                'new_basic_salary' => 35000.00,
                'effective_from' => now()->addMonth(),
                'effective_to' => null,
                'status' => 'pending',
                'getEmployee' => (object)[
                    'id' => 2,
                    'full_name' => 'Fatima Khatun',
                    'applicant_id' => 'EMP-2024-002',
                    'system_id' => 'SYS-002',
                ],
                'getPreviousDesignation' => (object)[
                    'id' => 1,
                    'company_designation' => 'Junior Software Engineer',
                ],
                'getNewDesignation' => (object)[
                    'id' => 2,
                    'company_designation' => 'Software Engineer',
                ],
                'status_badge_class' => 'bg-warning',
            ],
        ]);

        // Create paginator for dummy data
        $promotions = new \Illuminate\Pagination\LengthAwarePaginator(
            $promotionData,
            $promotionData->count(),
            15,
            1
        );

        return view('payroll.promotion.index', compact('promotions', 'employees', 'designations'));
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
        ]);

        $designations = collect([
            (object)['id' => 1, 'company_designation' => 'Junior Software Engineer'],
            (object)['id' => 2, 'company_designation' => 'Software Engineer'],
            (object)['id' => 3, 'company_designation' => 'Senior Software Engineer'],
            (object)['id' => 4, 'company_designation' => 'Lead Software Engineer'],
            (object)['id' => 5, 'company_designation' => 'Tech Lead'],
        ]);

        return view('payroll.promotion.form', compact('employees', 'designations'));
    }

    public function store(Request $request)
    {
        return redirect()->route('promotion.index')->with('success', 'Promotion created successfully (Dummy Implementation)');
    }

    public function show($id)
    {
        // Object-style promotion dummy data
        $promotion = (object)[
            'id' => $id,
            'employee_id' => 1,
            'previous_designation' => 2,
            'new_designation' => 3,
            'new_basic_salary' => 50000.00,
            'effective_from' => \Carbon\Carbon::parse('2024-01-01'),
            'effective_to' => null,
            'status' => 'approved',
            'created_at' => \Carbon\Carbon::parse('2023-12-15 14:30:00'),
            'getEmployee' => (object)[
                'id' => 1,
                'full_name' => 'Ahmed Rahman',
                'applicant_id' => 'EMP-2024-001',
                'system_id' => 'SYS-001',
                'officeInfo' => (object)[
                    'getCurrentDepartment' => (object)['name' => 'IT Department'],
                ],
            ],
            'getPreviousDesignation' => (object)[
                'id' => 2,
                'company_designation' => 'Software Engineer',
            ],
            'getNewDesignation' => (object)[
                'id' => 3,
                'company_designation' => 'Senior Software Engineer',
            ],
            'status_badge_class' => 'bg-success',
            'promotion_summary' => '৳50,000.00 (New Basic Salary)',
        ];

        return view('payroll.promotion.view', compact('promotion'));
    }

    public function edit($id)
    {
        // Combine create data with existing promotion data
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

        $designations = collect([
            (object)['id' => 1, 'company_designation' => 'Junior Software Engineer'],
            (object)['id' => 2, 'company_designation' => 'Software Engineer'],
            (object)['id' => 3, 'company_designation' => 'Senior Software Engineer'],
            (object)['id' => 4, 'company_designation' => 'Lead Software Engineer'],
        ]);

        $promotion = (object)[
            'id' => $id,
            'employee_id' => 1,
            'previous_designation' => 2,
            'new_designation' => 3,
            'new_basic_salary' => 50000.00,
            'effective_from' => '2024-01-01',
            'effective_to' => null,
            'status' => 'pending',
        ];

        return view('payroll.promotion.form', compact('employees', 'designations', 'promotion'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('promotion.show', $id)->with('success', 'Promotion updated successfully (Dummy Implementation)');
    }

    public function approve($id)
    {
        return redirect()->route('promotion.show', $id)->with('success', 'Promotion approved successfully (Dummy Implementation)');
    }

    public function reject($id)
    {
        return redirect()->route('promotion.show', $id)->with('error', 'Promotion rejected (Dummy Implementation)');
    }
}
