<nav class="bg-white rounded-lg p-4">
    <div class="flex justify-between items-center">
        <a href="{{ route('user.table') }}">
            <div class="flex items-center">
                <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-10 mr-4">
                <h2 class="text-xl font-semibold text-gray-800 hidden sm:block">YOSHIMIE RESTAURANT</h2>
                <h2 class="text-lg font-semibold text-gray-800 sm:hidden">YOSHIMIE</h2>
            </div>
        </a>
       
        <div class="flex items-center space-x-4">
            @auth
                <!-- cart & history button usual -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.cart') }}" class="text-gray-500 hover:text-gray-700 relative group">
                        <i class="ph ph-shopping-bag-open text-2xl"></i>
                        <span class="absolute left-1/2 transform -translate-x-1/2 translate-y-8 opacity-0 group-hover:opacity-100 transition-opacity bg-white text-gray-700 text-xs rounded px-1 mt-2">Cart</span>
                    </a>
                    <a href="{{ route('user.history') }}" class="text-gray-500 hover:text-gray-700 relative group">
                        <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                        <span class="absolute left-1/2 transform -translate-x-1/2 translate-y-8 opacity-0 group-hover:opacity-100 transition-opacity bg-white text-gray-700 text-xs rounded px-1 mt-2">History</span>
                    </a>
                </div>

                <!-- User dropdown -->
                <div class="relative">
                    <button id="dropdownButton" onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                            alt="Profile" class="w-8 h-8 rounded-full">
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        <i id="caretIcon" class="ph ph-caret-down"></i>
                    </button>
                   
                    <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden z-50">
                        <!-- Cart& History on small screen -->
                        <div class="md:hidden border-b border-gray-200">
                            <a href="{{ route('user.cart') }}" class="flex w-full px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="ph ph-shopping-bag-open text-xl mr-2"></i>
                                Cart
                            </a>
                            <a href="{{ route('user.history') }}" class="flex w-full px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="ph ph-clock-counter-clockwise text-xl mr-2"></i>
                                History
                            </a>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex w-full px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="ph ph-user-circle text-xl mr-2"></i>
                            {{ __('Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full px-4 py-2 text-gray-500 hover:bg-gray-100">
                                <i class="ph ph-sign-out text-xl mr-2"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login</a>
                <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700 ">Register</a>
            @endauth
        </div>
    </div>
</nav>
<script>
    function toggleDropdown() {
        let dropdown = document.getElementById('userDropdown');
        let caretIcon = document.getElementById('caretIcon');

        dropdown.classList.toggle('hidden');
        if (dropdown.classList.contains('hidden')) {
            caretIcon.classList.remove('ph-caret-up');
            caretIcon.classList.add('ph-caret-down');
        } else {
            caretIcon.classList.remove('ph-caret-down');
            caretIcon.classList.add('ph-caret-up');
        }
    }

    document.addEventListener('click', function(event) {
        let dropdown = document.getElementById('userDropdown');
        let button = document.getElementById('dropdownButton');

        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
            document.getElementById('caretIcon').classList.remove('ph-caret-up');
            document.getElementById('caretIcon').classList.add('ph-caret-down');
        }
    });
</script>
