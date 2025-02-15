@extends('admin.layouts.app')
@section('page_title', 'Finansial Report')
@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mt-6 flex justify-between items-center print:hidden">
                <div class="space-x-2">
                    <button onclick="printSection();" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                        <i class="ph ph-printer mr-2"></i> Print
                    </button>
                </div>
            </div>
            <div id="printable-content">
                <div class="flex items-center justify-center border-b border-gray-400 pb-5 mb-6">
                    <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-10 mr-4">
                    <div class="text-center">
                        <h2 class="text-xl font-bold pr-11 mt-2 uppercase">YOSHIMIE</h2>
                        <p class="text-sm text-gray-700">Jl. Kaliurang KM 11, Pedak, Sinduharjo, Kec. Ngaglik, Yogyakarta 55581</p>
                        <p class="text-sm text-gray-700">Phone: 081250514071 | Email: bakmiehotplate@gmail.com</p>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h1 class="text-1xl font-semibold uppercase">Finansial Bulanan</h1>
                    <p class="text-lg text-gray-600">Bulan: {{ Carbon\Carbon::now()->format('F Y') }}</p>
                </div>

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Monthly Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ Carbon\Carbon::now()->format('F Y') }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Total Orders</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        <p class="text-sm text-gray-500 mt-1">Completed Transactions</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Average Order Value</h3>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Per Transaction</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Most Sold Item</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $mostSoldItem['name'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $mostSoldItem['quantity'] ?? 0 }} Units Sold</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Highest Revenue Item</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $highestRevenueItem['name'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($highestRevenueItem['revenue'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Average Daily Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($averagePerDay, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Per Day</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">No</th>
                            <th class="py-2 px-4 text-left">Date</th>
                            <th class="py-2 px-4 text-left">Order Code</th>
                            <th class="py-2 px-4 text-left">Items</th>
                            <th class="py-2 px-4 text-left">Payment Type</th>
                            <th class="py-2 px-4 text-left">Gross Amount</th>
                            <th class="py-2 px-4 text-left">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($datas as $index => $payment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ Carbon\Carbon::parse($payment->created_at)->format('d M Y - H:i') }}</td>
                                <td class="py-2 px-4">{{ $payment->order->order_code }}</td>
                                <td class="py-2 px-4">
                                    <ul class="list-disc list-inside">
                                        @foreach($payment->order->menus as $menu_order)
                                            <li class="flex items-center py-1 text-sm">
                                                <span class="font-medium mr-5">{{ $menu_order->name }}</span>
                                                <span>{{ $menu_order->quantity }} x (Rp {{ number_format($menu_order->price, 0, ',', '.') }} - Rp {{ number_format($menu_order->discount, 0, ',', '.') }}) = <span class="font-semibold">Rp {{ number_format($menu_order->subtotal, 0, ',', '.') }}</span></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-2 px-4">{{ $payment->payment_type }}</td>
                                <td class="py-2 px-4">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-sm text-green-600">{{ $payment->transaction_status }}</span>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-6 text-sm text-gray-500">
                    <p><strong>Dicetak Pada:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printSection() {
            let printContent = document.getElementById('printable-content').innerHTML;
            let originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }
    </script>
@endsection
