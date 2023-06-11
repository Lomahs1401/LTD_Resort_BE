<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department_model = new Department();
        $department_ids = $department_model->newQuery()->get(['id']);

        // Bộ phận tiền sảnh
        $lobby_positions = [
            'Nhân viên lễ  tân', 'Giám sát lễ tân', 'Nhân viên đặt phòng', 'Giám sát bộ phận đặt phòng',
            'Nhân viên thu ngân', 'Nhân viên hỗ trợ khách hàng', 'Trưởng bộ phận hỗ trợ khách hàng',
            'Nhân viên chăm sóc khách hàng',  'Nhân viên tiền sảnh',
         
        ];
        $lobby_positions_admin =['Giám đốc tiền sảnh',  'Giám đốc tiền sảnh ban đêm',
        'Giám đốc bộ phận lễ tân', 'Giám đốc bộ phận chăm sóc khách hàng'

        ];

        // Bộ phận buồng phòng
        $room_positions = [
            'Nhân viên làm phòng', 'Nhân viên giặt là',  'Giám sát phòng vải',
            'Nhân viên trông trẻ',  'Giám sát vệ sinh khu vực công cộng'
        ];

        $room_positions_admin = ['Giám đốc Buồng phòng'];

        // Bộ phận ẩm thực
        $chef_positions = [
            'Nhân viên phục vụ', 'Nhân viên tiệc', 'Nhân viên pha chế rượu',
            'Nhân viên pha chế cafe', 'Nhân viên chạy món', 'Lễ tân nhà hàng', 'Giám sát bộ phận tạp vụ',
            'Bếp trưởng bộ phận', 'Bếp phó', 'Bếp trưởng điều hành', 'Giám sát tổ phục vụ tiệc và pha chế',
        
        ];  
        $chef_positions_admin = [
            'Giám đốc bộ phận ẩm thực', 
            'Giám đốc bộ phận tiệc'
        ];  
        // Bộ phận kinh doanh - tiếp thị
        $sales_marketing_positions = [
            'Nhân viên Marketing', 'Nhân viên sales khách công ty', 'Nhân viên sales khách tour',
            'Nhân viên sales trên Internet', 'Nhân viên sales nhà hàng và tiệc', 'Nhân viên thiết kế đồ hoạ',
          
        ];
        $sales_marketing_positions_admin = [
            
            'Giám đốc tiếp thị truyền thông'
        ];

        // Bộ phận Tài chính - Kế toán
        $finance_positions = [
            'Nhân viên kế toán tổng hợp', 'Nhân viên kế toán công nợ', 'Nhân viên kế toán nội bộ',
            'Nhân viên thủ quỹ', 'Nhân viên thu mua', 'Nhân viên nhận hàng',
            'Nhân viên giữ kho', 'Nhân viên kiểm soát chi phí',
           
        ];
        $finance_positions_admin = [
            'Giám đốc kinh doanh', 'Giám đốc tài chính, kế toán',
        ];

        // Bộ phận Hành chính - Nhân sự
        $personnel_admin_positions = [
            
            'Nhân viên lương/ bảo hiểm', 'Nhân viên pháp lý',
        ];
        $personnel_positions_admin = [
            'Giám đốc bộ phận hành chính và nhân sự'
            
        ];


        // Bộ phận Kỹ thuật
        $engineers_positions = [
            'Nhân viên điện', 'Nhân viên nước', 'Nhân viên mộc',
            'Nhân viên cứu hộ', 'Giám sát bộ phận Kỹ thuật'
        ];
        $engineers_positions_admin = [
             'Giám đốc bộ phận IT'
        ];

        for ($i = 0; $i < count($lobby_positions); $i++) {
            Position::factory()->create([
                'position_name' => $lobby_positions[$i],
                'permission' => 1,
                'department_id' => $department_ids[0],
            ]);
        }
        for ($i = 0; $i < count($lobby_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $lobby_positions_admin[$i],
                'permission' => 2,
                'department_id' => $department_ids[0],
            ]);
        }

        for ($i = 0; $i < count($room_positions); $i++) {
            Position::factory()->create([
                'position_name' => $room_positions[$i],
                'permission' => 0,
                'department_id' => $department_ids[1],
            ]);
        }
        for ($i = 0; $i < count($room_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $room_positions_admin[$i],
                'permission' => 2,
                'department_id' => $department_ids[1],
            ]);
        }
        // fake()->randomElement([1, 2, 3])
        for ($i = 0; $i < count($chef_positions); $i++) {
            Position::factory()->create([
                'position_name' => $chef_positions[$i],
                'permission' => fake()->randomElement([0,1]),
                'department_id' => $department_ids[2],
            ]);
        }
        for ($i = 0; $i < count($chef_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $chef_positions_admin[$i],
                'permission' => 2,
                'department_id' => $department_ids[2],
            ]);
        }
        for ($i = 0; $i < count($sales_marketing_positions); $i++) {
            Position::factory()->create([
                'position_name' => $sales_marketing_positions[$i],
                'permission' => fake()->randomElement([0,1]),
                'department_id' => $department_ids[3],
            ]);
        }
        for ($i = 0; $i < count($sales_marketing_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $sales_marketing_positions_admin[$i],
                'permission' => 2,
                'department_id' => $department_ids[3],
            ]);
        }
        for ($i = 0; $i < count($finance_positions); $i++) {
            Position::factory()->create([
                'position_name' => $finance_positions[$i],
                'permission' =>  fake()->randomElement([1,2]),
                'department_id' => $department_ids[4],
            ]);
        }
        for ($i = 0; $i < count($finance_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' =>$finance_positions_admin[$i],
                'permission' => 2,
                'department_id' => $department_ids[4],
            ]);
        }
        for ($i = 0; $i < count($personnel_admin_positions); $i++) {
            Position::factory()->create([
                'position_name' => $personnel_admin_positions[$i],
                'permission' => 0,
                'department_id' => $department_ids[5],
            ]);
        }
        for ($i = 0; $i < count($personnel_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $personnel_admin_positions[$i],
                'permission' => 2,
                'department_id' => $department_ids[5],
            ]);
        }
        for ($i = 0; $i < count($engineers_positions); $i++) {
            Position::factory()->create([
                'position_name' => $engineers_positions[$i],
                'permission' => fake()->randomElement([0,1]), 
                'department_id' => $department_ids[6],
            ]);
        }
        for ($i = 0; $i < count($engineers_positions_admin); $i++) {
            Position::factory()->create([
                'position_name' => $engineers_positions[$i],
                'permission' => 2,
                'department_id' => $department_ids[6],
            ]);
        }
    }
}
