<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserMenuController extends Controller
{
    public function index(Request $request)
    {
        $tableId = Session::get('table_id');
        $orderId = Session::get('order_id');
        if (!$tableId || !$orderId) {
            return redirect()->route('user.table')->with('error', 'Session expired. Please scan again.');
        }

        $query = Menu::withoutTrashed()->with('category');
        
        // Handle search functionality
        if ($request->has('query') && !empty($request->get('query'))) {
            $searchTerm = $request->get('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                    $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                });
            });
        }
        
        $datas = $query->get();
        
        // Add stock information
        foreach ($datas as $data) {
            $latestInventory = Inventory::where('menu_id', $data->id)->latest()->first();
            $data->stock = $latestInventory ? $latestInventory->current_quantity : 0;
        }
        
        return view('user.menu_user', compact('tableId', 'orderId', 'datas'));
    }
}
