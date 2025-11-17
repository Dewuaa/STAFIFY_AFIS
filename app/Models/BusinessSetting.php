<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $table = 'business_settings';

    protected $fillable = [
        'business_legal_name',
        'trade_name',
        'registration_type',
        'registration_no',
        'date_of_registration',
        'industry_code',
        'business_description',
        'tax_type',
        'books_of_accounts',
        'accounting_method',
        'fiscal_start_month',
        'quarter_cutoff',
        'withholding_agent',
        'business_tin',
        'rdo_code',
        'official_address',
        'zip_code',
        'contact_phone',
        'official_email',
        'currency',
        'timezone',
        'week_start',
        'date_format',
        'number_format',
        'sss_no',
        'phic_no',
        'hdmf_no',
        'peza_cert_no',
        'permits',
        'or_prefix',
        'si_prefix',
        'next_or_number',
        'next_si_number',
        'pdf_template',
        'enable_multi_branch',
        'inventory_tracking_mode',
        'use_weighted_avg_cost',
        'enable_audit_trail',
    ];
}


