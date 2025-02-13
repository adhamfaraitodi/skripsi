<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'order_code',
        'status_order',
        'gross_amount',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class,'table_id');
    }
    public function menus()
    {
        return $this->hasMany(MenuOrder::class, 'order_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class,'order_id');
    }
}
