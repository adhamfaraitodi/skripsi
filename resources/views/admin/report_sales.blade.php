@extends('admin.layouts.app')
@section('page_title', 'Sales Report')
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
                    <h1 class="text-1xl font-semibold uppercase">Laporan Penjualan Bulanan</h1>
                    <p class="text-lg text-gray-600">Bulan: {{ Carbon\Carbon::now()->format('F Y') }}</p>
                </div>

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
