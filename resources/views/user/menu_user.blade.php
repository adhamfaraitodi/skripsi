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
                                    <span class="text-red-600 line-through block">Rp {{ number_format($data->price, 0, ',', '.') }}</span>
                                    <span class="text-gray-500 font-bold block">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($data->price, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        {{ $data->description }}
                        <br>
                        <strong>Stock:</strong> {{ $data->stock }}
                    </x-slot>
                    <x-slot name="button">
                        <div class="flex justify-center items-center space-x-4">
                            <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-blue-gray-900 text-white"
                                    type="button"
                                    onclick="toggleStar(this, '{{ $data->id }}')">
                                <i class="bi {{ $data->is_favorite ? 'bi-star-fill' : 'bi-star' }} text-yellow-500 text-xl"></i>
                            </button>
                            <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-blue-gray-900 text-blue-gray-900"
                                    type="button"
                                    onclick="addToCart(event, '{{ $data->id }}')">
                                Add to Cart
                            </button>
                        </div>
                    </x-slot>
                </x-card>
            @endforeach
        </div>
    </div>
@endsection
<script>
    function toggleStar(button, menuId) {
        let starIcon = button.querySelector("i");

        fetch("{{ route('user.favorite') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ menu_id: menuId })
        }).then(response => response.json())
            .then(data => {
                if (data.status === "added") {
                    starIcon.classList.remove("bi-star");
                    starIcon.classList.add("bi-star-fill");
                } else if (data.status === "removed") {
                    starIcon.classList.remove("bi-star-fill");
                    starIcon.classList.add("bi-star");
                } else {
                    console.error("Unexpected response:", data);
                }
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

