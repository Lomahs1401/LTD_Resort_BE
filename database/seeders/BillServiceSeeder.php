<?php

namespace Database\Seeders;

use App\Models\BillService;
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
