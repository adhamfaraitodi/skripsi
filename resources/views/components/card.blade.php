<div class="relative flex flex-col bg-white shadow-lg rounded-xl overflow-hidden w-full transition-transform transform hover:-translate-y-1 hover:shadow-xl">
    <div class="relative mx-2 sm:mx-4 mt-4 overflow-hidden bg-white rounded-xl h-32 sm:h-40 flex items-center justify-center">
        {{ $content }}
    </div>
    <div class="px-4 sm:px-5 py-3">
        {{ $tittle }}
        @if(trim($description ?? '') !== '')
            <p class="block text-gray-600 text-sm sm:text-base font-light leading-snug mt-3">
                {{ $description }}
            </p>
        @endif
    </div>
    <div class="mt-auto px-4 sm:px-5 pb-4">
        {{ $button }}
    </div>
</div>

