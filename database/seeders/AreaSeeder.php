<?php

namespace Database\Seeders;

use App\Models\room\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas_zone = ['A', 'B', 'C'];

        define('NUMBER_OF_AREAS', count($areas_zone));
        
        for ($i = 0; $i < NUMBER_OF_AREAS; $i++) {
            Area::factory()->create([
                'area_name' => 'Area' . ' ' . $areas_zone[$i]
            ]);
        }
    }
}
