<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    public function index(){
        $userId=Auth::user()->id;
        $datas = Order::with(['menus.menu','payment'])
            ->where('user_id', $userId)
            ->get();
        return view('user/history_user', compact('datas'));
    }
}
