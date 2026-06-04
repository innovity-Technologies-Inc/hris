<?php

namespace Database\Factories\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = \App\Models\Company\Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'short_name' => $this->faker->lexify('???'),
            'type_id' => 1,
            'group_id' => 1,
            'email' => $this->faker->companyEmail(),
            'address' => $this->faker->address(),
            'status' => 'active',
        ];
    }
}
