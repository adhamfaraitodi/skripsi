@extends('admin.layouts.app')
@section('page_title', 'Trash Food Menu Management')
@section('content')
    <div class="p-6">
        <x-table-data :name="'Trash Food Menu Items'">
            <x-slot name="column">
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
            </x-slot>
            <x-slot name="row">
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
                                <form action="{{ route('food.back', $data->id) }}" method="POST" onsubmit="return confirm('Do you want to put back this food?');">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="text-green-600 hover:text-green-900 text-lg p-2">
                                        <i class="ph ph-clock-clockwise"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </x-slot>
            <x-slot name="scripting">
            </x-slot>
        </x-table-data>
    </div>
@endsection
