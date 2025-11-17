<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiscSeeder extends Seeder
{
    public function run(): void
    {
        // acknowledgment_receipt
        DB::table('acknowledgment_receipt')->insert([
            ['Receipt_Id' => 2, 'Customer_Name' => 'Justine Dimalanta', 'Customer_Email' => 'dimalantajustine8@gmail.com', 'contact_number' => '12312312', 'Address' => 'La Union Street, Brgy. Barretto\r\nLa Union', 'purpose_type' => 'items', 'Payment_For' => null, 'items_received' => 'ewan', 'location' => 'Olongapo', 'Amount' => 123.00, 'payment_status' => 'partial', 'Payment_Method_Id' => 2, 'Reference_Number' => '123', 'Notes' => '123', 'created_at' => '2025-08-08 10:30:07', 'is_signed' => 0, 'signature_token' => null, 'signature_date' => null, 'signature_ip' => null],
            ['Receipt_Id' => 3, 'Customer_Name' => 'Justine Dimalanta', 'Customer_Email' => 'dimalantajustine8@gmail.com', 'contact_number' => '09616219737', 'Address' => 'La Union Street, Brgy. Barretto\r\nLa Union', 'purpose_type' => 'items', 'Payment_For' => null, 'items_received' => 'test', 'location' => 'Mayao Crossing, Lucena, 2nd District, Calabarzon, 4301, Philippines', 'Amount' => 123.00, 'payment_status' => 'full', 'Payment_Method_Id' => 2, 'Reference_Number' => '46456', 'Notes' => 'test', 'created_at' => '2025-08-18 06:00:06', 'is_signed' => 0, 'signature_token' => null, 'signature_date' => null, 'signature_ip' => null],
        ]);

        // petty_cash_voucher
        DB::table('petty_cash_voucher')->insert([
            ['voucher_id' => 3, 'voucher_number' => '20250808-001', 'date_issued' => '2025-08-07', 'payee_name' => 'Justine', 'payee_email' => 'dimalantajustine8@gmail.com', 'contact_number' => '09616219737', 'department' => 'test', 'position' => 'test', 'purpose' => 'testing', 'amount' => 123.00, 'category_id' => 5, 'payment_method' => 'Bank Transfer', 'approved_by' => 'me', 'received_by' => 'me', 'receipt_attached' => 0, 'notes' => 'test', 'status' => 'pending', 'created_at' => '2025-08-08 10:32:07', 'is_signed' => 0, 'signature_token' => null, 'signature_date' => null, 'signature_ip' => null, 'signature_image' => null],
            ['voucher_id' => 5, 'voucher_number' => '20251010-001', 'date_issued' => '2025-10-10', 'payee_name' => 'Justine', 'payee_email' => 'dimalantajustine8@gmail.com', 'contact_number' => '09616219737', 'department' => 'asd', 'position' => 'asd', 'purpose' => 'asd', 'amount' => 213.00, 'category_id' => 1, 'payment_method' => 'Bank Transfer', 'approved_by' => 'asd', 'received_by' => 'da', 'receipt_attached' => 1, 'notes' => 'sdada', 'status' => 'pending', 'created_at' => '2025-10-10 06:47:49', 'is_signed' => 1, 'signature_token' => 'a438462a2ff91970b8879d2968254b98', 'signature_date' => '2025-10-10 08:49:52', 'signature_ip' => '127.0.0.1', 'signature_image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAxUAAAFMCAYAAAC9A5EcAAAQAElEQVR4A...'],
        ]);

        // order_approvals (no rows in dump)

        // sales_toolkit
        DB::table('sales_toolkit')->insert([
            ['sales_id' => 3, 'user_id' => 125, 'sales_title' => 'asd', 'form_url' => '', 'response_url' => 'https://docs.google.com/spreadsheets/d/1_gr7kXx3t6JxY_QxV_fX8_zWC1D8WC5yf8NOCMDShjQ/edit?gid=0#gid=0', 'icon' => 'communication.gif', 'type' => 'Sheet', 'is_approved' => 0, 'created_at' => '2025-10-10 05:26:29', 'updated_at' => '2025-10-10 05:26:29'],
            ['sales_id' => 4, 'user_id' => 125, 'sales_title' => 'asd', 'form_url' => '', 'response_url' => 'https://docs.google.com/spreadsheets/d/1_gr7kXx3t6JxY_QxV_fX8_zWC1D8WC5yf8NOCMDShjQ/edit?gid=0#gid=0', 'icon' => 'communication.gif', 'type' => 'Sheet', 'is_approved' => 0, 'created_at' => '2025-10-10 05:26:31', 'updated_at' => '2025-10-10 05:26:31'],
        ]);

        // business_settings
        DB::table('business_settings')->insert([
            ['id' => 1, 'business_legal_name' => 'Stafify BPO', 'trade_name' => 'Stafify', 'registration_type' => 'SEC - Partnership', 'registration_no' => '23141234', 'date_of_registration' => '2025-10-14', 'industry_code' => '234', 'business_description' => 'wqer', 'business_tin' => '123123123123333', 'rdo_code' => '344', 'official_address' => "La Union Street, Brgy. Barretto\nLa Union", 'zip_code' => '2200', 'contact_phone' => '+639616219737', 'official_email' => 'dimalantajustine8@gmail.com', 'sss_no' => null, 'phic_no' => null, 'hdmf_no' => null, 'peza_cert_no' => null, 'permits' => '123123', 'enable_multi_branch' => 0, 'inventory_tracking_mode' => 'Perpetual', 'use_weighted_avg_cost' => 1, 'enable_audit_trail' => 1, 'tax_type' => 'Non-VAT (3%)', 'books_of_accounts' => 'Non-computerized', 'accounting_method' => 'Accrual', 'fiscal_start_month' => 'February', 'quarter_cutoff' => 'Calendar Quarter', 'withholding_agent' => 1, 'currency' => 'USD', 'timezone' => 'America/New_York (UTC-5)', 'week_start' => 'Monday', 'date_format' => 'DD-MM-YYYY', 'number_format' => '1 234,56', 'or_prefix' => 'OR- 123123', 'si_prefix' => 'SI- 232132', 'next_or_number' => 2003, 'next_si_number' => 6006, 'pdf_template' => 'Minimal', 'created_at' => '2025-08-13 17:44:36', 'updated_at' => '2025-10-14 07:52:47', 'last_updated' => '2025-10-14 07:52:47'],
        ]);

        // chart_of_accounts (subset due to volume; includes all from dump)
        DB::table('chart_of_accounts')->insert([
            ['id' => 1, 'account_group' => 'Assets', 'account_type' => 'Assets', 'account_number' => 100000, 'is_parent' => 1, 'parent_account_number' => null, 'description' => 'Major Group', 'created_at' => '2025-04-11 16:02:40', 'updated_at' => '2025-04-14 11:29:49'],
            ['id' => 2, 'account_group' => 'Assets', 'account_type' => 'Current Assets', 'account_number' => 110000, 'is_parent' => 1, 'parent_account_number' => 100000, 'description' => 'Subgroup', 'created_at' => '2025-04-11 16:02:40', 'updated_at' => '2025-04-11 16:02:40'],
            ['id' => 3, 'account_group' => 'Assets', 'account_type' => 'Cash', 'account_number' => 111000, 'is_parent' => 1, 'parent_account_number' => 110000, 'description' => 'General cash account', 'created_at' => '2025-04-11 16:02:40', 'updated_at' => '2025-04-14 11:13:20'],
            // ... add the rest from the dump as needed ...
            ['id' => 286, 'account_group' => 'Assets', 'account_type' => 'wee', 'account_number' => 100030, 'is_parent' => 0, 'parent_account_number' => null, 'description' => '', 'created_at' => '2025-04-14 14:47:58', 'updated_at' => '2025-04-14 14:47:58'],
        ]);

        // inventory_db (structure exists, no rows provided in dump)
    }
}


