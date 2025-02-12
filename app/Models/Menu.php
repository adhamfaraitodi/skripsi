<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'status',
        'image_path',
        'favorite',
        'price',
        'discount'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class,'menu_id');
    }
    public function menuOrders()
    {
        return $this->hasMany(MenuOrder::class, 'menu_id');
    }
}
