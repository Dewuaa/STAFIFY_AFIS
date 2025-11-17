<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'Invoice_Id';

    protected $fillable = [
        'Customer_Name',
        'Customer_Email',
        'Billing_Address',
        'Item_Name',
        'Price',
        'Quantity',
        'Discount',
        'Tax',
        'Terms',
        'invoice_mode',
        'tax_type_at_creation',
        'tax_id',
        'discount_id',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'Quantity' => 'integer',
        'Tax' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class, 'tax_id');
    }

    public function discountRate()
    {
        return $this->belongsTo(DiscountRate::class, 'discount_id');
    }
}

