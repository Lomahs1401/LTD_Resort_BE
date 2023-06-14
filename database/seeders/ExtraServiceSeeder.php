<?php

namespace Database\Seeders;

use App\Models\room\ExtraService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $extra_services_name = [
            'Snack Oishi', 'Snack Poca', 'Snack O\'star', 'Snack Swing', 'Snack Lay\'s',
            'Ly mì Modern lẩu Thái tôm 65g', 'Ly mì khoai tây Omachi tôm chua cay 112g', 
            'Lon nước ngọt coca 330ml', 'Lon nước ngọt 7up 330ml',
            'Nước cam Twister', 'Trà xanh không độ', 'Nước suối' 
        ];

        for ($i = 0; $i < count($extra_services_name); $i++) {
            ExtraService::factory()->create([
                'extra_service_name' => $extra_services_name[$i],
                'description' => fake()->sentence(),
                'image' => fake()->imageUrl(),
                'price' => 15000,
                'quantity' => 3,
            ]);
        }
    }
}
