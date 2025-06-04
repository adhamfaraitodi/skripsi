@extends('admin.layouts.app')
@section('page_title', 'Edit Food Data')
@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Food Data</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('food.update', $data->id) }}" method="post" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('post')
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <!-- Name -->
                        <div>
                            <label for="foodName" class="block text-sm font-medium text-gray-700">
                                Food Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="foodName"
                                   id="foodName"
                                   value="{{ $data->name }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <!-- Description -->
                        <div>
                            <label for="foodDesc" class="block text-sm font-medium text-gray-700">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="foodDesc"
                                      id="foodDesc"
                                      rows="3"
                                      required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $data->description }}</textarea>
                        </div>
                        <!-- Image -->
                        <div>
                            <label for="foodImg" class="block text-sm font-medium text-gray-700">
                                Food Image
                            </label>
                            <div class="mt-1 flex items-center">
                                <input type="file"
                                       id="foodImg"
                                       name="foodImg"
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                        <!-- Category Selection -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                @foreach($datas as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="foodPrice_display" class="block text-sm font-medium text-gray-700">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text"
                                    id="foodPrice_display"
                                    value="{{ $data->price}}"
                                    class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    oninput="formatNumber(this, 'foodPrice')"
                                    onblur="formatNumber(this, 'foodPrice')">
                                <input type="hidden" name="foodPrice" id="foodPrice" value="0" required>
                            </div>
                        </div>

                        <!-- Discount -->
                        <div>
                            <label for="foodDisc_display" class="block text-sm font-medium text-gray-700">
                                Discount <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text"
                                    id="foodDisc_display"
                                    value="{{ $data->discount}}"
                                    class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    oninput="formatNumber(this, 'foodDisc')"
                                    onblur="formatNumber(this, 'foodDisc')">
                                <input type="hidden" name="foodDisc" id="foodDisc" value="0" required>
                            </div>
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
    <script>
        function formatNumber(input, hiddenFieldId) {
            let value = input.value.replace(/[^\d]/g, '');
            let numValue = parseInt(value) || 0;
            let formatted = numValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = formatted;
            let hiddenInput = document.getElementById(hiddenFieldId);
            if (hiddenInput) {
                hiddenInput.value = numValue;
            }
            updateDisplay();
        }
        
        function updateDisplay() {
            document.getElementById('priceDisplay').textContent = document.getElementById('foodPrice').value;
            document.getElementById('discDisplay').textContent = document.getElementById('foodDisc').value;
        }
        document.addEventListener('DOMContentLoaded', function() {
            formatNumber(document.getElementById('foodPrice_display'), 'foodPrice');
            formatNumber(document.getElementById('foodDisc_display'), 'foodDisc');
        });
    </script>
@endsection
