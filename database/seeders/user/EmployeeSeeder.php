<?php

namespace Database\Seeders\user;

use App\Models\Department;
use App\Models\Position;
use App\Models\user\Account;
use App\Models\user\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account_model = new Account();
        $employee_accounts = $account_model->newQuery()->join('roles', 'accounts.role_id', '=', 'roles.id')->where('role_name', '=', 'ROLE_EMPLOYEE')->get(['accounts.id']);

        $position_model = new Position();
        $list_positions = $position_model->newQuery()
            ->where('permission',  1)
            ->get('id');

       
            for ($i = 0; $i < count($employee_accounts); $i++) {
                Employee::factory()->create([
                    'account_id' => $employee_accounts[$i]->id,
                    'position_id' => fake()->randomElement($list_positions),
                ]);
            }
        
    }
}
