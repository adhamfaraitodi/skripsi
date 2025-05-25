@extends('user.layouts.app')
@section('content')
    <div class="p-6">
        <!-- Search Section -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center w-full max-w-3xl">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search menu by name, keyword, or category..."
                    class="flex-grow focus:outline-none rounded-2xl px-3 py-2 border-[1px] border-gray-300"
                    value="{{ request('query') }}"
                />
                <button
                    id="searchButton"
                    class="ml-2 text-gray-500 hover:text-gray-700"
                >
                    <!-- Magnifying Glass SVG Icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button
                    id="clearButton"
                    class="ml-2 text-gray-500 hover:text-gray-700 {{ request('query') ? '' : 'hidden' }}"
                >
                    <!-- X Icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-5 justify-center">
            @foreach ($datas as $data)
                <x-card>
                    <x-slot name="content">
                        <img src="{{ Storage::url($data->image_path) }}" class="w-full h-full object-cover">
                    </x-slot>
                    <x-slot name="tittle">
                        <div class="flex items-center justify-between mb-2">
                            <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-900">
                                {{ $data->name }}
                            </p>
                            @php
                                $discountedPrice = $data->price - $data->discount;
                            @endphp
                            <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-900">
                                @if ($data->discount > 0)
                                    <span class="text-red-600 line-through block text-sm">Rp {{ number_format($data->price, 0, ',', '.') }}</span>
                                    <span class="text-black font-bold block">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-black font-bold block">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @endif
                            </p>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        {{ $data->description }}
                        <br>
                        <span class="font-medium">Stock:</span> 
                        {{ $data->stock === null ? 'N/A' : $data->stock }}<br>
                        <span class="font-medium">Category:</span> {{ $data->category->name }}
                    </x-slot>
                    <x-slot name="button">
                        <div class="flex justify-center items-center space-x-3">
                        <span class="text-black">{{ $data->favorite }}</span>
                        <button class="font-sans font-bold py-3 pr-10"
                            type="button"
                            onclick="toggleHeart(this, '{{ $data->id }}')">
                            <i class="bi bi-heart text-red-500 text-xl"></i>
                        </button>
                            @if ($data->stock === null)
                                <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-gray-500 text-white cursor-not-allowed"
                                        type="button"
                                        disabled>
                                    Not Available
                                </button>
                            @elseif ($data->stock == 0)
                                <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-red-500 text-white cursor-not-allowed"
                                        type="button"
                                        disabled>
                                    Sold Out
                                </button>
                            @else
                            <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-blue-500 text-white"
                                    type="button"
                                    onclick="addToCart(event, '{{ $data->id }}')">
                                Add to Cart
                            </button>
                            @endif
                        </div>
                    </x-slot>
                </x-card>
            @endforeach
        </div>
    </div>
@endsection
<script>
    function toggleHeart(button, menuId) {
        let heartIcon = button.querySelector("i");
        let favoriteCountSpan = button.previousElementSibling;
        let isCurrentlyFavorited = heartIcon.classList.contains("bi-heart-fill");

        fetch("{{ route('user.favorite') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ 
                menu_id: menuId,
                is_favorite: !isCurrentlyFavorited
            })
        }).then(response => response.json())
        .then(data => {
            if (data.status === "added") {
                heartIcon.classList.remove("bi-heart");
                heartIcon.classList.add("bi-heart-fill");
            } else if (data.status === "removed") {
                heartIcon.classList.remove("bi-heart-fill");
                heartIcon.classList.add("bi-heart");
            }
            favoriteCountSpan.textContent = data.favorite_count;
        }).catch(error => console.error("Error:", error));
    }

    function addToCart(event, itemId) {
        event.preventDefault();

        fetch("{{ route('user.add-cart') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: itemId })
        }).then(response => response.json())
            .then(data => {
                alert(data.message);
            }).catch(error => console.error("Error:", error));
    }
    
    //search guery
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const clearButton = document.getElementById('clearButton');
        
        // Handle search function
        function handleSearch() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                const url = new URL(window.location.href);
                url.searchParams.set('query', searchQuery);
                window.location.href = url.toString();
            }
        }
        
        // Handle clear search function
        function handleClearSearch() {
            const url = new URL(window.location.href);
            url.searchParams.delete('query');
            window.location.href = url.toString();
        }
        
        // Handle enter key press
        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        }
        
        // Event listeners
        searchButton.addEventListener('click', handleSearch);
        clearButton.addEventListener('click', handleClearSearch);
        searchInput.addEventListener('keydown', handleKeyPress);
        
        // Show/hide clear button based on input value
        searchInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
        });
    });
</script>

