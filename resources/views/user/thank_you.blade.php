@extends('user.layouts.app')
@section('content')
<div class="flex flex-col items-center justify-center min-h-screen">
    <img src="{{ asset('storage/icon/icon.png') }}" alt="Restaurant Logo" class="w-19 mb-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Thank You for Your Order!</h1>
    <p class="text-lg text-gray-600 mb-6">Your order has been received. We will prepare it shortly.</p>
    <a href="{{ route('user.table') }}" 
       class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md transition duration-200 hover:bg-green-700 active:bg-green-800">
        Back to Table
    </a>
</div>
@endsection
