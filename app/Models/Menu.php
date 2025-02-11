<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'admin_id',
        'name',
        'description',
        'stock',
        'sell',
        'favorite',
        'role',
        'price',
        'discount'
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'menu_id');
    }
    public function menuOrders()
    {
        return $this->hasMany(MenuOrder::class, 'menu_id');
    }
}
