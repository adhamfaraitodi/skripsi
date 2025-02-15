@extends('admin.layouts.app')
@section('page_title', 'Food Category Management')
@section('content')
    <div class="p-6">
        @auth
            @if(auth()->user()->role_id == 1)
                <div class="mb-6 flex justify-between items-center">
                    <a href="{{ route('category.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="ph ph-plus mr-2"></i>Add New Category
                    </a>
                </div>
            @endif
        @endauth
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Food Category</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
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
                                    <i class="ph ph-note-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($datas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $datas->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
