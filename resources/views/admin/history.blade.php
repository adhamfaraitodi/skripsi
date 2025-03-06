@extends('admin.layouts.app')
@section('page_title', 'History Order')

@section('content')
    <div class="p-6">
        <x-table-data :name="'History Order Table'">
            <x-slot name="column">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">status order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">order code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">gross amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">note</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">detail</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">payment detail</th>
            </x-slot>
            <x-slot name="row">
                @forelse($datas as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium
                                {{ $data->order_status == 'success' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($data->order_status) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $data->order_code}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $data->gross_amount}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $data->note}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="toggleDropdown('order-detail-{{ $data->id }}')"
                                    class="text-blue-600 hover:text-blue-900 flex items-center transition-all duration-300">
                                <span>View Detail</span>
                                <i class="ph ph-caret-down ml-2"></i>
                            </button>
                            <div id="order-detail-{{ $data->id }}" class="hidden mt-2 bg-gray-50 rounded-lg shadow-md p-4 transition-all duration-300">
                                <h4 class="text-gray-700 font-semibold mb-2">Order Detail</h4>

                                @foreach($data->menus as $menuOrder)
                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                        <p class="text-sm text-gray-800 font-semibold">
                                            {{ $menuOrder->created_at->format('Y-m-d H:i:s') ?? 'N/A'}} -
                                            {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                        <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="toggleDropdown('payment-detail-{{ $data->id }}')"
                                    class="text-blue-600 hover:text-blue-900 flex items-center transition-all duration-300">
                                <span>View Payment Detail</span>
                                <i class="ph ph-caret-down ml-2"></i>
                            </button>
                            <div id="payment-detail-{{ $data->id }}" class="hidden mt-2 bg-gray-50 rounded-lg shadow-md p-4 transition-all duration-300">
                                <h4 class="text-gray-700 font-semibold mb-2">Order Detail</h4>
                                <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                <p class="text-sm text-gray-800 font-semibold">{{ optional($data->payment)->created_at ? $data->payment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-500 italic">Status: {{ $data->payment->transaction_status ?? 'N/A'}}</p>
                                    <p class="text-sm text-gray-500 italic">Payment Type: {{ $data->payment->payment_type ?? 'N/A'}}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </x-slot>
            <x-slot name="scripting">
                <script>
                    function toggleDropdown(id) {
                        document.getElementById(id).classList.toggle('hidden');
                    }
                </script>
            </x-slot>
        </x-table-data>
    </div>
@endsection
