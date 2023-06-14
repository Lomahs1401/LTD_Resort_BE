<?php

namespace Database\Seeders;

use App\Models\BillRoom;
use Illuminate\Database\Seeder;

class BillRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BillRoom::factory(30)->create();
    }
}
