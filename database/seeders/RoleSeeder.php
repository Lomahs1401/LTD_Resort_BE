<?php

namespace Database\Seeders;

use App\Models\user\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ALL_ROLES = ['ROLE_ADMIN', 'ROLE_EMPLOYEE', 'ROLE_CUSTOMER'];

        for ($i = 0; $i < count($ALL_ROLES); $i++) {
            Role::factory()->create([
                'role_name' => $ALL_ROLES[$i],
            ]);
        }
    }
}
