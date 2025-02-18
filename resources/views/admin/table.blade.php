@extends('admin.layouts.app')
@section('page_title', 'Table Management')
@section('content')
    <div class="p-6">
        @auth
            @if(auth()->user()->role_id == 1)
            <div class="mb-6 flex items-center space-x-4">
            <h3 class="text-lg font-semibold text-gray-700">Put Total Table:</h3>
            <form action="{{ route('table.create') }}" method="POST" class="flex items-center">
                @csrf
                <input type="number" name="total" min="1" required
                       class="border rounded-md px-2 py-1 w-20 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="ml-2 text-green-600 hover:text-green-900">
                    <i class="ph ph-arrows-clockwise text-3xl"></i>
                </button>
            </form>
        </div>
            @endif
        @endauth

    <x-table-data :name="'Table Management'">
        <x-slot name="column">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table qr code</th>
        </x-slot>

        <x-slot name="row">
            @forelse($datas as $data)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-48 text-sm font-medium text-gray-900">{{ $data->number }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $data->table_code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div id="qrcode-{{ $data->table_code }}"></div>
                    </td>
                </tr>
            @empty
            @endforelse
        </x-slot>

        <x-slot name="scripting">
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let tables = @json($datas);
                    tables.forEach(function (data) {
                        let elementId = "qrcode-" + data.table_code;
                        let url = "{{ url('/scan') }}/" + data.table_code;

                        new QRCode(document.getElementById(elementId), {
                            text: url,
                            width: 100,
                            height: 100
                        });
                    });
                });
            </script>
        </x-slot>
    </x-table-data>
    </div>
@endsection
