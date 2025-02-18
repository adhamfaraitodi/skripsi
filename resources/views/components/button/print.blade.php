<div class="mt-6 flex justify-between items-center print:hidden">
    <div class="space-x-2">
        <button onclick="printSection();"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
            <i class="ph ph-printer mr-2"></i> Print
        </button>
    </div>
</div>
<div id="printable-content">
    <div class="flex items-center justify-center border-b border-gray-400 pb-5 mb-6">
        <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-10 mr-4">
        <div class="text-center">
            <h2 class="text-xl font-bold pr-11 mt-2 uppercase">YOSHIMIE</h2>
            <p class="text-sm text-gray-700">Jl. Kaliurang KM 11, Pedak, Sinduharjo, Kec. Ngaglik,
                Yogyakarta 55581</p>
            <p class="text-sm text-gray-700">Phone: 081250514071 | Email: bakmiehotplate@gmail.com</p>
        </div>
    </div>

    <div class="text-center mb-6">
        <h1 class="text-1xl font-semibold uppercase">{{$title}}</h1>
        <p class="text-lg text-gray-600">Bulan: {{ Carbon\Carbon::now()->format('F Y') }}</p>
    </div>
