@extends('admin.layouts.app')
@section('page_title', 'Food Category Management')
@section('content')
    <div class="p-6">
        <x-button.add>
            <x-slot name="route">{{ route('category.create') }}</x-slot>
            <x-slot name="massage">Add New Category</x-slot>
        </x-button.add>
        <x-table-data :name="'Food Category'">
            <x-slot name="column">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </x-slot>
            <x-slot name="row">
                @forelse($datas as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ $data->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('category.edit', $data->id) }}"
                               class="text-blue-600 hover:text-blue-900">
                                <i class="ph ph-note-pencil text-xl"></i>
                            </a>
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
