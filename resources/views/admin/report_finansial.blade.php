@extends('admin.layouts.app')
@section('page_title', 'Finansial Report')
@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mt-6 flex justify-between items-center print:hidden">
                <div class="space-x-2">
                    <button onclick="printSection();"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                        <i class="ph ph-printer mr-2"></i> Print
                    </button>
                </div>
            </div>
            <div id="printable-content">
                <div class="flex items-center justify-center border-b border-gray-400 pb-5 mb-6">
                    <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-10 mr-4">
                    <div class="text-center">
                        <h2 class="text-xl font-bold pr-11 mt-2 uppercase">YOSHIMIE</h2>
                        <p class="text-sm text-gray-700">Jl. Kaliurang KM 11, Pedak, Sinduharjo, Kec. Ngaglik,
                            Yogyakarta 55581</p>
                        <p class="text-sm text-gray-700">Phone: 081250514071 | Email: bakmiehotplate@gmail.com</p>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h1 class="text-1xl font-semibold uppercase">Finansial Bulanan</h1>
                    <p class="text-lg text-gray-600">Bulan: {{ Carbon\Carbon::now()->format('F Y') }}</p>
                </div>

                @php
                    $reportData = [
                        ['title' => 'Monthly Revenue', 'value' => $monthlyTotal, 'suffix' =>'/'. Carbon\Carbon::now()->format('F Y')],
                        ['title' => 'Average Daily Revenue', 'value' => $averagePerDay, 'suffix' => '/ Day'],
                        ['title' => 'Average Order Value', 'value' => $averageOrderValue, 'suffix' => '/ Transaction'],
                        ['title' => 'Total Orders', 'value' => $totalOrders, 'suffix' => 'Completed'],
                        ['title' => 'Most Sold Item', 'value' => $mostSoldItem['name'] ?? 'N/A', 'suffix' => ($mostSoldItem['quantity'] ?? 0) . ' Sold'],
                        ['title' => 'Highest Revenue Item', 'value' => $highestRevenueItem['name'] ?? 'N/A', 'suffix' => 'Rp ' . number_format($highestRevenueItem['revenue'] ?? 0, 0, ',', '.')],
                        ['title' => 'Most Favorite Item', 'value' => $mostFavoriteMenu['name'] ?? 'N/A', 'suffix' =>  number_format($mostFavoriteMenu['favorite'] ?? 0) .' Favorite'],
                    ];
                @endphp

                <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                    <table class="w-full text-gray-900">
                        <tbody>
                        @foreach ($reportData as $data)
                            <tr class="border-b">
                                <td class="py-2 text-gray-600 text-sm">{{ $data['title'] }}</td>
                                <td class="py-2 text-right font-bold">
                                    @if (is_numeric($data['value']) && !str_contains($data['title'], 'Total Orders'))
                                        Rp {{ number_format($data['value'], 0, ',', '.') }}
                                    @else
                                        {{ $data['value'] }}
                                    @endif
                                    <span class="text-black text-sm ml-2">{{ $data['suffix'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
                                                <span>{{ $menu_order->quantity }} x (Rp {{ number_format($menu_order->price, 0, ',', '.') }} - Rp {{ number_format($menu_order->discount, 0, ',', '.') }}) = <span
                                                        class="font-semibold">Rp {{ number_format($menu_order->subtotal, 0, ',', '.') }}</span></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-2 px-4">{{ $payment->payment_type }}</td>
                                <td class="py-2 px-4">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                <td class="py-2 px-4">
                                    <span
                                        class="px-2 py-1 rounded text-sm text-green-600">{{ $payment->transaction_status }}</span>
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
