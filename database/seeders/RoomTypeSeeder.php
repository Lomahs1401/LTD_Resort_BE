<?php

namespace Database\Seeders;

use App\Models\room\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bedroom_types = ['Single Bedroom', 'Twin Bedroom', 'Double Bedroom', 'Triple Bedroom', 'Quad Bedroom'];
        $room_types = ['Superior Room', 'Deluxe Room', 'Executive Room', 'Suite Room'];

        $bedroom_types_price = [200000, 240000, 250000, 300000, 350000];
        $room_types_price = [1.2, 1.4, 2, 2.5];
        $room_sizes = [20, 25, 30, 35, 40];
        $number_customers = [1, 2, 2, 3, 4];
        $list_point_rankings = [200, 250, 300, 350, 400];
        $room_type_count = 1;

        for ($i = 0; $i < count($bedroom_types); $i++) {
            for ($j = 0; $j < count($room_types); $j++) {
                RoomType::factory()->create([
                    'room_type_name' => $bedroom_types[$i] . ' - ' . $room_types[$j],
                    'room_size' => $room_sizes[$i],
                    'number_customers' => $number_customers[$i],
                    'description' => fake()->paragraph(20),
                    'image' => 'gs://ltd-resort.appspot.com/room-types/' . $room_type_count . '/',
                    'price' => $bedroom_types_price[$i] * $room_types_price[$j],
                    'point_ranking' => $list_point_rankings[$i]
                ]);
                $room_type_count++;
            }
        }
    }
}
