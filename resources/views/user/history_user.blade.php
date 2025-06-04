@extends('user.layouts.app')
@section('content')
    <div class="container mx-auto p-3 sm:p-4">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Order History</h2>

        @if ($datas->isEmpty())
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <i class="ph ph-clock-counter-clockwise text-6xl"></i>
                </div>
                <p class="text-gray-600 text-lg">No order history available.</p>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-3 px-4 border text-center font-semibold w-32">Order ID</th>
                                <th class="py-3 px-4 border text-center font-semibold w-24">Status</th>
                                <th class="py-3 px-4 border text-center font-semibold w-40">Total Amount</th>
                                <th class="py-3 px-4 border text-center font-semibold w-[30%]">Note</th>
                                <th class="py-3 px-4 border text-center font-semibold w-40">Time</th>
                                <th class="py-3 px-4 border text-center font-semibold w-24">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 border font-mono text-center text-sm w-32">{{ $order->order_code }}</td>
                                    <td class="py-3 px-4 border text-center w-24">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                            @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 border text-center font-semibold w-40">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 border w-[30%]">
                                        <p class="line-clamp-3 break-words text-sm">
                                            {{ $order->note ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="py-3 px-4 border text-center w-40">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="py-3 px-4 border text-center w-24">
                                        @if($order->order_status === 'pending')
                                           <button class="pay-button w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                data-order-code="{{ $order->order_code }}">
                                                Continue Payment
                                            </button>
                                        @else
                                            <x-pop-up>
                                                <x-slot name="id">order-detail-{{ $order->id }}</x-slot>
                                                <x-slot name="title">Order Detail</x-slot>
                                                <x-slot name="content">
                                                    <div class="flex justify-end mb-2">
                                                        <button type="button" 
                                                                class="p-2 rounded hover:bg-gray-100" 
                                                                title="Download Order as Image"
                                                                onclick="downloadOrderAsImage({{ $order->id }})"
                                                                id="download-btn-{{ $order->id }}">
                                                            <i class="ph ph-download text-2xl"></i>
                                                        </button>
                                                    </div>
                                                    @foreach($order->menus as $menuOrder)
                                                        <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                                            <p class="text-sm text-gray-800 font-semibold">
                                                                {{ $menuOrder->quantity }} x {{ $menuOrder->menu->name }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 italic">Price: Rp {{ number_format($menuOrder->price, 2) }}</p>
                                                            <p class="text-sm text-gray-500 italic">Subtotal: Rp {{ number_format($menuOrder->subtotal, 2) }}</p>
                                                        </div>
                                                    @endforeach
                                                    <h4 class="text-gray-700 font-semibold text-xl pl-4 mb-2 mt-4">Payment Detail</h4>
                                                    <div class="px-4 py-2 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                                        <p class="text-sm text-gray-800 font-semibold">
                                                            {{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('Y-m-d H:i:s') : 'N/A' }} 
                                                            - ID : {{ optional($order->payment)->transaction_id ?? 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-500 italic">Payment: {{ $order->payment->payment_type ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-500 italic">Grand Total: Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 2) : 'N/A' }}</p>
                                                    </div>
                                                </x-slot>
                                            </x-pop-up>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4">
                @foreach ($datas as $order)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <!-- Order Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Order #{{ $order->order_code }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount:</span>
                                <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($order->note)
                                <div class="flex justify-between items-start">
                                    <span class="text-sm text-gray-600">Note:</span>
                                    <span class="text-sm text-gray-700 text-right flex-1 ml-2">{{ $order->note }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- View Details Button -->
                        <div class="pt-3 border-t border-gray-200">
                            @if($order->order_status === 'pending')
                                <button class="pay-button w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-order-code="{{ $order->order_code }}">
                                    Continue Payment
                                </button>
                            @else
                            <x-pop-up>
                                <x-slot name="id">order-detail-mobile-{{ $order->id }}</x-slot>
                                <x-slot name="title">Order Detail</x-slot>
                                <x-slot name="content">
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <h3 class="font-semibold text-gray-900">Order #{{ $order->order_code }}</h3>
                                            <button type="button" 
                                                class="p-2 rounded hover:bg-gray-100" 
                                                title="Download Order as Image"
                                                onclick="downloadOrderAsImage({{ $order->id }})"
                                                id="download-btn-{{ $order->id }}">
                                                <i class="ph ph-download text-2xl"></i>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                                        <div class="mt-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if ($order->order_status == 'paid') bg-green-100 text-green-800
                                                @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <h4 class="text-gray-700 font-semibold text-lg mb-3">Order Items</h4>
                                    @foreach($order->menus as $menuOrder)
                                        <div class="px-3 py-3 bg-white rounded-md mb-2 shadow-sm border border-gray-200">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-800 font-semibold">
                                                        {{ $menuOrder->quantity }}x {{ $menuOrder->menu->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($menuOrder->price, 0, ',', '.') }} each</p>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($menuOrder->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <h4 class="text-gray-700 font-semibold text-lg mt-6 mb-3">Payment Details</h4>
                                    <div class="px-3 py-3 bg-white rounded-md shadow-sm border border-gray-200">
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Transaction ID:</span>
                                                <span class="text-sm font-mono">{{ optional($order->payment)->transaction_id ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Payment Method:</span>
                                                <span class="text-sm">{{ $order->payment->payment_type ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Settlement Time:</span>
                                                <span class="text-sm">{{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('d M Y, H:i') : 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                                <span class="text-sm font-semibold text-gray-900">Grand Total:</span>
                                                <span class="text-sm font-bold text-green-600">Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 0, ',', '.') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </x-slot>
                            </x-pop-up>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- hidden printable html as image -->
            <div id="printable-order-{{ $order->id }}" class="fixed -top-[9999px] left-0 bg-white p-6 w-[400px] shadow-lg">
                <!-- Header Section -->
                <div class="flex items-center justify-center border-b border-gray-400 pb-5 mb-6">
                    <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-10 mr-4">
                    <div class="text-center">
                        <h2 class="text-xl font-bold pr-11 mt-2 uppercase">YOSHIMIE</h2>
                        <p class="text-sm text-gray-700">Jl. Kaliurang KM 11, Pedak, Sinduharjo, Kec. Ngaglik,</p>
                        <p class="text-sm text-gray-700">Yogyakarta 55581</p>
                        <p class="text-sm text-gray-700">Phone: 081250514071 | Email: bakmiehotplate@gmail.com</p>
                    </div>
                </div>

                <!-- Order Header -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold text-gray-900 text-lg">Order #{{ $order->order_code }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if ($order->order_status == 'paid') bg-green-100 text-green-800
                            @elseif ($order->order_status == 'success') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                </div>

                <!-- Order Items Section -->
                <div class="mb-6">
                    <h4 class="text-gray-900 font-bold text-lg mb-4 border-b border-gray-300 pb-2">Order Items</h4>
                    @foreach($order->menus as $menuOrder)
                        <div class="flex justify-between items-start py-2 border-b border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm text-gray-800 font-semibold">
                                    {{ $menuOrder->quantity }}x {{ $menuOrder->menu->name }}
                                </p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($menuOrder->price, 0, ',', '.') }} each</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($menuOrder->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Payment Details Section -->
                <div class="mb-6">
                    <h4 class="text-gray-900 font-bold text-lg mb-4 border-b border-gray-300 pb-2">Payment Details</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Transaction ID:</span>
                            <span class="text-sm font-mono">{{ optional($order->payment)->transaction_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Payment Method:</span>
                            <span class="text-sm">{{ $order->payment->payment_type ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Settlement Time:</span>
                            <span class="text-sm">{{ optional($order->payment)->settlement_time ? \Carbon\Carbon::parse($order->payment->settlement_time)->format('d M Y, H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="border-t-2 border-gray-400 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                        <span class="text-lg font-bold text-green-600">Rp {{ optional($order->payment)->gross_amount ? number_format(optional($order->payment)->gross_amount, 0, ',', '.') : 'N/A' }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500">Thank you for your order!</p>
                    <p class="text-xs text-gray-500">Generated on {{ now()->format('d M Y, H:i') }}</p>
                </div>
            </div>
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
    document.querySelectorAll('.pay-button').forEach(button => {
        button.addEventListener('click', function () {
            const orderCode = this.dataset.orderCode;
            const btn = this;
            btn.disabled = true;
            btn.textContent = "Processing...";

            fetch("{{ route('user.continue-payment') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ order_code: orderCode })
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
                            btn.disabled = false;
                            btn.textContent = "Continue Payment";
                        },
                        onClose: function() {
                            alert("You closed the payment popup.");
                            btn.disabled = false;
                            btn.textContent = "Continue Payment";
                        }
                    });
                } else {
                    alert(data.message || "Unable to continue payment. Try again.");
                    btn.disabled = false;
                    btn.textContent = "Continue Payment";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                btn.disabled = false;
                btn.textContent = "Continue Payment";
            });
        });
    });
    </script>
    <!-- generate image based on hidden html -->
    <script>
    async function downloadOrderAsImage(orderId) {
        const button = document.getElementById(`download-btn-${orderId}`);
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="ph ph-spinner-gap text-2xl animate-spin"></i>';
        button.disabled = true;
        
        try {
            const element = document.getElementById(`printable-order-${orderId}`);
            element.style.position = 'fixed';
            element.style.top = '0';
            element.style.left = '0';
            element.style.zIndex = '9999';
            element.style.background = 'white';
            
            await new Promise(resolve => setTimeout(resolve, 100));
            const canvas = await html2canvas(element, {
                backgroundColor: '#ffffff',
                scale: 2,
                useCORS: true,
                allowTaint: true,
                logging: false,
                width: element.offsetWidth,
                height: element.offsetHeight
            });
            element.style.position = 'fixed';
            element.style.top = '-9999px';
            element.style.left = '0';
            element.style.zIndex = '1';
            const link = document.createElement('a');
            link.download = `order-${orderId}-${new Date().getTime()}.png`;
            link.href = canvas.toDataURL('image/png');
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
        } catch (error) {
            console.error('Error generating image:', error);
            alert('Failed to generate image. Please try again.');
        } finally {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }
</script>
@endsection
