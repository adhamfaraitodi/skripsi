<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\MenuOrder;
use App\Models\Order;
use App\Models\Payment;
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
    public function financial()
    {
        $datas = Payment::with(['order.menus.menu'])
            ->where('transaction_status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthlyTotal = $datas->sum('gross_amount');
        $totalOrders = $datas->count();
        $averageOrderValue = $totalOrders > 0 ? $monthlyTotal / $totalOrders : 0;

        $menuSales = collect();
        foreach ($datas as $payment) {
            foreach ($payment->order->menus as $menu_order) {
                $menuSales->push([
                    'name' => $menu_order->menu->name,
                    'quantity' => $menu_order->quantity,
                    'revenue' => $menu_order->subtotal
                ]);
            }
        }
        $mostSoldItem = $menuSales->groupBy('name')
            ->map(function ($items, $name) {
                return [
                    'name' => $name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum('revenue')
                ];
            })
            ->sortByDesc('quantity')
            ->first();

        $highestRevenueItem = $menuSales->groupBy('name')
            ->map(function ($items, $name) {
                return [
                    'name' => $name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum('revenue')
                ];
            })
            ->sortByDesc('revenue')
            ->first();

        $averagePerDay = $monthlyTotal / Carbon::now()->daysInMonth;

        return view('admin/report_finansial', compact(
            'datas',
            'monthlyTotal',
            'totalOrders',
            'averageOrderValue',
            'mostSoldItem',
            'highestRevenueItem',
            'averagePerDay'
        ));
    }
}
