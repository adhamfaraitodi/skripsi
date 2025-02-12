<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'role_id'=>1,
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'image_path'=>'null',
            'telephone_number'=>'0888898989',
            'address'=>'jogjakarta',
            'password' => bcrypt('password'),
            'remember_token' =>'null',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
