@extends('admin.layouts.app')
@section('page_title', 'Edit Food Category')
@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Food Category</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('category.update', $data->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('post')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <div>
                            <label for="categoryName" class="block text-sm font-medium text-gray-700">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="categoryName"
                                   id="categoryName"
                                   value="{{ $data->name }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Form Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="reset"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
