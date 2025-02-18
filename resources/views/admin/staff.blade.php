@extends('admin.layouts.app')
@section('page_title', 'Staff Management')
@section('content')
    <div class="p-6">
        <x-button.add>
            <x-slot name="route">{{ route('staff.create')}}</x-slot>
            <x-slot name="massage">Register New Staff</x-slot>
        </x-button.add>
        <x-table-data :name="'Staff Management'">
            <x-slot name="column">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telephone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </x-slot>
            <x-slot name="row">
                @forelse($datas as $key =>$data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $key + 1 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 text-sm font-medium text-gray-900">{{ $data->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->role->name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->email}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-48 h-32 overflow-hidden">
                                <img src="{{ Storage::url($data->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->telephone_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $data->address }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('staff.edit', $data->id) }}" class="text-blue-600 hover:text-blue-900 text-lg p-2">
                                    <i class="ph ph-note-pencil"></i>
                                </a>
                                <form action="{{ route('staff.remove', $data->id) }}" method="POST" onsubmit="return confirm('Do you want to delete this Staff?');">
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
            <x-slot name="scripting">

            </x-slot>
        </x-table-data>
    </div>
@endsection
