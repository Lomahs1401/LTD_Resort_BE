<?php

namespace Database\Factories;

use App\Models\RoomType;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $feedback_types = ['ROOM', 'SERVICE'];
        $feedback_type = fake()->randomElement($feedback_types);
        $good_ratings = [4, 5];
        $bad_ratings = [1, 2, 3];
        $feedback_status = ['Feedbacked', 'Not feedbacked yet'];

        $employee_model = new Employee();
        $customer_model = new Customer();
        $room_type_model = new RoomType();
        $service_model = new Service();

        $employee_id_accounts = $employee_model->newQuery()->get('id');
        $customer_id_accounts = $customer_model->newQuery()->get('id');
        $room_type_id = $room_type_model->newQuery()->get('id');
        $service_id = $service_model->newQuery()->get('id');

        $isFeedbacked = fake()->randomElement($feedback_status);

        return [
            'date_request' => fake()->dateTimeInInterval('-5 years', '+1 years', 'Asia/Ho_Chi_Minh'),
            'date_response' => $isFeedbacked == 'Feedbacked' ? fake()->dateTimeInInterval('-3 years', '+ 2 months', 'Asia/Ho_Chi_Minh') : null,
            'feedback_type' => $feedback_type,
            'image' => fake()->imageUrl(),
            'rating' => fake()->boolean(90) ? fake()->randomElement($good_ratings) : fake()->randomElement($bad_ratings),
            'title' => fake()->sentence(),
            'comment' => fake()->paragraph(16),
            'feedback_status' => $isFeedbacked,
            'employee_id' => $isFeedbacked == 'Feedbacked' ? fake()->randomElement($employee_id_accounts) : null,
            'customer_id' => fake()->randomElement($customer_id_accounts),
            'room_type_id' => $feedback_type == 'ROOM' ? fake()->randomElement($room_type_id) : null,
            'service_id' => $feedback_type == 'SERVICE' ? fake()->randomElement($service_id) : null,
        ];
    }
}
