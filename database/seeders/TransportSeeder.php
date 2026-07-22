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
        $company = Company::first() ?? Company::create([
            'name' => 'Default Company',
            'short_name' => 'DEF',
            'status' => 'active',
            'address' => 'Dhaka',
        ]);

        // 1. Create Designation for Driver if not exists
        $designation = Designation::firstOrCreate(
            ['company_designation' => 'Driver'],
            [
                'designation_level' => 1,
                'status' => 'active',
            ]
        );

        // 2. Fetch or create multiple driver employees (10 drivers)
        $drivers = [];
        for ($i = 1; $i <= 10; $i++) {
            $driverEmail = "driver{$i}@example.com";
            $driver = Employee::where('personal_mobile', '017000000' . sprintf('%02d', $i))->first();
            if (!$driver) {
                $driver = Employee::create([
                    'first_name' => 'Driver',
                    'last_name' => (string)$i,
                    'full_name' => "Driver {$i}",
                    'father_name' => 'Father',
                    'mother_name' => 'Mother',
                    'nationality' => 'Bangladeshi',
                    'religion' => 'Islam',
                    'present_address' => json_encode(['address' => "Dhaka Sector {$i}"]),
                    'personal_mobile' => '017000000' . sprintf('%02d', $i),
                    'date_of_birth' => '1990-05-15',
                    'system_id' => "SysDRV" . sprintf('%03d', $i),
                    'applicant_id' => "DRV-" . sprintf('%03d', $i),
                    'punch_card_no' => "PC-DRV-" . sprintf('%03d', $i),
                    'gender' => 'Male',
                    'marital_status' => 'Single',
                    'blood_group' => 'O+',
                    'status' => 'active',
                ]);

                EmployeeOfficeInfo::create([
                    'employee_id' => $driver->id,
                    'current_company_id' => $company->id,
                    'joining_company_id' => $company->id,
                    'current_designation_id' => $designation->id,
                    'joining_designation_id' => $designation->id,
                    'joining_division_id' => 1,
                    'date_of_join' => '2023-01-01',
                ]);
            }
            $drivers[] = $driver;
        }

        // 3. Create multiple Vehicles (15 vehicles)
        $vehicles = [];
        $categories = ['Car', 'Bus', 'Micro Bus', 'Van'];
        for ($i = 1; $i <= 15; $i++) {
            $category = $categories[$i % count($categories)];
            $vehicle = Vehicle::create([
                'vehicle_category' => $category,
                'model_number' => "Model {$i}",
                'manufacture_year' => 2020 + ($i % 4),
                'body_type' => $category === 'Micro Bus' ? 'Van' : 'Sedan',
                'fuel_type' => 'Petrol',
                'engine_capacity' => '2000cc',
                'seating_capacity' => $category === 'Micro Bus' ? 15 : 5,
                'color' => $i % 2 === 0 ? 'Black' : 'Silver',
                'mileage' => 1000 * $i,
                'license_number' => "DHK-METRO-CHA-11-" . sprintf('%04d', 2000 + $i),
                'purchase_type' => 'Purchase',
                'purchase_date' => '2023-05-10',
                'purchase_price' => 3000000,
                'ownership_type' => 'Company-owned',
                'status' => 'Active',
            ]);
            $vehicles[] = $vehicle;
        }

        // 4. Assign Drivers to Vehicles
        for ($i = 0; $i < min(count($vehicles), count($drivers)); $i++) {
            VehicleDriver::create([
                'vehicle_id' => $vehicles[$i]->id,
                'driver_id' => $drivers[$i]->id,
                'start_date' => '2026-01-01',
                'status' => 'active',
            ]);
        }

        // 5. Create Route Maps (10 routes)
        $routeMaps = [];
        $points = ['Mirpur 10', 'Gulshan 2', 'Uttara Sector 3', 'Dhanmondi 27', 'Motijheel C/A', 'Banani 11', 'Mohakhali'];
        for ($i = 1; $i <= 10; $i++) {
            $start = $points[($i - 1) % count($points)];
            $end = $points[$i % count($points)];
            $routeMap = RouteMap::create([
                'route_name' => "{$start} to {$end}",
                'start_point' => $start,
                'end_point' => $end,
                'status' => 'Active',
            ]);
            $routeMaps[] = $routeMap;
        }

        // 6. Get/create requester employees
        $requesters = [];
        for ($i = 1; $i <= 10; $i++) {
            $mobile = '018000000' . sprintf('%02d', $i);
            $requester = Employee::where('personal_mobile', $mobile)->first();
            if (!$requester) {
                $requester = Employee::create([
                    'first_name' => 'Requester',
                    'last_name' => (string)$i,
                    'full_name' => "Requester {$i}",
                    'father_name' => 'Father',
                    'mother_name' => 'Mother',
                    'nationality' => 'Bangladeshi',
                    'religion' => 'Islam',
                    'present_address' => json_encode(['address' => "Dhaka Zone {$i}"]),
                    'personal_mobile' => $mobile,
                    'date_of_birth' => '1995-08-20',
                    'system_id' => "SysREQ" . sprintf('%03d', 100 + $i),
                    'applicant_id' => "REQ-" . sprintf('%03d', 100 + $i),
                    'punch_card_no' => "PC-REQ-" . sprintf('%03d', 100 + $i),
                    'gender' => 'Male',
                    'marital_status' => 'Single',
                    'blood_group' => 'A+',
                    'status' => 'active',
                ]);
            }
            $requesters[] = $requester;
        }

        // 7. Create Vehicle Requisitions (25 requisitions)
        for ($i = 1; $i <= 25; $i++) {
            $reqEmployee = $requesters[$i % count($requesters)];
            VehicleRequisition::create([
                'employee_id' => $reqEmployee->id,
                'trip_type' => $i % 2 === 0 ? 'Official' : 'Personal',
                'trip_mode' => $i % 2 === 0 ? 'Round-trip' : 'One-way',
                'purpose_of_travel' => "Seeded Trip Purpose #{$i}",
                'start_date_time' => Carbon::now()->addDays($i)->format('Y-m-d H:i:s'),
                'end_date_time' => Carbon::now()->addDays($i)->addHours(4)->format('Y-m-d H:i:s'),
                'pickup_location' => 'Office HQ',
                'destination' => 'Client Location ' . $i,
                'no_of_passengers' => ($i % 4) + 1,
                'vehicle_type_required' => $i % 3 === 0 ? 'Car' : ($i % 3 === 1 ? 'Bus' : 'Micro'),
                'approval_status' => $i % 3 === 0 ? 'Approved' : ($i % 3 === 1 ? 'Pending' : 'Rejected'),
            ]);
        }

        // 8. Create Vehicle Allocations (25 allocations)
        for ($i = 1; $i <= 25; $i++) {
            $allocVehicle = $vehicles[$i % count($vehicles)];
            VehicleAllocation::create([
                'vehicle_id' => $allocVehicle->id,
                'name' => "Allocation Service #{$i}",
                'allocation_type' => $i % 2 === 0 ? 'employee_transport' : 'trip_based',
                'allocated_to' => "Department " . (($i % 4) + 1),
                'approved_by' => 1,
                'start_date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(30 - $i)->format('Y-m-d'),
                'status' => $i % 4 === 0 ? 'Completed' : 'Active',
            ]);
        }

        // 9. Create Employee Transports (20 employee transports)
        for ($i = 1; $i <= 20; $i++) {
            $route = $routeMaps[$i % count($routeMaps)];
            EmployeeTransport::create([
                'type' => 'company',
                'company_id' => $company->id,
                'service_name' => "Commute Route Service #{$i}",
                'transport_type' => 'Daily Commute',
                'purpose' => "Seeded pick & drop #{$i}",
                'route_map_id' => $route->id,
                'start_date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(30 + $i)->format('Y-m-d'),
                'status' => $i % 3 === 0 ? 'Approved' : 'Pending',
            ]);
        }
    }
}
