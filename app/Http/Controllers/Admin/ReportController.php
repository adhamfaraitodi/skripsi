<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request){
        if ($request->has('month_year')) {
            try {
                $date = Carbon::createFromFormat('m-Y', $request->input('month_year'));
                $month = $date->month;
                $year = $date->year;
            } catch (\Exception $e) {
                $month = now()->month;
                $year = now()->year;
            }
        } else {
            $month = now()->month;
            $year = now()->year;
        }
        $datas = Order::with(['menus', 'payment'])
            ->where('order_status','success')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at','asc')
            ->get();
        $total = $datas->sum('gross_amount');
        $mostFavoriteMenu = $this->getMostFavoriteMenu();
        $mostSoldItem = $this->mostSoldItem();
        return view('admin/report_sales',compact('datas','total','mostFavoriteMenu','mostSoldItem'));
    }
    public function inventory(Request $request){
        if ($request->has('month_year')) {
            try {
                $date = Carbon::createFromFormat('m-Y', $request->input('month_year'));
                $month = $date->month;
                $year = $date->year;
            } catch (\Exception $e) {
                $month = now()->month;
                $year = now()->year;
            }
        } else {
            $month = now()->month;
            $year = now()->year;
        }
        $datas = Inventory::with('menu')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at','asc')
            ->get();
        return view('admin/report_inventory',compact('datas'));
    }
    public function financial(Request $request)
    {
        if ($request->has('month_year')) {
            try {
                $date = Carbon::createFromFormat('m-Y', $request->input('month_year'));
                $month = $date->month;
                $year = $date->year;
            } catch (\Exception $e) {
                $month = now()->month;
                $year = now()->year;
            }
        } else {
            $month = now()->month;
            $year = now()->year;
        }
        $datas = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'asc')
            ->get();
        $monthlyTotal = $this->monthlyTotal();
        $averagePerDay = $this->averagePerDay();
        $averageOrderValue = $this->averageOrderValue();
        $totalOrders = $this->totalOrders();
        return view('admin.report_finansial',compact('datas','monthlyTotal','averagePerDay','averageOrderValue','totalOrders'));
    }

    private function getMostFavoriteMenu()
    {
        $mostFavoriteMenu = Menu::orderBy('favorite', 'desc')->first();
        return $mostFavoriteMenu ? [
            'name' => $mostFavoriteMenu->name,
            'favorite' => $mostFavoriteMenu->favorite
        ] : null;
    }
    private function mostSoldItem()
    {
        $mostSoldItem = Menu::select('menus.id', 'menus.name')
            ->join('menu_orders', 'menus.id', '=', 'menu_orders.menu_id')
            ->join('orders', 'menu_orders.order_id', '=', 'orders.id')
            ->whereIn('orders.order_status', ['paid', 'success'])
            ->selectRaw('menus.name, SUM(menu_orders.quantity) as total_sold')
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_sold')
            ->first();

        return $mostSoldItem ? [
            'name' => $mostSoldItem->name,
            'quantity' => $mostSoldItem->total_sold
        ] : null;
    }
    private function monthlyTotal()
    {
        $paymentStats = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('gross_amount');
        $monthlyTotal = $paymentStats ?? 0;
        return $monthlyTotal;
    }
    
    private function averagePerDay()
    {
        $daysInMonth = Carbon::now()->daysInMonth;
        $monthlyTotal = $this->monthlyTotal();
        $averagePerDay = $daysInMonth ? $monthlyTotal / $daysInMonth : 0;
        return $averagePerDay;
    }
    private function averageOrderValue(){
        $totalOrders = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $monthlyTotal = $this->monthlyTotal();
        $averageOrderValue = $totalOrders > 0 ? $monthlyTotal / $totalOrders : 0;
        return $averageOrderValue;
    }
    private function totalOrders(){
        $totalOrders  = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        return $totalOrders;
    }
}
