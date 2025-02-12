<div class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-[300px] overflow-y-auto text-center bg-gray-900">
    <div class="text-gray-100 text-xl">
        <div class="p-2.5 mt-1 flex items-center">
            <h1 class="font-bold text-gray-200 text-[15px] ml-3">YOSHIMIE DASHBOARD</h1>
        </div>
        <div class="my-2 bg-gray-600 h-[1px]"></div>
    </div>

    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
        <i class="ph ph-house-line"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Dashboard</span>
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
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Food list</h1>
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Add new food</h1>
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
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Order in</h1>
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Order history</h1>
    </div>

    <!-- Dropdown: Table -->
    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
         onclick="dropdown('table-menu', 'table-arrow')">
        <i class="ph ph-desk"></i>
        <div class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Table</span>
            <span class="text-sm" id="table-arrow">
                <i class="ph ph-caret-down"></i>
            </span>
        </div>
    </div>
    <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="table-menu">
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Table list</h1>
    </div>

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
        <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Sales report</h1>
    </div>

    <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
        <i class="ph ph-sign-out"></i>
        <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
    </div>
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
