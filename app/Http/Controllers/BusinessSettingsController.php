<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessSettingsController extends Controller
{
    public function index()
    {
        $settings = BusinessSetting::query()->first();
        return view('pages.business-settings', [
            'settings' => $settings,
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'business_legal_name' => ['nullable', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'registration_type' => ['nullable', 'string', 'max:100'],
            'registration_no' => ['nullable', 'string', 'max:20'],
            'date_of_registration' => ['nullable', 'date'],
            'industry_code' => ['nullable', 'string', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:500'],
            'tax_type' => ['nullable', 'string', 'max:50'],
            'books_of_accounts' => ['nullable', 'string', 'max:100'],
            'accounting_method' => ['nullable', 'string', 'max:50'],
            'fiscal_start_month' => ['nullable', 'string', 'max:20'],
            'quarter_cutoff' => ['nullable', 'string', 'max:20'],
            'withholding_agent' => ['nullable', 'integer', 'in:0,1'],
            'business_tin' => ['nullable', 'string', 'max:15'],
            'rdo_code' => ['nullable', 'string', 'max:3'],
            'official_address' => ['nullable', 'string', 'max:300'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'official_email' => ['nullable', 'email', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'week_start' => ['nullable', 'string', 'max:10'],
            'date_format' => ['nullable', 'string', 'max:20'],
            'number_format' => ['nullable', 'string', 'max:20'],
            'sss_no' => ['nullable', 'string', 'max:50'],
            'phic_no' => ['nullable', 'string', 'max:50'],
            'hdmf_no' => ['nullable', 'string', 'max:50'],
            'peza_cert_no' => ['nullable', 'string', 'max:50'],
            'permits' => ['nullable', 'string', 'max:1000'],
            'or_prefix' => ['nullable', 'string', 'max:10'],
            'si_prefix' => ['nullable', 'string', 'max:10'],
            'next_or_number' => ['nullable', 'integer', 'min:0'],
            'next_si_number' => ['nullable', 'integer', 'min:0'],
            'pdf_template' => ['nullable', 'string', 'max:50'],
            'enable_multi_branch' => ['nullable', 'integer', 'in:0,1'],
            'inventory_tracking_mode' => ['nullable', 'string', 'max:20'],
            'use_weighted_avg_cost' => ['nullable', 'integer', 'in:0,1'],
            'enable_audit_trail' => ['nullable', 'integer', 'in:0,1'],
        ]);

        try {
            DB::beginTransaction();
            $settings = BusinessSetting::query()->first();
            $exists = (bool) $settings;
            $settings = BusinessSetting::updateOrCreate(
                ['id' => optional($settings)->id ?? 1],
                $validated
            );
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Settings saved successfully',
                'affected_rows' => $exists ? 1 : 1,
                'debug' => [
                    'record_exists' => $exists,
                    'id' => $settings->id,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings',
                'debug' => [
                    'error_line' => $e->getLine(),
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }
}


