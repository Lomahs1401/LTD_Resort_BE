<?php

namespace Database\Factories\room;

use App\Models\user\Customer;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\room\BillRoom>
 */
class BillRoomFactory extends Factory
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
        $checkin_time = fake()->dateTimeBetween('-12 days', 'now', 'Asia/Ho_Chi_Minh');

        return [
            'total_amount' => fake()->numberBetween(440000, 1160000),
            'total_room' => fake()->numberBetween(2, 6),
            'total_people' => fake()->numberBetween(4, 8),
            'payment_method' => 'Online',
            'pay_time' => $checkin_time,
            'checkin_time' => null,
            'checkout_time' => null,
            'cancel_time' => null,
            'tax' => 0,
            'discount' => 0,
            'customer_id' => fake()->randomElement($customer_id_accounts),
        ];
    }
}
