<?php

namespace Database\Seeders;

use App\Models\room\Equipment;
use App\Models\room\EquipmentRoomType;
use App\Models\room\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentRoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment_model = new Equipment();
        $room_type_model = new RoomType();

        $list_room_types = $room_type_model->newQuery()->get();
        $list_equipments = $equipment_model->newQuery()->get('id');

        define('NUMBER_EQUIPMENT_OF_ROOM_TYPE', 5);

        for ($i = 0; $i < count($list_room_types); $i++) {
            for ($j = 0; $j < NUMBER_EQUIPMENT_OF_ROOM_TYPE; $j++)
            EquipmentRoomType::factory()->create([
                'room_type_id' => $list_room_types[$i]->id,
                'equipment_id' => fake()->randomElement($list_equipments)
            ]);
        }
    }
}
