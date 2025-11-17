<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'account_group',
        'account_type',
        'account_number',
        'is_parent',
        'parent_account_number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_parent' => 'integer',
        'is_active' => 'integer',
        'account_number' => 'integer',
        'parent_account_number' => 'integer',
    ];
}


