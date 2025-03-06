<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function index(Request $request){
        $validated = $request->validate([
            'order_id'           => 'required|string',
            'transaction_id'     => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_time'   => 'required|date',
            'settlement_time'    => 'nullable|date',
            'payment_type'       => 'required|string',
            'gross_amount'       => 'required|numeric',
            'signature_key'      => 'required|string',
            'status_code'        => 'required|string',
            'va_numbers'         => 'sometimes|array',
            'va_numbers.*.va_number' => 'sometimes|string',
            'va_numbers.*.bank'  => 'sometimes|string',
        ]);
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $expectedSignature = hash('sha512', $validated['order_id'] . $validated['status_code'] . $validated['gross_amount'] . $serverKey);
        if ($validated['signature_key'] !== $expectedSignature) {
            Log::warning('Invalid Midtrans signature key', ['request' => $validated]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        $order = Order::where('order_code', $validated['order_id'])->firstOrFail();
        $vaNumber = $validated['va_numbers'][0]['va_number'] ?? null;
        $bank = $validated['va_numbers'][0]['bank'] ?? null;
        $payment = Payment::updateOrCreate(
            ['order_code' => $validated['order_id']],
            [
                'order_id'           => $order->id,
                'transaction_id'     => $validated['transaction_id'],
                'transaction_status' => $validated['transaction_status'],
                'payment_type'       => $validated['payment_type'],
                'gross_amount'       => $validated['gross_amount'],
                'transaction_time'   => $validated['transaction_time'],
                'settlement_time'    => $validated['settlement_time'] ?? null,
                'va_number'          => $vaNumber,
                'bank'               => $bank,
                'response_json'      => json_encode($request->all()),
            ]
        );

        if ($validated['transaction_status'] === 'settlement') {
            $order->update(['order_status' => 'paid']);
        }
        return response()->json(['message' => 'Payment recorded successfully']);
    }
}
