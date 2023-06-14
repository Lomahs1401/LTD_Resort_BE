<?php

namespace Database\Seeders;

use App\Models\service\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_service_types_name = [
            'Dịch vụ ăn uống', 
            'Dịch vụ vui chơi giải trí', 
            'Dịch vụ tiệc cưới', 
            'Dịch vụ chăm sóc sức khỏe', 
            'Dịch vụ du lịch'
        ];
        
        for ($i = 0; $i < count($list_service_types_name); $i++) {
            ServiceType::factory()->create([
                'service_type_name' => $list_service_types_name[$i]
            ]);
        }
    }
}
