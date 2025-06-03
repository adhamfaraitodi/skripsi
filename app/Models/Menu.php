<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use SoftDeletes, HasFactory;

    protected $dates = ['deleted_at'];
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
