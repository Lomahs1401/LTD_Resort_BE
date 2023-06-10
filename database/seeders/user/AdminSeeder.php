<?php

namespace Database\Seeders\user;

use App\Models\Position;
use App\Models\user\Account;
use App\Models\user\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account_model = new Account();
        $admin_accounts = $account_model->newQuery()->join('roles', 'accounts.role_id', '=', 'roles.id')->where('role_name', '=', 'ROLE_ADMIN')->get(['accounts.id']);
        
        $position_model = new Position();
        $list_positions = $position_model->newQuery()
        ->where('permission', '=', 2)->get('id');
        
        for ($i = 0; $i < count($admin_accounts); $i++) {
            Admin::factory()->create([
                'account_id' => $admin_accounts[$i]->id,
                'position_id' => fake()->randomElement($list_positions),
            ]);
        }
    }
}
