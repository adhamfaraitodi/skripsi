@extends('user.layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Checkout Order</h2>
        <div class="mb-4">
            <p class="text-lg font-semibold">Order ID: {{ $order_id }}</p>
            <p class="text-lg">Table: {{ $table->number }}</p>
        </div>
        <table class="w-full shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                    <th class="px-4 py-3 text-left font-semibold tracking-wider">Image</th>
                    <th class="px-4 py-3 text-left font-semibold tracking-wider">Name</th>
                    <th class="px-4 py-3 text-center font-semibold tracking-wider">Quantity</th>
                    <th class="px-4 py-3 text-right font-semibold tracking-wider">Price</th>
                    <th class="px-4 py-3 text-right font-semibold tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    $totalBeforeDiscount = 0;
                    $totalDiscount = 0;
                @endphp
                @foreach ($cart as $item)
                    @php
                        $totalBeforeDiscount += ($item['price'] * $item['quantity']);
                        $totalDiscount += ($item['discount'] * $item['quantity']);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3">
                            <img src="{{ Storage::url($item['image_path']) }}" 
                                alt="{{ $item['name'] }}" 
                                class="w-20 h-14 object-cover rounded-md shadow-sm">
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item['name'] }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $item['quantity'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>

            @php
                $grossAmount = $totalBeforeDiscount - $totalDiscount;
            @endphp

            <tfoot class="bg-gray-100">
                <tr>
                    <td colspan="4" class="px-4 py-3 text-right font-medium text-gray-700">Total Price:</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp{{ number_format($totalBeforeDiscount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="px-4 py-3 text-right font-medium text-gray-700">Total Discount:</td>
                    <td class="px-4 py-3 text-right font-semibold text-red-600">- Rp{{ number_format($totalDiscount, 0, ',', '.') }}</td>
                </tr>
                <tr class="bg-green-50">
                    <td colspan="4" class="px-4 py-3 text-right font-bold text-green-800 text-lg">Grand Total:</td>
                    <td class="px-4 py-3 text-right font-bold text-green-800 text-xl">Rp{{ number_format($grossAmount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mb-4 mt-5">
            <label for="order_note" class="block text-lg font-semibold">Order Note:</label>
            <textarea id="order_note" name="order_note" class="w-full p-2 border border-gray-300 rounded" rows="3" placeholder="Add any special instructions here..."></textarea>
        </div>

        <button id="checkoutButton" onclick="submitOrder()"
                class="w-full bg-green-600 text-white font-bold py-3 px-6 rounded-lg">
            Confirm Order
        </button>
    </div>

    <script>
        function submitOrder() {
            const note = document.getElementById('order_note').value;
            const button = document.getElementById('checkoutButton');

            button.disabled = true;
            button.textContent = "Processing...";

            fetch("{{ route('user.checkout.create') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ order_note: note })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                    button.disabled = false;
                    button.textContent = "Confirm Order";
                });
        }
    </script>
@endsection
