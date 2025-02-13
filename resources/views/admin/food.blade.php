@extends('admin.layouts.app')
@section('page_title', 'Food Menu Management')
@section('content')
    <div class="p-6">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('food.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="bi bi-plus-lg mr-2"></i>Add New Food
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Food Menu Items</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="foodMenuTable" class="min-w-full divide-y divide-gray-200 border-separate">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Favorites</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($datas as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $data->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-48 text-sm font-medium text-gray-900">{{ $data->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-48 h-32 overflow-hidden">
                                    <img src="{{ Storage::url($data->image_path) }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $data->category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $data->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($data->price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($data->discount > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $data->discount }}%
                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="bi bi-heart-fill text-red-500 mr-1"></i>
                                    <span class="text-sm text-gray-900">{{ $data->favorite }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $data->status == 1 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $data->status == 1 ? 'Show' : 'Hidden' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $data->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('food.edit', $data->id) }}" class="text-blue-600 hover:text-blue-900 text-lg p-2">
                                        <i class="ph ph-note-pencil"></i>
                                    </a>
                                    <form action="{{ route($data->status == 1 ? 'food.destroy' : 'food.restore', $data->id) }}" method="POST" class="inline" onsubmit="return confirm('Do you want to change food menu visibility');">
                                        @csrf
                                        @method('post')
                                        <button type="submit" class="text-lg p-2 {{ $data->status == 1 ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                            <i class="ph {{ $data->status == 1 ? 'ph-eye-closed' : 'ph-eye' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                No menu items found
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
            document.addEventListener('DOMContentLoaded', function() {
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#foodMenuTable').DataTable({
                    "paging": true,
                    "pagingType": "simple",
                    "lengthMenu": [5, 10, 25, 50],
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "language": {
                        "search": "Search menu:",
                        "lengthMenu": "Show _MENU_ items per page",
                        "zeroRecords": "No matching menu items found",
                        "info": "Showing _START_ to _END_ of _TOTAL_ items",
                        "infoEmpty": "No items available",
                        "infoFiltered": "(filtered from _MAX_ total items)",
                        "pagination":{
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
                            "next": "relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50",
                            "first": "hidden",
                            "last": "hidden"
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
