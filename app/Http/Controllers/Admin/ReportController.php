<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\MenuOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(){
        $datas = Order::with(['menus', 'payment'])
            ->where('order_status','success')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at','desc')
            ->get();

        $total = $datas->sum('gross_amount');
        return view('admin/report_sales',compact('datas','total'));
    }
    public function inventory(){
        $datas = Inventory::with('menu')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at','desc')
            ->get();
        return view('admin/report_inventory',compact('datas'));
    }
    public function financial(){
        return view('admin/report_finansial');
    }
}
