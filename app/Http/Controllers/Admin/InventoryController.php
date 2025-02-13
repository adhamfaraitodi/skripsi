<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Menu;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){
        $datas = Menu::with(['inventory' => function($query) {
            $query->latest()->limit(3);
        }])->get();
        $sold = Inventory::where('transaction_type', 'out')
            ->selectRaw('menu_id, SUM(quantity) as total_sold')
            ->groupBy('menu_id')
            ->pluck('total_sold', 'menu_id');
        return view('admin/inventory',compact('datas','sold'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $latestInventory = Inventory::where('menu_id', $id)->latest()->first();
        $newQuantity = $latestInventory ? $latestInventory->current_quantity + $request->quantity : $request->quantity;
        Inventory::create([
            'menu_id' => $id,
            'quantity' => $request->quantity,
            'current_quantity' => $newQuantity,
            'transaction_type' => 'in',
            'reason' => 'add stock',
        ]);
        return redirect()->back()->with('success', 'Stock updated successfully');
    }
}
