@extends('user.layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-4">Checkout Order</h2>
        <div class="mb-4">
            <p class="text-lg font-semibold">Order ID: {{ $order_id }}</p>
            <p class="text-lg">Table: {{ $table->number }}</p>
        </div>
        <table class="w-full border-collapse border border-gray-300 mb-4">
            <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Image</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Quantity</th>
                <th class="border p-2">Price</th>
                <th class="border p-2">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalBeforeDiscount = 0;
                $totalDiscount = 0;
            @endphp
            @foreach ($cart as $item)
                @php
                    $totalBeforeDiscount += ($item['price'] * $item['quantity']);
                    $totalDiscount += ($item['discount'] * $item['quantity']);
                @endphp
                <tr>
                    <td class="border p-2 text-center">
                        <img src="{{ Storage::url($item['image_path']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover">
                    </td>
                    <td class="border p-2">{{ $item['name'] }}</td>
                    <td class="border p-2 text-center">{{ $item['quantity'] }}</td>
                    <td class="border p-2">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="border p-2">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @php
                $grossAmount = $totalBeforeDiscount - $totalDiscount;
            @endphp
            </tbody>

            <tfoot class="bg-gray-100 font-semibold">
            <tr>
                <td colspan="4" class="border p-2 text-right">Total Price :</td>
                <td class="border p-2">Rp{{ number_format($totalBeforeDiscount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="border p-2 text-right">Total Discount :</td>
                <td class="border p-2 text-red-600">- Rp{{ number_format($totalDiscount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="border p-2 text-right ">Total :</td>
                <td class="border p-2 text-lg text-green-600">Rp{{ number_format($grossAmount, 0, ',', '.') }}</td>
            </tr>
            </tfoot>
        </table>

        <div class="mb-4">
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
                        window.location.href = data.redirect_url; // Redirect to payment page
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
