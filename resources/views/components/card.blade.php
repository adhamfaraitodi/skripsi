<div class="relative flex flex-col text-gray-700 bg-white shadow-md bg-clip-border rounded-xl w-full">
    <div class="relative mx-4 mt-4 overflow-hidden text-gray-700 bg-white bg-clip-border rounded-xl h-30 flex items-center justify-center">
        {{ $content }}
    </div>

    <div class="p-6">
        {{ $tittle }}
        <p class="block font-sans text-sm antialiased font-normal leading-normal text-gray-700 opacity-75 ">
            {{ $description }}
        </p>
    </div>
    <div class="mt-auto pb-4">
        {{ $button }}
    </div>
</div>
