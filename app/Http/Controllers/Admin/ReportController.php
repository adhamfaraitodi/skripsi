<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Menu;
use App\Models\MenuOrder;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function financial() // need simplification i believe
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        $paymentStats = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('COUNT(*) as total_orders, SUM(gross_amount) as monthly_total')
            ->first();

        $monthlyTotal = $paymentStats->monthly_total ?? 0;
        $totalOrders = $paymentStats->total_orders ?? 0;
        $averageOrderValue = $totalOrders > 0 ? $monthlyTotal / $totalOrders : 0;
        $averagePerDay = $monthlyTotal / $daysInMonth;

        $menuSalesStats = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->join('menu_orders', 'orders.id', '=', 'menu_orders.order_id')
            ->join('menus', 'menu_orders.menu_id', '=', 'menus.id')
            ->where('payments.transaction_status', 'settlement')
            ->whereMonth('payments.created_at', $currentMonth)
            ->whereYear('payments.created_at', $currentYear)
            ->select(
                'menus.name',
                DB::raw('SUM(menu_orders.quantity) as total_quantity'),
                DB::raw('SUM(menu_orders.subtotal) as total_revenue')
            )
            ->groupBy('menus.name')
            ->get();
        $mostSoldItem = null;
        $highestRevenueItem = null;
        $maxQuantity = 0;
        $maxRevenue = 0;

        foreach ($menuSalesStats as $menuStat) {
            if ($menuStat->total_quantity > $maxQuantity) {
                $maxQuantity = $menuStat->total_quantity;
                $mostSoldItem = [
                    'name' => $menuStat->name,
                    'quantity' => $menuStat->total_quantity,
                    'revenue' => $menuStat->total_revenue
                ];
            }

            if ($menuStat->total_revenue > $maxRevenue) {
                $maxRevenue = $menuStat->total_revenue;
                $highestRevenueItem = [
                    'name' => $menuStat->name,
                    'quantity' => $menuStat->total_quantity,
                    'revenue' => $menuStat->total_revenue
                ];
            }
        }
        $mostFavoriteMenu = $this->getMostFavoriteMenu();
        $datas = Payment::where('transaction_status', 'settlement')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.report_finansial', compact(
            'datas',
            'monthlyTotal',
            'totalOrders',
            'averageOrderValue',
            'mostSoldItem',
            'highestRevenueItem',
            'averagePerDay',
            'mostFavoriteMenu',
        ));
    }
    private function getMostFavoriteMenu()
    {
        return Menu::orderBy('favorite', 'desc')->first();
    }
}
