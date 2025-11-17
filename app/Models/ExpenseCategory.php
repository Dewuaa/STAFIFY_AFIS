<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_name',
    ];

    public function pettyCashVouchers()
    {
        return $this->hasMany(PettyCashVoucher::class, 'category_id');
    }
}

