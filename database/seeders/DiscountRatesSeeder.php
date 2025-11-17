<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountRatesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('discount_rates')->insert([
            ['id' => 1, 'discount_name' => 'No Discount', 'discount_value' => '0', 'created_at' => '2025-04-15 12:28:38'],
            ['id' => 2, 'discount_name' => 'Standard Discount', 'discount_value' => '10%', 'created_at' => '2025-04-15 12:28:38'],
            ['id' => 3, 'discount_name' => 'Special Offer', 'discount_value' => '100', 'created_at' => '2025-04-15 12:28:38'],
            ['id' => 4, 'discount_name' => 'Free', 'discount_value' => '100%', 'created_at' => '2025-04-15 14:21:12'],
        ]);
    }
}


