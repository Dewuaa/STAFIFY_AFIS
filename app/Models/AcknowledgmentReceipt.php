<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcknowledgmentReceipt extends Model
{
    use HasFactory;

    protected $table = 'acknowledgment_receipt';
    protected $primaryKey = 'Receipt_Id';

    protected $fillable = [
        'Customer_Name',
        'Customer_Email',
        'contact_number',
        'Address',
        'purpose_type',
        'Payment_For',
        'items_received',
        'location',
        'Amount',
        'payment_status',
        'Payment_Method_Id',
        'Reference_Number',
        'Notes',
        'is_signed',
        'signature_token',
        'signature_date',
        'signature_ip',
        'signature_image',
    ];

    protected $casts = [
        'Amount' => 'decimal:2',
        'is_signed' => 'boolean',
        'signature_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'Payment_Method_Id');
    }
}
