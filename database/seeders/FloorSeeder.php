<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        define('NUMBER_OF_FLOORS', 8);

        for ($i = 0; $i < NUMBER_OF_FLOORS; $i++) {
            Floor::factory()->create([
                'floor_name' => 'Floor' . ' ' . $i + 1
            ]);
        }
    }
}
