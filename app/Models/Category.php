<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];
    public function menu()
    {
        return $this->hasOne(Menu::class, 'category_id');
    }
}
