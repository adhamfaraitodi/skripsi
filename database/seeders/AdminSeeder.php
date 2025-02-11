<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'image_path'=>'null',
            'telephone_number'=>'0888898989',
            'address'=>'jogjakarta',
            'role' => 'owner',
            'password' => bcrypt('password'),
            'remember_token' =>'null',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
