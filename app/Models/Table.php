<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'number',
        'table_code'
    ];

    public function orders()
    {
        return $this->hasOne(Order::class,'table_id');
    }
}
