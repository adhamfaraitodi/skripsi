@extends('user.layouts.app')
@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Order History</h2>

        @if ($datas->isEmpty())
            <p class="text-gray-600">No order history available.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md ">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="py-1 px-4 border">Order Time</th>
                        <th class="py-2 px-4 border">Order Id</th>
                        <th class="py-2 px-4 border">Status  Payment</th>
                        <th class="py-2 px-4 border">Total Amount</th>
                        <th class="py-2 px-4 border">Note</th>
                        <th class="py-2 px-4 border">Detail Order</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($datas as $order)
                        <tr class="text-center">
                            <td class="py-2 px-4 border">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                            <td class="py-2 px-4 border">{{ $order->order_code }}</td>
                            <td class="py-2 px-4 border relative">
                                <span class="px-2 py-1 rounded
                                    @if ($order->order_status == 'paid') bg-green-200 text-green-800
                                    @elseif ($order->order_status == 'success') bg-green-200 text-green-800
                                    @else bg-red-200 text-red-800
                                    @endif">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border">{{ $order->note ?? '-' }}</td>
                            <td class="py-2 px-4 border">
                            <x-pop-up>
                                <x-slot name="id">
                                    order-detail-{{ $order->id }}
                                </x-slot>
                                <x-slot name="title">
                                    Order Detail
                                </x-slot>
                                <x-slot name="content">
                                        @foreach($order->menus as $menuOrder)
                                            <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                                <p class="text-sm text-gray-800 font-semibold">
                                                    {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                                </p>
                                                <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                                <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                            </div>
                                        @endforeach</br>
                                        <h4 class="text-gray-700 font-semibold text-xl pl-4 mb-2">Payment Detail</h4>
                                        <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                            <p class="text-sm text-gray-800 font-semibold">{{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('Y-m-d H:i:s') : 'N/A' }} - ID : {{optional($order->payment)->transaction_id?$order->payment->transaction_id :'N/A'}}</p>
                                            <p class="text-sm text-gray-500 italic">Payment: {{ $order->payment->payment_type ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500 italic">    Grand Total: Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 2) : 'N/A' }}</p>
                                        </div>
                                </x-slot>
                            </x-pop-up>
                            </td>
                           
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endsection
