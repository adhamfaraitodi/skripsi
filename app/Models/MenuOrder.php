<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOrder extends Model
{
    protected $table = 'menu_orders';

    protected $fillable = [
        'menu_id',
        'order_id',
        'name',
        'quantity',
        'price',
        'discount',
        'subtotal'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
