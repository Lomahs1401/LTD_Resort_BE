<?php

namespace Database\Factories\user;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\user\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameBanks = ['Vietcombank', 'BIDV', 'Techcombank', 'Agribank', 'Vietinbank', 'Oceanbank', 'MBBank'];

        return [
            'full_name' => fake()->boolean() ? fake('vi_VN')->lastName() . ' ' . fake('vi_VN')->middleNameMale() . ' ' . fake('vi_VN')->firstNameMale() 
                                    : fake('vi_VN')->lastName() . ' ' . fake('vi_VN')->middleNameFemale() . ' ' . fake('vi_VN')->firstNameFemale(),
            'gender' => fake()->randomElement(['Nam', 'Nữ']),
            'birthday' => fake()->dateTimeInInterval('-50 years', '+40 years', 'Asia/Ho_Chi_Minh')->format('Y-m-d'),
            'CMND' => fake()->numerify('#########'),
            'address' => fake()->boolean() ? fake('vi_VN')->city() : fake('vi_VN')->province(),
            'phone' => fake('vi_VN')->regexify('(0|3|5|7|8|9){1}([0-9]{8})'),
            'account_bank' => fake()->numerify('##########'),
            'name_bank' => fake()->randomElement($nameBanks),
            'day_start' => fake()->dateTimeInInterval('-50 years', '+40 years', 'Asia/Ho_Chi_Minh'),
            'day_quit' => null,
            'image' => fake()->imageUrl(),
            'status' => fake()->boolean(1000),
        ];
    }
}
