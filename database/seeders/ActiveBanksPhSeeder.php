<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActiveBanksPhSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('active_banks_ph')->upsert([
            ['id' => 1, 'bank_name' => 'BDO Unibank, Inc.', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 2, 'bank_name' => 'Land Bank of the Philippines', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 3, 'bank_name' => 'Metrobank', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 4, 'bank_name' => 'Bank of the Philippine Islands', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 5, 'bank_name' => 'China Banking Corporation', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 6, 'bank_name' => 'RCBC', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 7, 'bank_name' => 'Philippine National Bank', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 8, 'bank_name' => 'Security Bank Corporation', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 9, 'bank_name' => 'Union Bank of the Philippines', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 10, 'bank_name' => 'Development Bank of the Philippines', 'bank_type' => 'Universal/Commercial', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 11, 'bank_name' => 'Maya Bank, Inc.', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 12, 'bank_name' => 'UnionDigital Bank', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 13, 'bank_name' => 'GoTyme Bank Corporation', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 14, 'bank_name' => 'Tonik Digital Bank, Inc.', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 15, 'bank_name' => 'UNObank, Inc.', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 16, 'bank_name' => 'Overseas Filipino Bank (OFBank)', 'bank_type' => 'Digital', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 17, 'bank_name' => 'Philippine Savings Bank', 'bank_type' => 'Thrift', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 18, 'bank_name' => 'Philippine Business Bank', 'bank_type' => 'Thrift', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 19, 'bank_name' => 'City Savings Bank', 'bank_type' => 'Thrift', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 20, 'bank_name' => 'China Bank Savings', 'bank_type' => 'Thrift', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 21, 'bank_name' => 'Sterling Bank of Asia', 'bank_type' => 'Thrift', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 22, 'bank_name' => 'BDO Network Bank', 'bank_type' => 'Rural/Cooperative', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 23, 'bank_name' => 'EastWest Rural Bank', 'bank_type' => 'Rural/Cooperative', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 24, 'bank_name' => 'CARD Bank, Inc.', 'bank_type' => 'Rural/Cooperative', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 25, 'bank_name' => 'Seabank Philippines, Inc.', 'bank_type' => 'Rural/Cooperative', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
            ['id' => 26, 'bank_name' => 'Cantilan Bank, Inc.', 'bank_type' => 'Rural/Cooperative', 'status' => 'Active', 'notes' => null, 'created_at' => '2025-05-09 08:44:45'],
        ], ['id'], ['bank_name','bank_type','status','notes','created_at']);
    }
}


