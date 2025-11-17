<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            ['id' => 1, 'method_name' => 'Cash', 'created_at' => '2025-05-09 03:55:52'],
            ['id' => 2, 'method_name' => 'Check', 'created_at' => '2025-05-09 03:55:52'],
            ['id' => 3, 'method_name' => 'Bank Transfer', 'created_at' => '2025-05-09 03:55:52'],
            ['id' => 4, 'method_name' => 'Credit Card', 'created_at' => '2025-05-09 03:55:52'],
            ['id' => 5, 'method_name' => 'Debit Card', 'created_at' => '2025-05-09 03:55:52'],
            ['id' => 6, 'method_name' => 'Mobile Payment', 'created_at' => '2025-05-09 03:55:52'],
        ]);
    }
}


