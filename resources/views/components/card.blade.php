<div class="relative flex flex-col bg-white shadow-lg rounded-xl overflow-hidden w-full transition-transform transform hover:-translate-y-1 hover:shadow-xl">
    <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-xl h-40 flex items-center justify-center">
        {{ $content }}
    </div>
    <div class="p-5">
        {{ $tittle }}
        <p class="block text-gray-600 text-sm font-light leading-snug mt-4">
            {{ $description }}
        </p>
    </div>
    <div class="mt-auto pb-4">
        {{ $button }}
    </div>
</div>
