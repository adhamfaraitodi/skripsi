@extends('user.layouts.app')
@section('content')
    <div class="p-4 sm:p-6">
        <!-- Search Section -->
        <div class="flex justify-center mb-6 px-2">
            <div class="relative w-full max-w-3xl">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search menu by name, keyword, or category..."
                    class="w-full rounded-2xl border border-gray-300 py-2 pl-4 pr-10 text-sm focus:outline-none"
                    value="{{ request('query') }}"
                />
                <button
                    id="searchButton"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M21 21L16.514 16.506M19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <button
                    id="clearButton"
                    class="absolute right-10 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 {{ request('query') ? '' : 'hidden' }}"
                >
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M18 6L6 18M6 6L18 18"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 justify-center">
            @foreach ($datas as $data)
                <x-card>
                    <x-slot name="content">
                        <img src="{{ Storage::url($data->image_path) }}" class="w-full h-full object-cover rounded-xl">
                    </x-slot>

                    <x-slot name="tittle">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <p class="font-sans text-sm sm:text-base font-medium text-blue-gray-900 flex-1">
                                {{ $data->name }}
                            </p>
                            @php
                                $discountedPrice = $data->price - $data->discount;
                            @endphp
                            <div class="text-right whitespace-nowrap">
                                @if ($data->discount > 0)
                                    <span class="text-red-600 line-through text-xs block">Rp {{ number_format($data->price, 0, ',', '.') }}</span>
                                    <span class="text-black font-bold text-sm block">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-black font-bold text-sm block">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </x-slot>

                    <x-slot name="description">
                        <p class="text-sm leading-snug">
                            {{ $data->description }}<br>
                            <span class="font-medium">Stock:</span> {{ $data->stock === null ? 'N/A' : $data->stock }}<br>
                            <span class="font-medium">Category:</span> {{ $data->category->name }}
                        </p>
                    </x-slot>

                    <x-slot name="button">
                        <div class="flex flex-wrap justify-center sm:justify-between items-center gap-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-black text-sm">{{ $data->favorite }}</span>
                                <button class="font-sans font-bold text-xl text-red-500"
                                        type="button"
                                        onclick="toggleHeart(this, '{{ $data->id }}')">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>

                            @if ($data->stock === null)
                                <button class="font-sans font-bold py-2 px-4 rounded-lg bg-gray-500 text-white text-sm cursor-not-allowed"
                                        type="button" disabled>
                                    Not Available
                                </button>
                            @elseif ($data->stock == 0)
                                <button class="font-sans font-bold py-2 px-4 rounded-lg bg-red-500 text-white text-sm cursor-not-allowed"
                                        type="button" disabled>
                                    Sold Out
                                </button>
                            @else
                                <button class="font-sans font-bold py-2 px-4 rounded-lg bg-blue-500 text-white text-sm"
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const clearButton = document.getElementById('clearButton');
        
        function handleSearch() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                const url = new URL(window.location.href);
                url.searchParams.set('query', searchQuery);
                window.location.href = url.toString();
            }
        }
        
        function handleClearSearch() {
            const url = new URL(window.location.href);
            url.searchParams.delete('query');
            window.location.href = url.toString();
        }
        
        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        }
        
        searchButton.addEventListener('click', handleSearch);
        clearButton.addEventListener('click', handleClearSearch);
        searchInput.addEventListener('keydown', handleKeyPress);
        searchInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
        });
    });
</script>

