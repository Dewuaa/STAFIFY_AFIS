<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('expense_categories')->insert([
            ['id' => 1, 'category_name' => 'Office Supplies', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 2, 'category_name' => 'Transportation', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 3, 'category_name' => 'Meals and Entertainment', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 4, 'category_name' => 'Utilities', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 5, 'category_name' => 'Postage and Shipping', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 6, 'category_name' => 'Repairs and Maintenance', 'created_at' => '2025-05-09 03:56:23'],
            ['id' => 7, 'category_name' => 'Miscellaneous', 'created_at' => '2025-05-09 03:56:23'],
        ]);
    }
}


