<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
        'image_path',
        'telephone_number',
        'address',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role_id' => 'integer',
        ];
    }
    protected $dates = ['deleted_at'];
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}
