<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transport\Vehicle;
use App\Models\Transport\VehicleDriver;
use App\Models\Transport\RouteMap;
use App\Models\Transport\VehicleAllocation;
use App\Models\Transport\VehicleRequisition;
use App\Models\Transport\EmployeeTransport;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Designation;
use App\Models\Company\Company;
use Carbon\Carbon;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Designation for Driver if not exists
        $designation = Designation::firstOrCreate(
            ['company_designation' => 'Driver'],
            [
                'designation_level' => 1,
                'status' => 'active',
            ]
        );

        // 2. Fetch or create a driver employee
        $driverEmployee = Employee::where('full_name', 'John Driver')->first();
        if (!$driverEmployee) {
            $driverEmployee = Employee::create([
                'first_name' => 'John',
                'last_name' => 'Driver',
                'full_name' => 'John Driver',
                'father_name' => 'Father',
                'mother_name' => 'Mother',
                'nationality' => 'Bangladeshi',
                'religion' => 'Islam',
                'present_address' => json_encode(['address' => 'Dhaka']),
                'personal_mobile' => '01700000001',
                'date_of_birth' => '1990-05-15',
                'system_id' => 'SysDRV101',
                'applicant_id' => 'DRV-101',
                'punch_card_no' => 'PC-DRV-101',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'blood_group' => 'O+',
                'status' => 'active',
            ]);

            $company = Company::first();
            EmployeeOfficeInfo::create([
                'employee_id' => $driverEmployee->id,
                'current_company_id' => $company?->id ?? 1,
                'joining_company_id' => $company?->id ?? 1,
                'current_designation_id' => $designation->id,
                'joining_designation_id' => $designation->id,
                'joining_division_id' => 1,
                'date_of_join' => '2023-01-01',
            ]);
        }

        // 3. Create Vehicles
        $vehicle1 = Vehicle::create([
            'vehicle_category' => 'Micro Bus',
            'model_number' => 'Toyota HiAce - 2023',
            'manufacture_year' => 2023,
            'body_type' => 'Van',
            'fuel_type' => 'Petrol',
            'engine_capacity' => '2500cc',
            'seating_capacity' => 15,
            'color' => 'Silver',
            'mileage' => 5000,
            'license_number' => 'DHK-METRO-CHA-11-2222',
            'purchase_type' => 'Purchase',
            'purchase_date' => '2023-05-10',
            'purchase_price' => 3500000,
            'ownership_type' => 'Company-owned',
            'status' => 'Active',
        ]);

        $vehicle2 = Vehicle::create([
            'vehicle_category' => 'Car',
            'model_number' => 'Toyota Premio - 2022',
            'manufacture_year' => 2022,
            'body_type' => 'Sedan',
            'fuel_type' => 'Petrol',
            'engine_capacity' => '1500cc',
            'seating_capacity' => 5,
            'color' => 'Black',
            'mileage' => 12000,
            'license_number' => 'DHK-METRO-GA-33-4444',
            'purchase_type' => 'Purchase',
            'purchase_date' => '2022-08-15',
            'purchase_price' => 2500000,
            'ownership_type' => 'Company-owned',
            'status' => 'Active',
        ]);

        // 4. Assign Driver to Vehicle 1
        VehicleDriver::create([
            'vehicle_id' => $vehicle1->id,
            'driver_id' => $driverEmployee->id,
            'start_date' => '2026-01-01',
            'status' => 'active',
        ]);

        // 5. Create Route Map
        $routeMap = RouteMap::create([
            'route_name' => 'Mirpur to Gulshan',
            'start_point' => 'Mirpur 10',
            'end_point' => 'Gulshan 2',
            'status' => 'Active',
        ]);

        // 6. Get another general employee for requisitions
        $requester = Employee::where('id', '!=', $driverEmployee->id)->first();
        if (!$requester) {
            $requester = Employee::create([
                'first_name' => 'Alice',
                'last_name' => 'Requester',
                'full_name' => 'Alice requester',
                'father_name' => 'Father',
                'mother_name' => 'Mother',
                'nationality' => 'Bangladeshi',
                'religion' => 'Islam',
                'present_address' => json_encode(['address' => 'Dhaka']),
                'personal_mobile' => '01700000002',
                'date_of_birth' => '1995-08-20',
                'system_id' => 'SysREQ102',
                'applicant_id' => 'REQ-102',
                'punch_card_no' => 'PC-REQ-102',
                'gender' => 'Female',
                'marital_status' => 'Single',
                'blood_group' => 'A+',
                'status' => 'active',
            ]);
        }

        // 7. Create Vehicle Requisition
        VehicleRequisition::create([
            'employee_id' => $requester->id,
            'trip_type' => 'Official',
            'trip_mode' => 'Round-trip',
            'purpose_of_travel' => 'Client onsite support meeting',
            'start_date_time' => Carbon::now()->addHour()->format('Y-m-d H:i:s'),
            'end_date_time' => Carbon::now()->addHours(4)->format('Y-m-d H:i:s'),
            'pickup_location' => 'Office HQ',
            'destination' => 'Client office',
            'no_of_passengers' => 3,
            'vehicle_type_required' => 'Car',
            'approval_status' => 'Pending',
        ]);

        // 8. Create Vehicle Allocation
        VehicleAllocation::create([
            'vehicle_id' => $vehicle1->id,
            'name' => 'Operations Commute',
            'allocation_type' => 'employee_transport',
            'allocated_to' => 'Operations Department',
            'approved_by' => 1,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'status' => 'Active',
        ]);

        // 9. Create Employee Transport
        EmployeeTransport::create([
            'type' => 'company',
            'company_id' => Company::first()?->id ?? 1,
            'service_name' => 'Morning Shift Shuttle',
            'transport_type' => 'Daily Commute',
            'purpose' => 'Employee pick and drop service',
            'route_map_id' => $routeMap->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'status' => 'Pending',
        ]);
    }
}
