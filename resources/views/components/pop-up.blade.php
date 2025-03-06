<button onclick="openModal('{{ $id }}')"
        class="text-blue-600 hover:text-blue-900 flex items-center transition-all duration-300">
    <span>View Detail</span>
</button>
<div id="{{ $id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-md p-4 w-1/2 relative">
        <h4 class="text-gray-700 font-semibold text-xl pl-4 mb-2">{{$title}}</h4>
        <button onclick="closeModal('{{ $id }}')" class="absolute top-3 right-1 mt-2 mr-2 text-gray-600 hover:text-gray-900">
            <i class="ph ph-x text-xl"></i>
        </button>
        {{ $content }}
    </div>
</div>