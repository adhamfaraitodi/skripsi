@extends('admin.layouts.app')
@section('page_title', 'Finansial Report')
@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <x-button.print>
                <x-slot name="title">Laporan Finansial Bulanan </x-slot>
            </x-button.print>
                @php
                    $monthlyTotal = $monthlyTotal ?? 0;
                    $averagePerDay = $averagePerDay ?? 0;
                    $totalOrders = $totalOrders ?? 0;
                    $averageOrderValue = $averageOrderValue ?? 0;
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
                            <th class="py-2 px-4 text-left">Order By</th>
                            <th class="py-2 px-4 text-left">Items</th>
                            <th class="py-2 px-4 text-left">Payment Thru</th>
                            <th class="py-2 px-4 text-left">Payment Status</th>
                            <th class="py-2 px-4 text-left">Gross Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($datas as $index => $payment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ Carbon\Carbon::parse($payment->created_at)->format('d M Y - H:i') }}</td>
                                <td class="py-2 px-4">{{ $payment->order->order_code }}</td>
                                <td class="py-2 px-4">{{ $payment->order->user_id }}</td>
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
                                <td class="py-2 px-4">
                                    <span
                                        class="px-2 py-1 rounded text-sm text-green-600">{{ $payment->transaction_status }}</span>
                                </td>
                                <td class="py-2 px-4">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
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
