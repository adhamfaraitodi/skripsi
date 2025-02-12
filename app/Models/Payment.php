<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'order_code',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'transaction_time',
        'settlement_time',
        'va_number',
        'bank',
        'response_json'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
