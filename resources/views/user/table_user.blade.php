@extends('user.layouts.app')
@section('content')
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-6 gap-5 justify-center">
            @foreach ($datas as $data)
                <x-card>
                    <x-slot name="content">
                        <div id="qrcode-{{ $data->table_code }}"></div>
                    </x-slot>
                    <x-slot name="tittle">
                        <div class="flex items-center justify-center mb-2">
                            <p class="block font-sans text-base antialiased font-medium leading-relaxed text-blue-gray-900">
                                Table Number {{ $data->number }}
                            </p>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                    </x-slot>
                    <x-slot name="button">
                        <a href="{{ route('user.scan', ['id' => $data->table_code]) }}">
                            <button
                                class="align-middle select-none font-sans font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg shadow-gray-900/10 hover:shadow-gray-900/20 focus:opacity-[0.85] active:opacity-[0.85] active:shadow-none block w-full bg-blue-gray-900/10 text-blue-gray-900 shadow-none hover:scale-105 hover:shadow-none focus:scale-105 focus:shadow-none active:scale-100"
                                type="button">
                                Select Table
                            </button>
                        </a>
                    </x-slot>
                </x-card>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let tables = @json($datas);
            tables.forEach(function (data) {
                let elementId = "qrcode-" + data.table_code;
                let url = "{{ url('/scan') }}/" + data.table_code;

                new QRCode(document.getElementById(elementId), {
                    text: url,
                    width: 100,
                    height: 100
                });
            });
        });
    </script>
@endsection
