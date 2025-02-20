<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>YOSHIMIE</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-white">
<div class="lg:ml-[80px] md:mr-[5px] p-4">
    @include('user.includes.navbar')
    <main class="mt-4">
        @yield('content')
    </main>
    @include('admin.includes.footer')
</div>
@stack('scripts')
</body>
</html>
