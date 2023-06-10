<?php

namespace Database\Seeders\room;

use App\Models\room\BillExtraService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillExtraServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BillExtraService::factory(20)->create();
    }
}
