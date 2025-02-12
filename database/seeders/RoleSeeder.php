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
            ["name" => "owner"],
            ["name" => "karyawan"],
            ["name" => "user"],
        ];
        foreach ($roles as $data) {
            Role::create([
                'name'=>$data['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
