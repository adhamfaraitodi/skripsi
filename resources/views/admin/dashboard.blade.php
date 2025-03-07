@extends('admin.layouts.app')
@section('page_title', 'Dashboard')
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-people text-3xl text-blue-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Customer</h3>
                    <p class="text-2xl font-semibold">{{ $totalUsers ?? '0' }} <i class="ph ph-user pl-3"></i></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-people text-3xl text-blue-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Staff</h3>
                    <p class="text-2xl font-semibold">{{ $totalStaff ?? '0' }} <i class="ph ph-users-three pl-3"></i></p>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-clock text-3xl text-yellow-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Order Pending</h3>
                    <p class="text-2xl font-semibold">{{ $dataOrders['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-check-circle text-3xl text-green-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Order Success</h3>
                    <p class="text-2xl font-semibold">{{ $dataOrders['success'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <i class="bi bi-x-circle text-3xl text-red-600"></i>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Order Cancelled</h3>
                    <p class="text-2xl font-semibold">{{ $dataOrders['cancelled'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Recent Order Activity</h3>
        <canvas id="activityChart" class="max-w-full" style="max-width: full; max-height: 400px;"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('activityChart').getContext('2d');
            var activityData = @json($activity);
            var labels = Object.keys(activityData).map(date => {
                return moment(date).format('dddd');
            });
            var data = Object.values(activityData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Order Total',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection

