<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseToolkit extends Model
{
    use HasFactory;

    protected $table = 'expense_toolkit';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'sales_title',
        'form_url',
        'response_url',
        'icon',
        'type',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scopeAccessible($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_approved', true);
        });
    }
}


