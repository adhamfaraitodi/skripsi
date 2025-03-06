<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }
        return view('user.cart_user', compact('cart', 'total'));
    }
    public function create(Request $request){
        $menuId = $request->input('id');
        $tableId = $request->input('table_id');
    
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }
        $cart = session()->get('cart');
        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity']++;
            $cart[$menuId]['subtotal'] = $cart[$menuId]['quantity'] * $cart[$menuId]['price'];
        } else {
            $menu = Menu::find($menuId);
            $originalPrice = $menu->price;
            $cart[$menuId] = [
                'name' => $menu->name,
                'quantity' => 1,
                'original_price' => $originalPrice,
                'price' => $menu->price - $menu->discount,
                'discount' => $menu->discount,
                'image_path' => $menu->image_path,
                'table_id' => $tableId,
                'subtotal' => $menu->price - $menu->discount
            ];
        }
        session()->put('cart', $cart);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Item added to cart successfully',
            'cart_count' => count($cart)
        ]);
    }
    
    public function favorite(Request $request)
    {
        $menuId = $request->input('menu_id');
        $menu = Menu::findOrFail($menuId);
        $isFavorite = filter_var($request->input('is_favorite'), FILTER_VALIDATE_BOOLEAN);
    
        if ($isFavorite) {
            $menu->increment('favorite');
            $status = 'added';
        } else {
            if ($menu->favorite > 0) {
                $menu->decrement('favorite');
            }
            $status = 'removed';
        }
        return response()->json([
            'status' => $status,
            'favorite_count' => $menu->favorite
        ]);
    }
    public function updateCart(Request $request)
    {
        $menuId = $request->menu_id;
        $quantity = $request->quantity;

        if (!session()->has('cart')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ]);
        }

        $cart = session()->get('cart');

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] = $quantity;
            $cart[$menuId]['subtotal'] = $quantity * $cart[$menuId]['price'];

            session()->put('cart', $cart);

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['subtotal'];
            }

            return response()->json([
                'status' => 'success',
                'subtotal' => $cart[$menuId]['subtotal'],
                'total' => $total
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Item not found in cart'
        ]);
    }
    public function removeCart(Request $request)
    {
        $menuId = $request->menu_id;
    
        if (!session()->has('cart')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ]);
        }
    
        $cart = session()->get('cart');
    
        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('cart', $cart);
    
            $total = array_sum(array_column($cart, 'subtotal'));
    
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from cart',
                'total' => $total
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Item not found in cart'
        ]);
    }    
}
