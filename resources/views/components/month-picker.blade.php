<div class="relative w-48">
    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
        <input type="text" id="monthPicker" name="month_year"
            class="border px-2 py-1 rounded w-full" 
            placeholder="Select Month" 
            value="{{ request('month_year') }}"
            readonly>
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
            Filter
        </button>
    </form>

    <script>
        $(function () {
            $("#monthPicker").MonthPicker({
                Button: false,
                MonthFormat: 'mm-yy',
            });
        });
    </script>
</div>
