<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake('vi_VN')->lastName() 
                    . ' ' . fake('vi_VN')->middleName() 
                    . ' ' . fake('vi_VN')->firstName(),
            'gender' => fake()->randomElement(['Nam', 'Nữ']),
            'birthday' => fake()->dateTimeInInterval('-20 years', '+2 years', 'Asia/Ho_Chi_Minh')->format('Y-m-d'),
            'CMND' => fake()->numerify('#########'),
            'address' => fake()->boolean() ? fake('vi_VN')->city() : fake('vi_VN')->province(),
            'phone' => fake('vi_VN')->regexify('(0|3|5|7|8|9){1}([0-9]{8})'),
            'image' => fake()->imageUrl(),
            'status' => fake()->boolean(100),
        ];
    }
}
