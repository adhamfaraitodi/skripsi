<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\MenuOrder;
use App\Models\Order;
use App\Models\Table;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPaymentController extends Controller
{
    public function index(){
        $cart = session('cart', []);
        $order_id = session('order_id');
        $table_id = session('table_id');
        $table = Table::findOrFail($table_id);
        return view('user.checkout_user', compact('cart', 'order_id', 'table'));
    }
    public function create(Request $request) {
        $request->validate([
            'order_note' => 'nullable|string|max:500',
        ]);
    
        $cart = session('cart', []);
        $table_id = session('table_id');
        $order_code = session('order_id');
        $user_id = Auth::id();
    
        if (empty($cart)) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
    
        try {
            DB::beginTransaction();
    
            foreach ($cart as $menu_id => $item) {
                $latestInventory = Inventory::where('menu_id', $menu_id)
                    ->latest()
                    ->sharedLock()
                    ->first();
    
                $currentQuantity = $latestInventory ? $latestInventory->current_quantity : 0;
                if ($currentQuantity < $item['quantity']) {
                    return response()->json(['message' => "Insufficient stock for menu ID {$menu_id}"], 400);
                }
            }
    
            $gross_amount = array_reduce($cart, fn($sum, $item) => $sum + $item['subtotal'], 0);
    
            $order = Order::create([
                'user_id' => $user_id,
                'table_id' => $table_id,
                'order_code' => $order_code,
                'order_status' => 'pending',
                'gross_amount' => $gross_amount,
                'note' => $request->order_note,
            ]);
    
            foreach ($cart as $menu_id => $item) {
                MenuOrder::create([
                    'menu_id' => $menu_id,
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'subtotal' => $item['subtotal'],
                ]);
    
                $latestInventory = Inventory::where('menu_id', $menu_id)
                    ->latest()
                    ->lockForUpdate()
                    ->first();
    
                $newQuantity = $latestInventory ? $latestInventory->current_quantity - $item['quantity'] : 0;
                Inventory::create([
                    'menu_id' => $menu_id,
                    'quantity' => $item['quantity'],
                    'transaction_type' => 'out',
                    'reason' => 'sold',
                    'current_quantity' => max($newQuantity, 0),
                ]);
            }
    
            DB::commit();
            session()->forget('cart');
    
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
    
            $transaction = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => $order->gross_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];
    
            $snapToken = Snap::getSnapToken($transaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function pop(){
        return view('user.thank_you');
    }

    public function continuePayment(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)
                  ->where('order_status', 'pending')
                  ->firstOrFail();
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        $payload = [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => $order->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to continue payment.'], 500);
        }
    }
}
