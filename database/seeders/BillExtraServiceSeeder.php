<?php

namespace Database\Seeders;

use App\Models\BillExtraService;
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
