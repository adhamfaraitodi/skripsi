@extends('admin.layouts.app')
@section('page_title', 'Sales Report')
@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <x-button.print>
                <x-slot name="title">Laporan Penjualan Bulanan </x-slot>
            </x-button.print>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">No</th>
                            <th class="py-2 px-4 text-left">Tanggal</th>
                            <th class="py-2 px-4 text-left">Order Code</th>
                            <th class="py-2 px-4 text-left">Order Status</th>
                            <th class="py-2 px-4 text-left">Detail Pesanan</th>
                            <th class="py-2 px-4 text-right">Gross Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($datas as $index => $item)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}</td>
                                <td class="py-2 px-4">{{ $item->order_code }}</td>
                                <td class="py-2 px-4">{{ $item->order_status }}</td>
                                <td class="py-2 px-4">
                                    <ul>
                                        @foreach ($item->menus as $menu_order)
                                            <li class="flex items-center py-1 text-sm">
                                                <span class="font-medium mr-5">{{ $menu_order->name }}</span>
                                                <span>{{ $menu_order->quantity }} x (Rp {{ number_format($menu_order->price, 0, ',', '.') }} - Rp {{ number_format($menu_order->discount, 0, ',', '.') }}) = <span class="font-semibold">Rp {{ number_format($menu_order->subtotal, 0, ',', '.') }}</span></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-2 px-4 text-right">Rp {{ number_format($item->gross_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100">
                        <tr class="border-t">
                            <td colspan="4"></td>
                            <td class="py-2 px-4 text-right font-semibold">Total:</td>
                            <td class="py-2 px-4 text-right font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        </tfoot>
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
