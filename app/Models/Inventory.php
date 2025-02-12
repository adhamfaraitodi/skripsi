<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'menu_id',
        'quantity',
        'transaction_type',
        'reason'
    ];
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
