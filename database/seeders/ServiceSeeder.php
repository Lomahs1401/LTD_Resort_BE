<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service_type_model = new ServiceType();
        $list_service_types_id = $service_type_model->newQuery()->get(['id']);

        $list_food_services = [
            'Restaurants', 'Pool bar'
        ];

        $list_entertainment_services = [
            'Swimming Pool', 'Golf Course', 'Tennis'
        ];


        $list_wedding_party_services = [
            'Conference Room', 'Outdoor Party'
        ];

        $list_health_care_services = [
            'Gym', 'Yoga', 'Spa'
        ];

        $list_local_travel_services = [
            'Visit Local Attractions', 'Bicycle Rental Service'
        ];

        $service_count = 1;

        for ($i = 0; $i < count($list_service_types_id); $i++) {
            switch($list_service_types_id[$i]->id) {
                case 1: 
                    for ($j = 0; $j < count($list_food_services); $j++) {
                        Service::factory()->create([
                            'service_name' => $list_food_services[$j],
                            'image' => 'gs://ltd-resort.appspot.com/services/' . $service_count . '/',
                            'description' => fake()->sentence(20),
                            'status' => 'AVAILABLE',
                            'price' => 200000,
                            'point_ranking' => 200,
                            'service_type_id' => $list_service_types_id[$i]->id
                        ]);
                        $service_count++;
                    }
                    break;
                case 2:
                    for ($j = 0; $j < count($list_entertainment_services); $j++) {
                        Service::factory()->create([
                            'service_name' => $list_entertainment_services[$j],
                            'image' => 'gs://ltd-resort.appspot.com/services/' . $service_count . '/',
                            'description' => fake()->sentence(20),
                            'status' => 'AVAILABLE',
                            'price' => 250000,
                            'point_ranking' => 250,
                            'service_type_id' => $list_service_types_id[$i]->id
                        ]);
                        $service_count++;
                    }
                    break;
                case 3:
                    for ($j = 0; $j < count($list_wedding_party_services); $j++) {
                        Service::factory()->create([
                            'service_name' => $list_wedding_party_services[$j],
                            'image' => 'gs://ltd-resort.appspot.com/services/' . $service_count . '/',
                            'description' => fake()->sentence(20),
                            'status' => 'AVAILABLE',
                            'price' => 500000,
                            'point_ranking' => 300,
                            'service_type_id' => $list_service_types_id[$i]->id
                        ]);
                        $service_count++;
                    }
                    break;
                case 4:
                    for ($j = 0; $j < count($list_health_care_services); $j++) {
                        Service::factory()->create([
                            'service_name' => $list_health_care_services[$j],
                            'image' => 'gs://ltd-resort.appspot.com/services/' . $service_count . '/',
                            'description' => fake()->sentence(20),
                            'status' => 'AVAILABLE',
                            'price' => 300000,
                            'point_ranking' => 250,
                            'service_type_id' => $list_service_types_id[$i]->id
                        ]);
                        $service_count++;
                    }
                    break;
                case 5:
                    for ($j = 0; $j < count($list_local_travel_services); $j++) {
                        Service::factory()->create([
                            'service_name' => $list_local_travel_services[$j],
                            'image' => 'gs://ltd-resort.appspot.com/services/' . $service_count . '/',
                            'description' => fake()->sentence(20),
                            'status' => 'AVAILABLE',
                            'price' => 400000,
                            'point_ranking' => 300,
                            'service_type_id' => $list_service_types_id[$i]->id
                        ]);
                        $service_count++;
                    }
                    break;
                default:
                    break;
            }
        }
    }
}
