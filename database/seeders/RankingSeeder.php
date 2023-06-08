<?php

namespace Database\Seeders;

use App\Models\Ranking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_ranking = ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'];

        for ($i = 0; $i < count($list_ranking); $i++) {
            $ranking_name = $list_ranking[$i];

            $point_start = 0;
            switch ($ranking_name) {
                case 'Bronze':
                    $point_start = 0;
                    break;
                case 'Silver':
                    $point_start = 1000;
                    break;
                case 'Gold':
                    $point_start = 2500;
                    break;
                case 'Platinum':
                    $point_start = 5000;
                    break;
                case 'Diamond':
                    $point_start = 10000;
                    break;
                default:
                    # code...
                    break;
            }

            $discount = 0;
            if ($point_start >= 1000) {
                $discount = 0.05;
            } else if ($point_start >= 2500) {
                $discount = 0.1;
            } else if ($point_start >= 5000) {
                $discount = 0.15;
            }else if ($point_start >= 10000) {
                $discount = 0.2;
            }

            Ranking::factory()->create([
                'ranking_name' => $ranking_name,
                'point_start' => $point_start,
                'discount' => $discount,
            ]);
        }
    }
}
