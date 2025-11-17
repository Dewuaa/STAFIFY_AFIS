<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxRatesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tax_rates')->insert([
            ['id' => 1, 'tax_name' => 'No Tax', 'tax_rate' => 0.00],
            ['id' => 2, 'tax_name' => 'Standard VAT', 'tax_rate' => 12.00],
            ['id' => 3, 'tax_name' => 'Reduced VAT', 'tax_rate' => 5.00],
            ['id' => 4, 'tax_name' => 'try', 'tax_rate' => 1.00],
        ]);
    }
}


