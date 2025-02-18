<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'number',
        'table_code'
    ];

    public function orders()
    {
        return $this->hasOne(Order::class,'table_id');
    }
}
