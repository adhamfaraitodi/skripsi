<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles=[
            ["id"=>1,"name" => "owner"],
            ["id"=>2,"name" => "karyawan"],
            ["id"=>3,"name" => "user"],
        ];
        foreach ($roles as $data) {
            Role::create([
                'id'=>$data['id'],
                'name'=>$data['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
