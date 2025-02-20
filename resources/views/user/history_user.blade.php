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
                        <th class="py-1 px-4 border">Order At</th>
                        <th class="py-2 px-4 border">Order Code</th>
                        <th class="py-2 px-4 border">Status</th>
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
                            <td class="py-2 px-4 border">
                                <span class="px-2 py-1 rounded
                                    {{ $order->order_status == 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border">{{ $order->note ?? '-' }}</td>
                            <td class="py-2 px-4 border">
                                <button onclick="toggleDropdown('order-detail-{{ $order->id }}')"
                                        class="text-blue-600 hover:text-blue-900 flex items-center transition-all duration-300">
                                    <span>View Detail</span>
                                    <i class="ph ph-caret-down ml-2"></i>
                                </button>
                                <div id="order-detail-{{ $order->id }}" class="hidden absolute mt-2 bg-gray-50 rounded-lg shadow-md p-4 transition-all duration-300">
                                    <h4 class="text-gray-700 font-semibold mb-2">Order Detail</h4>

                                    @foreach($order->menus as $menuOrder)
                                        <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                            <p class="text-sm text-gray-800 font-semibold">
                                                {{ $menuOrder->created_at->format('Y-m-d H:i:s') }} -
                                                {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                            <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script>
        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
@endsection

