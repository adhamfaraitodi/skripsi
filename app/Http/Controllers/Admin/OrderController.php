<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $datas = Order::with(['menus.menu'])
            ->whereIn('status_order', ['pending', 'paid'])
            ->orderByRaw("FIELD(status_order, 'paid', 'pending')")
            ->get();
        return view('admin/order', compact('datas'));
    }

    public function history(){
        $datas = Order::with(['menus.menu'])
            ->whereIn('status_order', ['success', 'cancelled'])
            ->orderByRaw("FIELD(status_order, 'success', 'cancelled')")
            ->get();
        return view('admin/history',compact('datas'));
    }
    public function update($id){
        $data = Order::findOrFail($id);
        $data->status_order="success";
        $data->save();
        return redirect()->back();
    }
}
