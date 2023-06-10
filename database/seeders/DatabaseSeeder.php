<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\room\AreaSeeder;
use Database\Seeders\room\BillExtraServiceSeeder;
use Database\Seeders\room\BillRoomSeeder;
use Database\Seeders\room\BillServiceSeeder;
use Database\Seeders\room\EquipmentRoomTypeSeeder;
use Database\Seeders\room\EquipmentSeeder;
use Database\Seeders\room\ExtraServiceDetailSeeder;
use Database\Seeders\room\ExtraServiceSeeder;
use Database\Seeders\room\FloorSeeder;
use Database\Seeders\room\ReservationRoomSeeder;
use Database\Seeders\room\RoomSeeder;
use Database\Seeders\room\RoomTypeSeeder;
use Database\Seeders\service\ServiceSeeder;
use Database\Seeders\service\ServiceTypeSeeder;
use Database\Seeders\user\AccountSeeder;
use Database\Seeders\user\AdminSeeder;
use Database\Seeders\user\EmployeeSeeder;
use Database\Seeders\user\CustomerSeeder;
use Database\Seeders\user\RoleSeeder;
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
