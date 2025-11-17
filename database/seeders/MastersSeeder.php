<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MastersSeeder extends Seeder
{
    public function run(): void
    {
        // contacts
        DB::table('contacts')->insert([
            ['id' => 1, 'name' => 'John Doe', 'type' => 'client', 'email' => 'john@example.com', 'phone' => '09171234567'],
            ['id' => 2, 'name' => 'Jane Smith', 'type' => 'client', 'email' => 'jane@example.com', 'phone' => '09181234567'],
            ['id' => 3, 'name' => 'Acme Supplies', 'type' => 'vendor', 'email' => 'acme@supplies.com', 'phone' => '09221234567'],
            ['id' => 4, 'name' => 'Global Imports', 'type' => 'vendor', 'email' => 'global@imports.com', 'phone' => '09231234567'],
        ]);

        // suppliers
        DB::table('suppliers')->insert([
            ['id' => 1, 'numbering' => 1, 'supplier_type' => 'vendor', 'materials_type' => 'finished goods', 'name' => 'Justine Dimalanta', 'address' => 'La Union Street, Brgy. Barretto', 'email' => 'dimalantajustine8@gmail.com', 'contact' => '123', 'contact_person' => '123', 'bank_name' => '123123', 'bank_type' => '123', 'account_name' => '123', 'account_number' => '123'],
        ]);

        // customers
        DB::table('customers')->insert([
            ['id' => 1, 'name' => 'Test Customer', 'email' => 'test@example.com', 'location' => '123 Test Street, Testville', 'orders' => 5, 'spent' => 999.99],
            ['id' => 2, 'name' => 'Justine Dimalanta', 'email' => 'dimajustine0@gmail.com', 'location' => 'Olongapo', 'orders' => 100, 'spent' => 1.00],
            ['id' => 3, 'name' => 'Dowelle Mon', 'email' => 'mondowelle00@gmail.com', 'location' => 'Gabaya', 'orders' => 500, 'spent' => 1.00],
            ['id' => 4, 'name' => 'Miko Oliva', 'email' => '202110207@gordoncollege.edu.ph', 'location' => 'Meat and Eat', 'orders' => 5, 'spent' => 100.00],
        ]);

        // categories
        DB::table('categories')->insert([
            ['Category_Id' => 1234, 'Category' => 'Shoes'],
            ['Category_Id' => 1235, 'Category' => 'Hygiene'],
            ['Category_Id' => 1236, 'Category' => 'BAT AKO NATATAWA'],
            ['Category_Id' => 1237, 'Category' => 'XD'],
            ['Category_Id' => 1238, 'Category' => 'Furniture'],
            ['Category_Id' => 1239, 'Category' => 'testing'],
            ['Category_Id' => 1240, 'Category' => 'Furniture'],
            ['Category_Id' => 1241, 'Category' => 'Food'],
            ['Category_Id' => 1242, 'Category' => 'Technology'],
            ['Category_Id' => 1243, 'Category' => 'testing'],
            ['Category_Id' => 1244, 'Category' => 'ayaw'],
            ['Category_Id' => 1245, 'Category' => 'robot'],
            ['Category_Id' => 1246, 'Category' => 'Massage'],
        ]);

        // departments
        DB::table('departments')->insert([
            ['Department_Id' => 1234, 'Department' => 'Stafify'],
            ['Department_Id' => 1235, 'Department' => 'Sm Department'],
            ['Department_Id' => 1236, 'Department' => 'IT'],
            ['Department_Id' => 1237, 'Department' => 'Ningnangan'],
            ['Department_Id' => 1238, 'Department' => 'DOE'],
            ['Department_Id' => 1239, 'Department' => 'Spasify'],
        ]);

        // locations
        DB::table('locations')->insert([
            ['Location_Id' => 1, 'Location' => '0'],
            ['Location_Id' => 2, 'Location' => 'Subic'],
            ['Location_Id' => 3, 'Location' => 'Pampanga'],
            ['Location_Id' => 4, 'Location' => 'Barretto'],
            ['Location_Id' => 5, 'Location' => 'Sikret'],
            ['Location_Id' => 6, 'Location' => 'Olongapo'],
        ]);

        // areas
        DB::table('areas')->insert([
            ['Area_Id' => 1234, 'Area' => 'SBMA'],
            ['Area_Id' => 1235, 'Area' => 'Sbma'],
            ['Area_Id' => 1236, 'Area' => 'Remy'],
            ['Area_Id' => 1237, 'Area' => 'testing'],
            ['Area_Id' => 1238, 'Area' => 'CR'],
            ['Area_Id' => 1239, 'Area' => 'Dinalupihan'],
            ['Area_Id' => 1240, 'Area' => 'test'],
            ['Area_Id' => 1241, 'Area' => 'test'],
            ['Area_Id' => 1242, 'Area' => 'area 51'],
        ]);
    }
}


