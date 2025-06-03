@extends('user.layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto p-3 sm:p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Checkout Order</h2>
        <div class="mb-4 bg-gray-50 p-3 rounded-lg">
            <p class="text-base sm:text-lg font-semibold">Order ID: {{ $order_id }}</p>
            <p class="text-base sm:text-lg">Table: {{ $table->number }}</p>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block">
            <table class="w-full shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-500 to-green-600 text-white">
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
                            $totalBeforeDiscount += ($item['original_price'] * $item['quantity']);
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
                            <td class="px-4 py-3 text-right text-gray-700">Rp{{ number_format($item['original_price'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp{{ number_format($item['original_price'] * $item['quantity'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View (visible only on mobile) -->
        <div class="md:hidden space-y-4">
            @php
                $totalBeforeDiscount = 0;
                $totalDiscount = 0;
            @endphp
            @foreach ($cart as $item)
                @php
                    $totalBeforeDiscount += ($item['original_price'] * $item['quantity']);
                    $totalDiscount += ($item['discount'] * $item['quantity']);
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex space-x-3">
                        <img src="{{ Storage::url($item['image_path']) }}" 
                            alt="{{ $item['name'] }}" 
                            class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-md shadow-sm flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 text-sm sm:text-base">{{ $item['name'] }}</h3>
                            <div class="mt-2 space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Price:</span>
                                    <span class="text-sm font-medium">Rp{{ number_format($item['original_price'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Quantity:</span>
                                    <span class="text-sm font-medium">{{ $item['quantity'] }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-1 border-t border-gray-200">
                                    <span class="text-sm font-medium text-gray-900">Subtotal:</span>
                                    <span class="text-sm font-semibold text-green-600">Rp{{ number_format($item['original_price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $grossAmount = $totalBeforeDiscount - $totalDiscount;
        @endphp

        <!-- Summary Section -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm sm:text-base font-medium text-gray-700">Total Price:</span>
                    <span class="text-sm sm:text-base font-semibold text-gray-900">Rp{{ number_format($totalBeforeDiscount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm sm:text-base font-medium text-gray-700">Total Discount:</span>
                    <span class="text-sm sm:text-base font-semibold text-red-600">- Rp{{ number_format($totalDiscount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-300 pt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-base sm:text-lg font-bold text-green-800">Grand Total:</span>
                        <span class="text-lg sm:text-xl font-bold text-green-800">Rp{{ number_format($grossAmount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 mt-5">
            <label for="order_note" class="block text-base sm:text-lg font-semibold mb-2">Order Note:</label>
            <textarea id="order_note" 
                      name="order_note" 
                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                      rows="3" 
                      placeholder="Add any special instructions here..."></textarea>
        </div>

        <button id="pay-button" 
                class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Confirm Order
        </button>
    </div>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

        <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            const button = this;
            button.disabled = true;
            button.textContent = "Processing...";

            fetch("{{ route('user.checkout.create') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    order_note: document.getElementById('order_note').value 
                })
            })
            .then(response => response.json())
            .then(data => {
                
                if (data.snap_token) {
                    
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert("Payment Successful!");
                            console.log(result);
                            window.location.href = "{{ route('order.success') }}";
                        },
                        onPending: function(result) {
                            alert("Waiting for your payment...");
                            console.log(result);
                    
                        },
                        onError: function(result) {
                            alert("Payment Failed!");
                            console.log(result);
                            button.disabled = false;
                            button.textContent = "Confirm Order";
                        },
                        onClose: function() {
                            alert("You closed the payment popup.");
                            button.disabled = false;
                            button.textContent = "Confirm Order";
                        }
                    });
                } else {
                    alert(data.message || "Unable to process payment. Please try again.");
                    button.disabled = false;
                    button.textContent = "Confirm Order";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                button.disabled = false;
                button.textContent = "Confirm Order";
            });
        });
    </script>
@endsection
