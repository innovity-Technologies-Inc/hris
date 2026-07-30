<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payroll\TaxPolicy;
use App\Models\Payroll\TaxSlab;
use Illuminate\Support\Facades\Schema;

class TaxPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to prevent duplicates
        Schema::disableForeignKeyConstraints();
        TaxSlab::truncate();
        TaxPolicy::truncate();
        Schema::enableForeignKeyConstraints();

        // Create the primary tax policy
        $policy = TaxPolicy::create([
            'zero_tax_male' => 375000.00,
            'zero_tax_female' => 425000.00,
            'min_tax_amount' => 5000.00,
            'exemption_type' => 'fixed',
            'salary_ratio' => '1/3',
            'fixed_amount' => 500000.00,
            'exempt_allowances' => [],
            'min_negotiable_tax_limit' => 0.00,
            'tax_payable_percentage' => 100.00,
            'total_tax_month' => 13,
            'applicable_pay_groups' => [1],
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Create the progressive tax slabs
        $policy->slabs()->createMany([
            [
                'taxable_amount' => 375000.00,
                'tax_percentage' => 0.00,
                'tax_amount' => 0.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'taxable_amount' => 300000.00,
                'tax_percentage' => 10.00,
                'tax_amount' => 30000.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'taxable_amount' => 400000.00,
                'tax_percentage' => 15.00,
                'tax_amount' => 60000.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'taxable_amount' => 500000.00,
                'tax_percentage' => 20.00,
                'tax_amount' => 100000.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'taxable_amount' => 2000000.00,
                'tax_percentage' => 25.00,
                'tax_amount' => 500000.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'taxable_amount' => null,
                'tax_percentage' => 30.00,
                'tax_amount' => 0.00,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ]);
    }
}
