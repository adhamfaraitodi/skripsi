<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOrder extends Model
{
    protected $table = 'menu_orders';

    protected $fillable = [
        'menu_id',
        'order_id',
        'quantity',
        'price',
        'discount',
        'subtotal'
    ];
    public function menuOrders()
    {
        return $this->belongsToMany(MenuOrder::class, 'menu_id');
    }
    public function menusOrder()
    {
        return $this->belongsToMany(MenuOrder::class, 'order_id');
    }
}
