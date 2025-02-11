<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'image_path',
        'telephone_number',
        'address',
        'role',
        'password',
    ];
    protected $casts = [
        'password' => 'hashed',
        'roles' => 'string',
        'email_verified_at' => 'datetime',
    ];
    protected $hidden = [
      'password',
      'remember_token'
    ];
    public function menus()
    {
        return $this->hasMany(Menu::class,'admin_id');
    }
}
