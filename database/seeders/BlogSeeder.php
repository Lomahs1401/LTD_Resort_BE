<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee_model = new Employee();
        $list_employees = $employee_model->newQuery()->get();

        for ($i = 0; $i < count($list_employees); $i++) {
            Blog::factory(5)->create([
                'employee_id' => $list_employees[$i]->id
            ]);
        }
    }
}
