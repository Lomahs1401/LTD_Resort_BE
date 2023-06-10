<?php

namespace Database\Seeders\room;

use App\Models\service\BillService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BillService::factory(30)->create();
    }
}
