<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use SoftDeletes,HasFactory;

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
