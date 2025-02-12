<nav class="bg-white rounded-lg p-4">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
        </div>
        <div class="flex items-center space-x-4">
            <button class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-bell text-xl"></i>
            </button>
            <div class="relative">
                <button class="flex items-center space-x-2 text-gray-500 hover:text-gray-700">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin User' }}"
                         alt="Profile"
                         class="w-8 h-8 rounded-full">
                    <span>{{ Auth::user()->name ?? 'Admin User' }}</span>
                </button>
            </div>
        </div>
    </div>
</nav>
