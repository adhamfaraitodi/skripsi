@extends('user.layouts.app')
@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">Shopping Cart</h2>

            @if(count($cart) > 0)
                <div class="space-y-4">
                    @foreach($cart as $id => $details)
                        <div class="flex items-center justify-between border p-4 rounded">
                            <div class="flex items-center space-x-4">
                                <img src="{{ Storage::url($details['image_path']) }}" class="w-20 h-20 object-cover rounded">
                                <div>
                                    <h3 class="font-bold">{{ $details['name'] }}</h3>
                                    <p>Price: Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <button onclick="updateQuantity({{ $id }}, 'decrease')"
                                                class="px-3 py-1 bg-gray-200 rounded">
                                            <i class="ph ph-minus text-sm"></i>
                                        </button>
                                        <span id="quantity-{{ $id }}" class="px-4">
                                            {{ $details['quantity'] }}
                                        </span>
                                        <button onclick="updateQuantity({{ $id }}, 'increase')"
                                                class="px-3 py-1 bg-gray-200 rounded">
                                            <i class="ph ph-plus text-sm"></i>
                                        </button>
                                        <button onclick="removeFromCart({{ $id }})"
                                                class="px-3 py-1 bg-red-200 text-red-600 rounded ml-2">
                                            <i class="ph ph-x text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold" id="subtotal-{{ $id }}">
                                    Subtotal: Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="border-t pt-4">
                        <div class="text-right">
                            <p class="text-lg font-bold pr-4" id="total-amount">Grand Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex space-x-4 mt-4">
                            <a href="{{ route('user.dine-in') }}">
                                <button class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-gray-200 text-gray-800">
                                    Back to Menu
                                </button>
                            </a>
                            <a href="{{ route('user.checkout') }}" class="font-sans font-bold text-center py-3 px-6 rounded-lg bg-blue-600 text-white">
                                Checkout Order
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <p>Your cart is empty</p>
            @endif
        </div>
    </div>

    <script>
        function updateQuantity(menuId, action) {
            const quantityElement = document.getElementById(`quantity-${menuId}`);
            let quantity = parseInt(quantityElement.textContent);

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            fetch("{{ route('user.update-cart') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    quantity: quantity
                })
            }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        quantityElement.textContent = quantity;
                        document.getElementById(`subtotal-${menuId}`).textContent =
                            `Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(data.subtotal)}`;
                        document.getElementById('total-amount').textContent =
                            `Grand Total: Rp ${new Intl.NumberFormat('id-ID').format(data.total)}`;
                    }
                }).catch(error => console.error("Error:", error));
        }

        function removeFromCart(menuId) {
            fetch("{{ route('user.remove-cart') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    menu_id: menuId
                })
            }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload(); // Reload the page to reflect changes
                    }
                }).catch(error => console.error("Error:", error));
        }
    </script>
@endsection
