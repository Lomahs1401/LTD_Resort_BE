<?php

namespace Database\Seeders;

use App\Models\Ranking;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account_model = new Account();
        $customer_accounts = $account_model->newQuery()->join('roles', 'accounts.role_id', '=', 'roles.id')->where('role_name', '=', 'ROLE_CUSTOMER')->get(['accounts.id']);

        $ranking_model = new Ranking();
        $list_rankings = $ranking_model->newQuery()->get('id');

        for ($i = 0; $i < count($customer_accounts); $i++) {
            Customer::factory()->create([
                'account_id' => $customer_accounts[$i]->id,
                'ranking_id' => fake()->randomElement($list_rankings),
            ]);
        }
    }
}
