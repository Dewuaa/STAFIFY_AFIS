<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxPresetsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tax_presets')->insert([
            ['id' => 1, 'name' => '', 'rate' => 10.00, 'is_default' => 0],
        ]);
    }
}


