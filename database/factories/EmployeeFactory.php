<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'applicant_id' => 'APP-' . $this->faker->unique()->numberBetween(1000, 9999),
            'system_id' => 'SYS-' . $this->faker->unique()->numberBetween(1000, 9999),
            'punch_card_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'full_name' => $this->faker->name,
            'status' => 'active',
        ];
    }
}
