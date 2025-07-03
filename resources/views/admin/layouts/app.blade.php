<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <!-- jQuery & DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <!-- jQuery Month Picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-ui-month-picker@3.0.4/src/MonthPicker.min.js"></script>

    <!-- css for jQuery Month Picker -->
    <style>
        .month-picker {
            z-index: 9999 !important;
            position: absolute !important;
            background: white !important;
            border: 1px solid #ccc !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
        }
        
        .month-picker table {
            background: white !important;
        }
        
        .month-picker .ui-state-active {
            background: #3b82f6 !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-white">
@include('admin.includes.sidebar')
<div class="lg:ml-[290px] p-4">
    @include('admin.includes.navbar')
    <main class="mt-4 pr-2">
        @yield('content')
    </main>
    @include('admin.includes.footer')
</div>

@stack('scripts')
</body>
</html>
