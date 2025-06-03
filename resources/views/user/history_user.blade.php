@extends('user.layouts.app')
@section('content')
    <div class="container mx-auto p-3 sm:p-4">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Order History</h2>

        @if ($datas->isEmpty())
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <i class="ph ph-clock-counter-clockwise text-6xl"></i>
                </div>
                <p class="text-gray-600 text-lg">No order history available.</p>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-3 px-4 border text-center font-semibold w-32">Order ID</th>
                                <th class="py-3 px-4 border text-center font-semibold w-24">Status</th>
                                <th class="py-3 px-4 border text-center font-semibold w-40">Total Amount</th>
                                <th class="py-3 px-4 border text-center font-semibold w-[30%]">Note</th>
                                <th class="py-3 px-4 border text-center font-semibold w-40">Time</th>
                                <th class="py-3 px-4 border text-center font-semibold w-24">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 border font-mono text-center text-sm w-32">{{ $order->order_code }}</td>
                                    <td class="py-3 px-4 border text-center w-24">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                            @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 border text-center font-semibold w-40">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 border w-[30%]">
                                        <p class="line-clamp-3 break-words text-sm">
                                            {{ $order->note ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="py-3 px-4 border text-center w-40">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="py-3 px-4 border text-center w-24">
                                        <x-pop-up>
                                            <x-slot name="id">order-detail-{{ $order->id }}</x-slot>
                                            <x-slot name="title">Order Detail</x-slot>
                                            <x-slot name="content">
                                                @foreach($order->menus as $menuOrder)
                                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                                        <p class="text-sm text-gray-800 font-semibold">
                                                            {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                                        <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                                    </div>
                                                @endforeach
                                                <h4 class="text-gray-700 font-semibold text-xl pl-4 mb-2 mt-4">Payment Detail</h4>
                                                <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                                    <p class="text-sm text-gray-800 font-semibold">{{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('Y-m-d H:i:s') : 'N/A' }} - ID : {{optional($order->payment)->transaction_id?$order->payment->transaction_id :'N/A'}}</p>
                                                    <p class="text-sm text-gray-500 italic">Payment: {{ $order->payment->payment_type ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-500 italic">Grand Total: Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 2) : 'N/A' }}</p>
                                                </div>
                                            </x-slot>
                                        </x-pop-up>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4">
                @foreach ($datas as $order)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <!-- Order Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Order #{{ $order->order_code }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount:</span>
                                <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($order->note)
                                <div class="flex justify-between items-start">
                                    <span class="text-sm text-gray-600">Note:</span>
                                    <span class="text-sm text-gray-700 text-right flex-1 ml-2">{{ $order->note }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- View Details Button -->
                        <div class="pt-3 border-t border-gray-200">
                            <x-pop-up>
                                <x-slot name="id">order-detail-mobile-{{ $order->id }}</x-slot>
                                <x-slot name="title">Order Detail</x-slot>
                                <x-slot name="content">
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                        <h3 class="font-semibold text-gray-900">Order #{{ $order->order_code }}</h3>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                                        <div class="mt-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                                @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <h4 class="text-gray-700 font-semibold text-lg mb-3">Order Items</h4>
                                    @foreach($order->menus as $menuOrder)
                                        <div class="px-3 py-3 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-800 font-semibold">
                                                        {{ $menuOrder->quantity }}x {{ $menuOrder->menu->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($menuOrder->price, 0, ',', '.') }} each</p>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($menuOrder->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <h4 class="text-gray-700 font-semibold text-lg mt-6 mb-3">Payment Details</h4>
                                    <div class="px-3 py-3 bg-white rounded-md shadow-sm border border-gray-200">
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Transaction ID:</span>
                                                <span class="text-sm font-mono">{{ optional($order->payment)->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Payment Method:</span>
                                                <span class="text-sm">{{ $order->payment->payment_type ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Settlement Time:</span>
                                                <span class="text-sm">{{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('d M Y, H:i') : 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                                <span class="text-sm font-semibold text-gray-900">Grand Total:</span>
                                                <span class="text-sm font-bold text-green-600">Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 0, ',', '.') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </x-slot>
                            </x-pop-up>
                        </div>
                    </div>
                @endforeach
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
