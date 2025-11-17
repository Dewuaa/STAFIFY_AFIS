<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // bank_accounts
        DB::table('bank_accounts')->insert([
            ['id' => 5, 'bank_name' => 'BPI', 'account_name' => 'Stafify Holdings Inc.', 'account_no' => '1234-5678-9012', 'account_type' => 'Savings - PHP', 'currency' => 'PHP', 'created_at' => '2025-05-09 08:39:58'],
            ['id' => 6, 'bank_name' => 'BDO', 'account_name' => 'Stafify Treasury', 'account_no' => '2345-6789-0123', 'account_type' => 'Current - PHP', 'currency' => 'PHP', 'created_at' => '2025-05-09 08:39:58'],
            ['id' => 7, 'bank_name' => 'Metrobank', 'account_name' => 'Stafify Payroll', 'account_no' => '3456-7890-1234', 'account_type' => 'Savings - Dollar', 'currency' => 'USD', 'created_at' => '2025-05-09 08:39:58'],
            ['id' => 8, 'bank_name' => 'UnionBank', 'account_name' => 'Stafify Intl.', 'account_no' => '4567-8901-2345', 'account_type' => 'Current - Dollar', 'currency' => 'USD', 'created_at' => '2025-05-09 08:39:58'],
        ]);

        // bank_reco
        DB::table('bank_reco')->insert([
            ['id' => 3, 'account_name' => 'BDO Unibank, Inc. Account', 'account_number' => '10001', 'account_type' => 'Checking', 'bank_id' => 1, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 4, 'account_name' => 'Land Bank of the Philippines Account', 'account_number' => '10002', 'account_type' => 'Checking', 'bank_id' => 2, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 5, 'account_name' => 'Metrobank Account', 'account_number' => '10003', 'account_type' => 'Checking', 'bank_id' => 3, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 6, 'account_name' => 'Bank of the Philippine Islands Account', 'account_number' => '10004', 'account_type' => 'Checking', 'bank_id' => 4, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 7, 'account_name' => 'China Banking Corporation Account', 'account_number' => '10005', 'account_type' => 'Checking', 'bank_id' => 5, 'opening_balance' => 0.00, 'current_balance' => 678.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:12:00'],
            ['id' => 8, 'account_name' => 'RCBC Account', 'account_number' => '10006', 'account_type' => 'Checking', 'bank_id' => 6, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 9, 'account_name' => 'Philippine National Bank Account', 'account_number' => '10007', 'account_type' => 'Checking', 'bank_id' => 7, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 10, 'account_name' => 'Security Bank Corporation Account', 'account_number' => '10008', 'account_type' => 'Checking', 'bank_id' => 8, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 11, 'account_name' => 'Union Bank of the Philippines Account', 'account_number' => '10009', 'account_type' => 'Checking', 'bank_id' => 9, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 12, 'account_name' => 'Development Bank of the Philippines Account', 'account_number' => '100010', 'account_type' => 'Checking', 'bank_id' => 10, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 13, 'account_name' => 'Maya Bank, Inc. Account', 'account_number' => '100011', 'account_type' => 'Checking', 'bank_id' => 11, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 14, 'account_name' => 'UnionDigital Bank Account', 'account_number' => '100012', 'account_type' => 'Checking', 'bank_id' => 12, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 15, 'account_name' => 'GoTyme Bank Corporation Account', 'account_number' => '100013', 'account_type' => 'Checking', 'bank_id' => 13, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 16, 'account_name' => 'Tonik Digital Bank, Inc. Account', 'account_number' => '100014', 'account_type' => 'Checking', 'bank_id' => 14, 'opening_balance' => 0.00, 'current_balance' => 567.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:38'],
            ['id' => 17, 'account_name' => 'UNObank, Inc. Account', 'account_number' => '100015', 'account_type' => 'Checking', 'bank_id' => 15, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 18, 'account_name' => 'Overseas Filipino Bank (OFBank) Account', 'account_number' => '100016', 'account_type' => 'Checking', 'bank_id' => 16, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 19, 'account_name' => 'Philippine Savings Bank Account', 'account_number' => '100017', 'account_type' => 'Checking', 'bank_id' => 17, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 20, 'account_name' => 'Philippine Business Bank Account', 'account_number' => '100018', 'account_type' => 'Checking', 'bank_id' => 18, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 21, 'account_name' => 'City Savings Bank Account', 'account_number' => '100019', 'account_type' => 'Checking', 'bank_id' => 19, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 22, 'account_name' => 'China Bank Savings Account', 'account_number' => '100020', 'account_type' => 'Checking', 'bank_id' => 20, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 23, 'account_name' => 'Sterling Bank of Asia Account', 'account_number' => '100021', 'account_type' => 'Checking', 'bank_id' => 21, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 24, 'account_name' => 'BDO Network Bank Account', 'account_number' => '100022', 'account_type' => 'Checking', 'bank_id' => 22, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 25, 'account_name' => 'EastWest Rural Bank Account', 'account_number' => '100023', 'account_type' => 'Checking', 'bank_id' => 23, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
            ['id' => 26, 'account_name' => 'Cantilan Bank, Inc. Account', 'account_number' => '100026', 'account_type' => 'Checking', 'bank_id' => 26, 'opening_balance' => 0.00, 'current_balance' => 0.00, 'last_reconciled_date' => null, 'created_at' => '2025-08-18 06:11:23', 'updated_at' => '2025-08-18 06:11:23'],
        ]);

        // transactions
        DB::table('transactions')->insert([
            ['id' => 19, 'date' => '2025-08-18', 'reference_number' => '123', 'description' => 'test', 'amount' => 567.00, 'transaction_type' => 'deposit', 'method' => 'Check', 'payee' => null, 'bank_account_id' => 16, 'status' => 'pending', 'adjustment_type' => null, 'created_at' => '2025-08-18 06:11:38', 'transaction_date' => '2025-08-18'],
            ['id' => 20, 'date' => '2025-08-18', 'reference_number' => '788', 'description' => 'pay', 'amount' => 678.00, 'transaction_type' => 'deposit', 'method' => 'Bank transfer', 'payee' => null, 'bank_account_id' => 7, 'status' => 'pending', 'adjustment_type' => null, 'created_at' => '2025-08-18 06:12:00', 'transaction_date' => '2025-08-18'],
        ]);
    }
}


