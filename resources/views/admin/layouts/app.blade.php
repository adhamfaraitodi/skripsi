<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white">
<span class="absolute text-white text-4xl top-5 left-4 cursor-pointer" onclick="openSidebar()">
        <i class="bi bi-filter-left px-2 bg-gray-900 rounded-md"></i>
    </span>
@include('admin.includes.sidebar')
<div class="lg:ml-[300px] p-4">
    @include('admin.includes.navbar')
    <main class="mt-4">
        @yield('content')
    </main>
    @include('admin.includes.footer')
</div>
@stack('scripts')
</body>
</html>
