<?php

namespace Tests\Feature\Company;

use App\Models\User;
use App\Models\Company\PayGroup;
use App\Models\Company\SalaryGrade;
use App\Models\Company\PayScale;
use App\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayGroupCascadeDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['user_type' => UserType::Group]);
    }

    /** @test */
    public function it_deletes_related_pay_scales_when_pay_group_is_deleted()
    {
        $payGroup = PayGroup::create([
            'title' => 'Test Group',
            'payroll_frequency' => 'monthly',
            'salary_processing_day' => 25,
            'status' => 'active'
        ]);

        $grade = SalaryGrade::create([
            'grade_name' => 'Grade 1',
            'grade_code' => 'G1',
            'status' => 'active'
        ]);

        $payScale = PayScale::create([
            'title' => 'Test Scale',
            'grade_id' => $grade->id,
            'pay_group_id' => $payGroup->id,
            'min_salary' => 10000,
            'max_salary' => 50000,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('pay_scales', ['id' => $payScale->id]);

        // Delete Pay Group
        $response = $this->actingAs($this->admin)->withoutMiddleware()->delete(route('pay_groups.delete', $payGroup->id));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('pay_groups', ['id' => $payGroup->id]);
        $this->assertDatabaseMissing('pay_scales', ['id' => $payScale->id]);
    }

    /** @test */
    public function it_allows_deleting_orphaned_pay_scales()
    {
        $payGroup = PayGroup::create([
            'title' => 'Test Group',
            'payroll_frequency' => 'monthly',
            'salary_processing_day' => 25,
            'status' => 'active'
        ]);

        $grade = SalaryGrade::create([
            'grade_name' => 'Grade 1',
            'grade_code' => 'G1',
            'status' => 'active'
        ]);

        $payScale = PayScale::create([
            'title' => 'Orphaned Scale',
            'grade_id' => $grade->id,
            'pay_group_id' => $payGroup->id,
            'min_salary' => 10000,
            'max_salary' => 50000,
            'status' => 'active'
        ]);

        // Delete PayGroup using Query Builder to bypass Eloquent events
        $driver = \DB::getDriverName();
        if ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        }
        
        \DB::table('pay_groups')->where('id', $payGroup->id)->delete();
        
        if ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }

        $this->assertDatabaseMissing('pay_groups', ['id' => $payGroup->id]);
        $this->assertDatabaseHas('pay_scales', ['id' => $payScale->id]);

        // Now try to delete the orphaned PayScale via controller
        $response = $this->actingAs($this->admin)->withoutMiddleware()->delete(route('pay_scales.delete', $payScale->id));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('pay_scales', ['id' => $payScale->id]);
    }

    /** @test */
    public function it_prevents_deleting_pay_scale_if_used_by_employee()
    {
        $payGroup = PayGroup::create([
            'title' => 'Test Group',
            'payroll_frequency' => 'monthly',
            'salary_processing_day' => 25,
            'status' => 'active'
        ]);

        $grade = SalaryGrade::create([
            'grade_name' => 'Grade 1',
            'grade_code' => 'G1',
            'status' => 'active'
        ]);

        $payScale = PayScale::create([
            'title' => 'Used Scale',
            'grade_id' => $grade->id,
            'pay_group_id' => $payGroup->id,
            'min_salary' => 10000,
            'max_salary' => 50000,
            'status' => 'active'
        ]);

        $employee = \App\Models\Employee\Employee::factory()->create();
        
        \DB::table('employee_salary_breakdowns')->insert([
            'employee_id' => $employee->id,
            'pay_scale_id' => $payScale->id,
            'gross_salary' => 30000,
            'basic_salary' => 15000,
            'basic_salary_percentage' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Try to delete the PayScale
        $response = $this->actingAs($this->admin)->withoutMiddleware()->delete(route('pay_scales.delete', $payScale->id));
        
        // It should fail (either 500 due to DB exception or we handle it)
        // Controller currently returns 500 in catch block
        $response->assertStatus(500);
        $this->assertDatabaseHas('pay_scales', ['id' => $payScale->id]);
    }
}
