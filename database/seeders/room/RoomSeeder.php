<?php

namespace Database\Seeders\room;

use App\Models\room\Room;
use App\Models\Feedback;
use App\Models\room\Area;
use App\Models\room\Floor;
use App\Models\room\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area_model = new Area();
        $floor_model = new Floor();
        $room_type_model = new RoomType();

        $list_areas = $area_model->newQuery()->get();
        $list_floors = $floor_model->newQuery()->get();
        $list_room_types_id = $room_type_model->newQuery()->get();
        $number_room_in_floor = [1, 2, 3];
       
        for($i = 0; $i < count($list_areas); $i++) {
            $areas_zone = substr($list_areas[$i]->area_name, -1);
            for ($j = 0; $j < count($list_floors); $j++) {
                $m =0;
                for ($k = 0; $k < count($list_room_types_id); $k++) {
                    $number_rooms = fake()->randomElement($number_room_in_floor);
                    
                    for ($z = 0; $z < $number_rooms; $z++) {
                        if($m<9){
                        Room::factory()->create([
                            'room_name' => $areas_zone . $list_floors[$j]->id . '0' . ($m + 1),
                            'status' => 0,
                            'room_type_id' => $k + 1,
                            'area_id' => $list_areas[$i]->id,
                            'floor_id' => $list_floors[$j]->id
                        ]);}
                        else {
                            Room::factory()->create([
                                'room_name' => $areas_zone . $list_floors[$j]->id .  ($m + 1),
                                'status' => 0,
                                'room_type_id' => $k + 1,
                                'area_id' => $list_areas[$i]->id,
                                'floor_id' => $list_floors[$j]->id
                            ]);}
                            $m += 1;
                        }
                        
                    }
                    
                }
            }
        }
    }

