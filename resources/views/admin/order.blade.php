@extends('admin.layouts.app')
@section('page_title', 'New Order Management')
@section('content')
    <div class="p-6">
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">New Order Table</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="orderTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">status order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">order code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">gross amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">detail</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($datas as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium
                                {{ $data->status_order == 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($data->status_order) }}
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
                                        {{ $data->status_order === 'pending' ? 'text-red-600 hover:text-red-900 cursor-not-allowed' : 'text-green-600 hover:text-green-900' }}"
                                        {{ $data->status_order === 'pending' ? 'disabled' : '' }}>
                                        <i class="ph ph-check text-lg"></i>
                                    </button>
                                </form>
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
                                                {{ $menuOrder->created_at->format('Y-m-d H:i:s') }} -
                                                {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 italic">Price: ${{ number_format($menuOrder->price, 2) }}</p>
                                            <p class="text-sm text-gray-500 italic">Subtotal: ${{ number_format($menuOrder->subtotal, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                No new Order found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleDropdown(id) {
                document.getElementById(id).classList.toggle('hidden');
            }

            $(document).ready(function() {
                $('#orderTable').DataTable({
                    "paging": true,
                    "lengthMenu": [5, 10, 25, 50],
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "pagingType": "simple",
                    "language": {
                        "search": "Search Order:",
                        "lengthMenu": "Show _MENU_ items per page",
                        "zeroRecords": "No matching items found",
                        "info": "Showing _START_ to _END_ of _TOTAL_ items",
                        "infoEmpty": "No items available",
                        "infoFiltered": "(filtered from _MAX_ total items)",
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next"
                        }
                    },
                    "dom": '<"flex flex-col sm:flex-row justify-between items-center mb-4"l<"ml-2"f>>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"ip>',
                    "classes": {
                        "sLength": "relative inline-block",
                        "sFilter": "relative inline-block",
                        "sProcessing": "text-center py-4",
                        "sInfo": "text-sm text-gray-700",
                        "sPaging": "relative z-0 inline-flex shadow-sm rounded-md",
                        "paginate": {
                            "previous": "relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50",
                            "next": "relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50"
                        },
                        "sPageButton": "relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50",
                        "sPageButtonActive": "z-10 bg-blue-50 border-blue-500 text-blue-600",
                        "sPageButtonDisabled": "cursor-not-allowed opacity-50",
                        "sLengthSelect": "form-select rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50",
                        "sFilterInput": "form-input rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    }
                });
            });
        </script>
    @endpush
@endsection
