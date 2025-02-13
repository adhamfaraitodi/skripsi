<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(){
        $datas = Menu::with('category', 'user')->get();
        return view('admin/food',compact('datas'));
    }
    public function create(){
        $datas=Category::select('id','name')->get();
        return view('admin/food_create',compact('datas'));
    }
    public function store(Request $request){
        $request->validate([
            'foodName' => 'required|string|min:1|max:50',
            'foodDesc' => 'required|string|min:1|max:255',
            'foodImg' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foodStock' => 'required|integer|min:0',
            'category_id' => 'required|integer',
            'foodPrice' => 'required|numeric|min:0',
            'foodDisc' => 'required|numeric|min:0',
        ]);
        $user = Auth::user();
        $imagePath = $request->file('foodImg')->store('menu_images', 'public');
        $data = new Menu();
        $data->status=1;
        $data->user_id=$user->id;
        $data->name = $request->foodName;
        $data->description = $request->foodDesc;
        $data->image_path = $imagePath;
        $data->category_id = $request->category_id;
        $data->price = $request->foodPrice;
        $data->discount = $request->foodDisc;
        $data->favorite = 0;
        $data->save();

        Inventory::create([
            'menu_id' => $data->id,
            'quantity' => $request->foodStock,
            'transaction_type' => 'in',
            'reason' => 'initial quantity',
        ]);
        return redirect()->route('food.index')->with('success', 'Food item added successfully');
    }
    public function edit($id){
        $data=Menu::with('category')->find($id);
        $datas=Category::select('id','name')->get();
        return view('admin/food_edit',compact('data','datas'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'foodName' => 'required|string|min:1|max:50',
            'foodDesc' => 'required|string|min:1|max:255',
            'foodImg' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|integer',
            'foodPrice' => 'required|numeric|min:0',
            'foodDisc' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $data = Menu::findOrFail($id);
        $data->user_id = $user->id;
        $data->name = $request->foodName;
        $data->description = $request->foodDesc;
        $data->category_id = $request->category_id;
        $data->price = $request->foodPrice;
        $data->discount = $request->foodDisc;

        if ($request->hasFile('foodImg')) {
            $imagePath = $request->file('foodImg')->store('menu_images', 'public');
            $data->image_path = $imagePath;
        }
        $data->save();
        return redirect()->route('food.index')->with('success', 'Food item updated successfully');
    }

    public function destroy($id){
        $data = Menu::findOrFail($id);
        $data->status=0;
        $data->save();
        return redirect()->back();
    }
    public function restore($id){
        $data = Menu::findOrFail($id);
        $data->status=1;
        $data->save();
        return redirect()->back();
    }
}
