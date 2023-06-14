<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department_names = [
            'Bộ phận Tiền sảnh', 'Bộ phận Buồng phòng', 'Bộ phận Ẩm thực', 'Bộ phận Kinh doanh - Tiếp thị',
            'Bộ phận Tài chính - Kế toán', 'Bộ phận Hành chính - Nhân sự', 'Bộ phận Kỹ thuật', 
        ];

        for ($i = 0; $i < count($department_names); $i++) { 
            Department::factory()->create([
                'department_name' => $department_names[$i]
            ]);
        }
    }
}
