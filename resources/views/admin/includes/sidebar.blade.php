<div class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-[300px] overflow-y-auto text-center bg-gray-900">
    <div class="text-gray-100 text-xl">
        <div class="p-2.5 mt-1 flex items-center">
            <img src="{{ asset('storage/icon/icon.png') }}" alt="Logo" class="w-8 h-9 mr-3">
            <h1 class="font-bold text-gray-200 text-[15px]">YOSHIMIE DASHBOARD</h1>
        </div>
        <div class="my-2 bg-gray-600 h-[1px]"></div>
    </div>


    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
        <i class="ph ph-house-line"></i>
        <a href="{{ route('superadmin.dashboard') }}"><span class="text-[15px] ml-4 text-gray-200 font-bold">Dashboard</span></a>
    </div>

    <!-- Dropdown: Food -->
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
         onclick="dropdown('food-menu', 'food-arrow')">
        <i class="ph ph-bowl-food"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Food</span>
            <span class="text-sm" id="food-arrow">
                <i class="ph ph-caret-down"></i>
            </span>
        </div>
    </div>
    <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="food-menu">
        <a href="{{ route('food.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Food list</h1></a>
        <a href="{{ route('category.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Food category</h1></a>
        <a href="{{ route('food.inventory.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Food inventory</h1></a>
    </div>

    <!-- Dropdown: Order -->
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
         onclick="dropdown('order-menu', 'order-arrow')">
        <i class="ph ph-bag"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Order</span>
            <span class="text-sm" id="order-arrow">
                <i class="ph ph-caret-down"></i>
            </span>
        </div>
    </div>
    <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="order-menu">
        <a href="{{ route('order.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Order in</h1></a>
        <a href="{{ route('order.history.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Order history</h1></a>
    </div>

    <!-- Single: Table -->
    <a href="{{ route('table.index') }}">
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
            <i class="ph ph-desk"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Table</span>
            </div>
        </div>
    </a>
    <!-- Dropdown: Report -->
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
         onclick="dropdown('report-menu', 'report-arrow')">
        <i class="ph ph-file"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Report</span>
            <span class="text-sm" id="report-arrow">
                <i class="ph ph-caret-down"></i>
            </span>
        </div>
    </div>
    <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="report-menu">
        <a href="{{ route('sales.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Sales report</h1></a>
        <a href="{{ route('inventory.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Inventory report</h1></a>
        <a href="{{ route('financial.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Financial report</h1></a>
    </div>

    <!-- Dropdown: Staff -->
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
         onclick="dropdown('staff-menu', 'staff-arrow')">
        <i class="ph ph-users-three"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Staff</span>
            <span class="text-sm" id="staff-arrow">
                <i class="ph ph-caret-down"></i>
            </span>
        </div>
    </div>
    <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="staff-menu">
        <a href="{{ route('staff.index') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Staff Management</h1></a>
        <a href="{{ route('staff.create') }}"><h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Staff register</h1></a>
    </div>
    {{--    Single: Profile--}}
    <a href="{{ route('profile.edit') }}">
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
        <i class="ph ph-user-circle-gear"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Profile</span>
        </div>
    </div>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white" onclick="event.preventDefault(); this.closest('form').submit();">
            <i class="ph ph-sign-out"></i>
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
        </div>
    </form>
</div>
<script>
    function dropdown(submenuId, arrowId) {
        let submenu = document.getElementById(submenuId);
        let arrow = document.querySelector(`#${arrowId} i`);

        submenu.classList.toggle("hidden");

        if (arrow.classList.contains("ph-caret-down")) {
            arrow.classList.remove("ph-caret-down");
            arrow.classList.add("ph-caret-up");
        } else {
            arrow.classList.remove("ph-caret-up");
            arrow.classList.add("ph-caret-down");
        }
    }

    function openSidebar() {
        document.querySelector(".sidebar").classList.toggle("hidden");
    }
</script>
