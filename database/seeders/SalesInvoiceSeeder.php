<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // discount_presets
        DB::table('discount_presets')->insert([
            ['id' => 1, 'name' => '', 'value' => '10.00', 'is_default' => 0],
        ]);

        // invoice
        DB::table('invoice')->insert([
            ['Invoice_Id' => 132, 'Customer_Name' => 'Justine Dimalanta', 'Customer_Email' => 'dimalantajustine8@gmail.com', 'Billing_Address' => 'La Union', 'Item_Name' => 'Nike Shoes', 'Price' => 100, 'Quantity' => 5, 'Discount' => '10', 'Tax' => 15, 'Terms' => '', 'invoice_mode' => 'VAT', 'tax_type_at_creation' => 'VAT (12%)', 'tax_id' => null, 'discount_id' => null],
            ['Invoice_Id' => 133, 'Customer_Name' => 'Miko Oliva', 'Customer_Email' => '202110207@gordoncollege.edu.ph', 'Billing_Address' => 'Meat and Eat', 'Item_Name' => 'Inventory', 'Price' => 100000, 'Quantity' => 1, 'Discount' => '5', 'Tax' => 1, 'Terms' => 'lopit', 'invoice_mode' => 'VAT', 'tax_type_at_creation' => 'VAT (12%)', 'tax_id' => null, 'discount_id' => null],
            ['Invoice_Id' => 134, 'Customer_Name' => 'Justine', 'Customer_Email' => '202110893@gordoncollege.edu.ph', 'Billing_Address' => 'baliw', 'Item_Name' => 'Keyboard', 'Price' => 150, 'Quantity' => 5, 'Discount' => '1', 'Tax' => 10, 'Terms' => 'ewanq', 'invoice_mode' => 'VAT', 'tax_type_at_creation' => 'VAT (12%)', 'tax_id' => null, 'discount_id' => null],
            ['Invoice_Id' => 136, 'Customer_Name' => 'Allen Lim', 'Customer_Email' => 'stafify@gmail.com', 'Billing_Address' => 'La Union', 'Item_Name' => 'Keyboard', 'Price' => 150, 'Quantity' => 5, 'Discount' => '10%', 'Tax' => 10, 'Terms' => 'test', 'invoice_mode' => 'VAT', 'tax_type_at_creation' => 'VAT (12%)', 'tax_id' => null, 'discount_id' => null],
            ['Invoice_Id' => 137, 'Customer_Name' => 'Justine Dimalanta', 'Customer_Email' => 'dimalantajustine8@gmail.com', 'Billing_Address' => 'La Union Street, Brgy. Barretto\r\nLa Union', 'Item_Name' => 'test', 'Price' => 123, 'Quantity' => 123, 'Discount' => '0', 'Tax' => 5, 'Terms' => 'awawea', 'invoice_mode' => 'NON-VAT', 'tax_type_at_creation' => 'Non-VAT (3%)', 'tax_id' => 3, 'discount_id' => 1],
        ]);
    }
}


