<?php

namespace Database\Seeders;

use App\Models\room\ExtraService;
use App\Models\room\ExtraServiceDetail;
use App\Models\room\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraServiceDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room_type_model = new RoomType();
        $extra_service_model = new ExtraService();

        $list_room_types = $room_type_model->newQuery()->get();
        $list_extra_services = $extra_service_model->newQuery()->get('id');

        define('NUMBER_EXTRA_SERVICES_OF_ROOM_TYPE', 5);

        for ($i = 0; $i < count($list_room_types); $i++) {
            for ($j = 0; $j < NUMBER_EXTRA_SERVICES_OF_ROOM_TYPE; $j++)
            ExtraServiceDetail::factory()->create([
                'room_type_id' => $list_room_types[$i]->id,
                'extra_service_id' => fake()->randomElement($list_extra_services)
            ]);
        }
    }
}
