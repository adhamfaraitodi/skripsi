@extends('admin.layouts.app')
@section('page_title', 'Food Menu Management')
@section('content')
    <div class="p-6">
        <x-button.add>
            <x-slot name="route">{{ route('food.create') }}</x-slot>
            <x-slot name="massage">Add New Food</x-slot>
        </x-button.add>
    <x-table-data :name="'Table Management'">
        <x-slot name="column">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Favorites</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
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
                        <div class="text-sm text-gray-900">Rp {{ number_format($data->price, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($data->discount > 0)
                            <span class="px-2 inline-flex text-sm ">Rp {{ number_format($data->discount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-sm text-gray-500">Rp 0</span>
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
                            <form action="{{ route('food.remove', $data->id) }}" method="POST" onsubmit="return confirm('Do you want to delete this food?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-lg p-2">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
            @endforelse
        </x-slot>
        <x-slot name="scripting"></x-slot>
        </x-table-data>
    </div>
@endsection
