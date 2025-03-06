@extends('user.layouts.app')
@section('content')
    <div class="p-6">
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
                                <i class="bi {{ $data->favorite > 0 ? 'bi-heart-fill' : 'bi-heart' }} text-red-500 text-xl"></i>
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
</script>

