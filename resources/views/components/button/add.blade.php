@auth
    @if(auth()->user()->role_id == 1)
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ $route }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="ph ph-plus mr-2"></i>{{$massage}}
            </a>
        </div>
    @endif
@endauth
