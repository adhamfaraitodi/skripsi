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

        $gross_amount = 0;
        foreach ($cart as $item) {
            $gross_amount += $item['subtotal'];
        }

        $order = new Order();
        $order->user_id = $user_id;
        $order->table_id = $table_id;
        $order->order_code = $order_code;
        $order->order_status = 'pending';
        $order->gross_amount = $gross_amount;
        $order->note = $request->order_note;
        $order->save();

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
            $latestInventory = Inventory::where('menu_id', $menu_id)->latest()->first();
            $newQuantity = $latestInventory ? ($latestInventory->current_quantity - $item['quantity']) : 0;
            Inventory::create([
                'menu_id' => $menu_id,
                'quantity' => $item['quantity'],
                'transaction_type' => 'out',
                'reason' => 'sold',
                'current_quantity' => max($newQuantity, 0),
            ]);
        }
        session()->forget('cart');
        return response()->json([
            'message' => 'Order placed successfully!',
            'redirect_url' => route('user.payment', ['order_id' => $order_code]),
        ]);
    }
    public function payment($order_id)
    {
        $order = Order::where('order_code', $order_id)->firstOrFail();
        $user = Auth::user();
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $transaction_details = [
            'order_id' => $order->order_code,
            'gross_amount' => $order->gross_amount,
        ];
        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];
        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
        ];
        $snapToken = Snap::getSnapToken($transaction);
        return view('user.payment_user', compact('order', 'snapToken'));
    }
    public function pop(){
        return view('user.thank_you');
    }

}
