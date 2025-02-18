@extends('admin.layouts.app')
@section('page_title', 'Inventory Report')
@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <x-button.print>
                <x-slot name="title">Laporan Inventory Bulanan </x-slot>
            </x-button.print>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-200">
                        <tr class="border-b">
                            <th class="py-2 px-4 text-left">No</th>
                            <th class="py-2 px-4 text-left">Tanggal</th>
                            <th class="py-2 px-4 text-left">Menu Name</th>
                            <th class="py-2 px-4 text-left">Current Quantity</th>
                            <th class="py-2 px-4 text-left">Quantity</th>
                            <th class="py-2 px-4 text-left">Transaction Type</th>
                            <th class="py-2 px-4 text-left">Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($datas as $index => $item)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $index + 1 }}</td>
                                <td class="py-2 px-4">{{ Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}</td>
                                <td class="py-2 px-4">{{ $item->menu->name }}</td>
                                <td class="py-2 px-4">{{ $item->current_quantity }}</td>
                                <td class="py-2 px-4">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 {{ $item->transaction_type == 'in' ? 'text-green-500' : 'text-red-500' }}">{{ $item->transaction_type == 'in' ? 'Stock In' : 'Stock Out' }}</td>
                                <td class="py-2 px-4">{{ $item->reason }}</td>
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
