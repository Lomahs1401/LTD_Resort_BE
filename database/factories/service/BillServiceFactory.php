<?php

namespace Database\Factories\service;

use App\Models\service\Service;
use App\Models\user\Customer;
use App\Models\user\Employee;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\service\BillService>
 */
class BillServiceFactory extends Factory
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
        $service_model = new Service();
        $service_ids = $service_model->newQuery()->get('id');

        $book_time = fake()->dateTimeBetween('-12 days', 'now', 'Asia/Ho_Chi_Minh');
        $checkin_time = fake()->dateTimeBetween($book_time, 'now', 'Asia/Ho_Chi_Minh');

        $has_been_check = fake()->boolean(70);

        $startDate = \Carbon\Carbon::now()->subMonth();
        $endDate = \Carbon\Carbon::now();
        $billCode = fake()->dateTimeBetween($startDate, $endDate)->format('YmdHis');

        return [
            'quantity' => fake()->randomElement([1, 2, 3, 4]),
            'total_amount' => fake()->numberBetween(200000, 1200000),
            'book_time' => $book_time,
            'payment_method' => 'Online',
            'pay_time' => $checkin_time,
            'checkin_time' => $has_been_check ? $checkin_time : null,
            'cancel_time' => null,
            'tax' => fake()->randomElement([0.1, 0.12, 0.15]),
            'discount' => 0,
            'bill_code' => $billCode,
            'service_id' => fake()->randomElement($service_ids),
            'customer_id' => fake()->randomElement($customer_id_accounts),
            'employee_id' => $has_been_check ? fake()->randomElement($employee_id_accounts) : null,
        ];
    }
}
