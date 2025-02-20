@extends('user.layouts.app')
@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-center font-bold text-2xl text-gray-800 mb-4">Payment for Order #{{ $order->order_code }}</h2>
            <button id="pay-button" class="bg-green-600 hover:bg-green-700 transition duration-200 text-white font-bold py-3 px-6 rounded-lg items-center mx-auto block">
                Proceed to Payment
            </button>
        </div>
    </div>

    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            window.snap.pay("{{ $snapToken }}", {
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
                },
                onClose: function() {
                    alert("You closed the payment popup.");
                }
            });
        });
    </script>
@endsection
