<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'menu_id',
        'quantity',
        'current_quantity',
        'transaction_type',
        'reason',
    ];
    public $timestamps = true;
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
