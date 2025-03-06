@extends('admin.layouts.app')
@section('page_title', 'New Order Management')
@section('content')
    <div class="p-6">

        <x-table-data :name="'Trash Food Menu Items'">
            <x-slot name="column">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">status order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">order code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">gross amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">note</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">action</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">detail</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">payment detail</th>
            </x-slot>
            <x-slot name="row">
                @forelse($datas as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium
                                {{ $data->order_status == 'paid' ? 'text-green-600' : 'text-red-600' }}">
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('order.update', $data->id) }}" method="POST" class="flex items-center" onsubmit="return confirm('Do you want to finish this order');">
                                @csrf
                                @method('post')
                                <button type="submit"
                                        class="ml-2 text-green-600
                                        {{ $data->order_status === 'pending' ? 'text-red-600 hover:text-red-900 cursor-not-allowed' : 'text-green-600 hover:text-green-900' }}"
                                    {{ $data->order_status === 'pending' ? 'disabled' : '' }}>Proccess
                                    <i class="ph ph-check text-lg"></i>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-pop-up>
                                <x-slot name="id">
                                    order-detail-{{ $data->id }}
                                </x-slot>
                                <x-slot name="title">
                                    Order Detail
                                </x-slot>
                                <x-slot name="content">
                                    @foreach($data->menus as $menuOrder)
                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                        <p class="text-sm text-gray-800 font-semibold">
                                            {{ $menuOrder->created_at->format('Y-m-d H:i:s') }} -
                                            {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                        <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                    </div>
                                    @endforeach
                                </x-slot>
                            </x-pop-up>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-pop-up>
                                <x-slot name="id">
                                    payment-detail-{{ $data->id }}
                                </x-slot>
                                <x-slot name="title">
                                    Payment Detail
                                </x-slot>
                                <x-slot name="content">
                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                        <p class="text-sm text-gray-600 italic">ID : {{ $data->payment->transaction_id?? 'N/A'}}</p>
                                        <p class="text-sm text-gray-600 italic">{{ optional($data->payment)->created_at ? $data->payment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                        <p class="text-sm text-gray-600 font-semibold">Status: {{ $data->payment->transaction_status ?? 'N/A'}}</p>
                                        <p class="text-sm text-gray-600 italic">Payment Type: {{ $data->payment->payment_type ?? 'N/A'}}</p>
                                        <p class="text-sm text-gray-600 italic">Grand Total: Rp {{ $data->payment ? number_format($data->payment->gross_amount, 2) : 'N/A' }}</p>
                                        @if (!empty($data->payment->response_json))
                                            <p class="text-sm text-gray-600 italic flex items-center">
                                                <i class="ph ph-printer mr-2 text-sm"></i>
                                                <a href="{{ route('download.response', $data->payment->id) }}" 
                                                class="text-blue-500 hover:underline">
                                                Download
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </x-slot>
                            </x-pop-up>
                        </td>
                    </tr>
                @empty
                @endforelse
            </x-slot>
            <x-slot name="scripting">
                <script>
                    function openModal(id) {
                        document.getElementById(id).classList.remove('hidden');
                    }

                    function closeModal(id) {
                        document.getElementById(id).classList.add('hidden');
                    }
                </script>
            </x-slot>
        </x-table-data>
    </div>
@endsection
