<?php

namespace App\Services\Payroll;

use App\Models\Payroll\TaxPolicy;
use App\Models\Payroll\TaxSlab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxPolicyService
{
    /**
     * Save or update a Tax Policy and its slabs.
     */
    public function saveTaxPolicy(array $data, $id = null): TaxPolicy
    {
        return DB::transaction(function () use ($data, $id) {
            Log::info('Saving tax policy.', ['id' => $id, 'data' => $data]);

            // Set default values if type is fixed or exempt_allowance
            if ($data['exemption_type'] === 'fixed') {
                $data['exempt_allowances'] = null;
            } else {
                $data['salary_ratio'] = null;
                $data['fixed_amount'] = null;
            }

            // Create or Update
            $taxPolicy = TaxPolicy::updateOrCreate(
                ['id' => $id],
                [
                    'zero_tax_male' => $data['zero_tax_male'],
                    'zero_tax_female' => $data['zero_tax_female'],
                    'min_tax_amount' => $data['min_tax_amount'],
                    'exemption_type' => $data['exemption_type'],
                    'salary_ratio' => $data['salary_ratio'],
                    'fixed_amount' => $data['fixed_amount'],
                    'exempt_allowances' => $data['exempt_allowances'] ?? [],
                    'min_negotiable_tax_limit' => $data['min_negotiable_tax_limit'],
                    'tax_payable_percentage' => $data['tax_payable_percentage'],
                ]
            );

            // Sync Slabs
            $taxPolicy->slabs()->delete();

            if (!empty($data['slabs'])) {
                foreach ($data['slabs'] as $slab) {
                    $taxPolicy->slabs()->create([
                        'taxable_amount' => $slab['taxable_amount'] ?? null,
                        'tax_percentage' => $slab['tax_percentage'],
                        'tax_amount' => $slab['tax_amount'],
                    ]);
                }
            }

            return $taxPolicy;
        });
    }

    /**
     * Delete a Tax Policy.
     */
    public function deleteTaxPolicy($id): bool
    {
        return DB::transaction(function () use ($id) {
            Log::info('Deleting tax policy.', ['id' => $id]);
            $policy = TaxPolicy::findOrFail($id);
            return $policy->delete();
        });
    }
}
