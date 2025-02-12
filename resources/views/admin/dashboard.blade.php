@extends('admin.layouts.app')
@section('page_title', 'Dashboard')
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-people text-3xl text-blue-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                    <p class="text-2xl font-semibold">{{ $totalUsers ?? '0' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Recent Activity</h3>
    </div>
@endsection

