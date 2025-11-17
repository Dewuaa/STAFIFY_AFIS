<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashVoucher extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_voucher';
    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'voucher_number',
        'date_issued',
        'payee_name',
        'payee_email',
        'contact_number',
        'department',
        'position',
        'purpose',
        'amount',
        'category_id',
        'payment_method',
        'approved_by',
        'received_by',
        'receipt_attached',
        'notes',
        'status',
        'is_signed',
        'signature_token',
        'signature_date',
        'signature_ip',
        'signature_image',
    ];

    protected $casts = [
        'date_issued' => 'date',
        'amount' => 'decimal:2',
        'receipt_attached' => 'boolean',
        'is_signed' => 'boolean',
        'signature_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}

