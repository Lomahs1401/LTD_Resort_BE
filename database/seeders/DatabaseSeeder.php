<?php

namespace Database\Seeders;

use Database\Seeders\AreaSeeder;
use Database\Seeders\BillExtraServiceSeeder;
use Database\Seeders\BillRoomSeeder;
use Database\Seeders\BillServiceSeeder;
use Database\Seeders\EquipmentRoomTypeSeeder;
use Database\Seeders\EquipmentSeeder;
use Database\Seeders\ExtraServiceDetailSeeder;
use Database\Seeders\ExtraServiceSeeder;
use Database\Seeders\FloorSeeder;
use Database\Seeders\ReservationRoomSeeder;
use Database\Seeders\RoomSeeder;
use Database\Seeders\RoomTypeSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\ServiceTypeSeeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\CustomerSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(RoleSeeder::class);  
        $this->call(RankingSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(FloorSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(RoomTypeSeeder::class);
        $this->call(ExtraServiceSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(ExtraServiceDetailSeeder::class);
        $this->call(EquipmentRoomTypeSeeder::class);
        $this->call(BillRoomSeeder::class);
        $this->call(BillServiceSeeder::class);
        $this->call(BillExtraServiceSeeder::class);
        $this->call(ReservationRoomSeeder::class);
    }
}
