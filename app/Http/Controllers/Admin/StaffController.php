<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(){
        $datas = User::with('role:id,name')
            ->select('id', 'name', 'email', 'image_path', 'telephone_number', 'address','role_id')
            ->where('role_id', 2)
            ->get();
        return view('admin.staff', compact('datas'));
    }
    public function create(){
        return view('auth/register-staff');
    }
}
