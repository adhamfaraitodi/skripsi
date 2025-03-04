<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Menu;
use Illuminate\Support\Facades\Session;

class UserMenuController extends Controller
{
    public function index()
    {
        $tableId = Session::get('table_id');
        $orderId = Session::get('order_id');
        if (!$tableId || !$orderId) {
            return redirect()->route('user.table')->with('error', 'Session expired. Please scan again.');
        }
        $datas = Menu::withoutTrashed()->with('category')->get();
        foreach ($datas as $data) {
            $latestInventory = Inventory::where('menu_id', $data->id)->latest()->first();
            $data->stock = $latestInventory ? $latestInventory->current_quantity : 0;
        }
        return view('user.menu_user', compact('tableId', 'orderId', 'datas'));
    }
}
