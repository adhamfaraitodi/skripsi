<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $datas = Order::with(['menus.menu','payment'])
            ->whereIn('order_status', ['pending', 'paid'])
            ->orderByRaw("FIELD(order_status, 'paid', 'pending')")
            ->get();
        return view('admin/order', compact('datas'));
    }
    public function history(){
        $datas = Order::with(['menus.menu'])
            ->whereIn('order_status', ['success', 'cancelled'])
            ->orderByRaw("FIELD(order_status, 'success', 'cancelled')")
            ->get();
        return view('admin/history',compact('datas'));
    }
    public function update($id){
        $data = Order::findOrFail($id);
        $data->order_status="success";
        $data->save();
        return redirect()->back();
    }
}
