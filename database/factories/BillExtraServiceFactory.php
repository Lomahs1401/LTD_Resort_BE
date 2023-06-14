<?php

namespace Database\Factories;

use App\Models\user\Customer;
use App\Models\user\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\room\BillExtraService>
 */
class BillExtraServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer_model = new Customer();
        $customer_id_accounts = $customer_model->newQuery()->get('id');
        $employee_model = new Employee();
        $employee_id_accounts = $employee_model->newQuery()->get('id');

        $has_been_check = fake()->boolean(70);

        return [
            'total_amount' => fake()->numberBetween(32000, 80000),
            'payment_method' => 'Online',
            'tax' => fake()->randomElement([0.1, 0.12, 0.15]),
            'discount' => 0,
            'customer_id' => fake()->randomElement($customer_id_accounts),
            'employee_id' => $has_been_check ? fake()->randomElement($employee_id_accounts) : null,
        ];
    }
}
