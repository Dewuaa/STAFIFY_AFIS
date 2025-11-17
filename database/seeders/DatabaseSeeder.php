<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Lookups first
        $this->call([
            ActiveBanksPhSeeder::class,
            PaymentMethodsSeeder::class,
            ExpenseCategoriesSeeder::class,
            TaxRatesSeeder::class,
            DiscountRatesSeeder::class,
            TaxPresetsSeeder::class,
        ]);

        // Masters
        $this->call([
            MastersSeeder::class,
        ]);

        // Finance (depends on lookups)
        $this->call([
            FinanceSeeder::class,
        ]);

        // Sales/Invoice
        $this->call([
            SalesInvoiceSeeder::class,
        ]);

        // Ops/Assets
        $this->call([
            OpsAssetsSeeder::class,
        ]);

        // Misc
        $this->call([
            MiscSeeder::class,
        ]);
    }
}
