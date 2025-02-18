@extends('admin.layouts.app')
@section('page_title', 'Food Inventory Management')

@section('content')
    <div class="p-6">
        <x-table-data :name="'Food Stock Inventory'">
            <x-slot name="column">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Menu Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Current Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Sold</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Add Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">History</th>
            </x-slot>
            <x-slot name="row">
                @forelse($datas as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $data->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ optional($data->inventory->first())->current_quantity ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $sold[$data->id] ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('inventory.update', $data->id) }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="number" name="quantity" min="1" required
                                       class="border rounded-md px-2 py-1 w-20 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="ml-2 text-green-600 hover:text-green-900">
                                    <i class="ph ph-plus text-lg"></i>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="toggleDropdown('inventory-{{ $data->id }}')" class="text-blue-600 hover:text-blue-900 flex items-center">
                                <span>View History</span>
                                <i class="ph ph-caret-down ml-2"></i>
                            </button>

                            <div id="inventory-{{ $data->id }}" class="hidden mt-2 bg-gray-50 rounded-lg shadow-md p-4">
                                <h4 class="text-gray-700 font-semibold mb-2">Inventory History</h4>
                                @foreach($data->inventory as $inventory)
                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm">
                                        <p class="text-sm text-gray-800 font-semibold">
                                            {{ $inventory->created_at->format('Y-m-d H:i:s') }} -
                                            {{ $inventory->quantity }} ({{ ucfirst($inventory->transaction_type) }})
                                        </p>
                                        <p class="text-sm text-gray-500 italic">Reason: {{ $inventory->reason }}</p>
                                    </div>
                                @endforeach
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
