<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_equipments = [
            'Tủ lạnh', 'Máy lạnh', 'Máy pha cà phê', 'Máy sấy tóc', 'Máy ép trái cây',
            'Bàn ghế', 'Quầy rượu', 'Tủ gỗ',
            'Khăn trải bàn', 'Rèm', 'Thảm',
            'Xe đẩy', 'Khay', 'Chén đĩa', 'Ly tách', 'Dao nĩa',
            'Chổi', 'Máy hút bụi',
            'Tranh ảnh', 'Lọ hoa'
        ];
        
        for ($i = 0; $i < count($list_equipments); $i++) {
            Equipment::factory()->create([
                'equipment_name' => $list_equipments[$i]
            ]);
        }
    }
}
